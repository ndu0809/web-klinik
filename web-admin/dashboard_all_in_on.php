<?php
session_start();

/* =============================
   CONFIG & DATABASE
============================= */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

/* =============================
   CEK LOGIN
============================= */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =============================
   KONEKSI DATABASE
============================= */
 $db   = new Database();
 $conn = $db->getConnection();

if (!$conn) {
    die('Koneksi database gagal');
}

 $sqlPasien = "
SELECT 
  pa.id_pasien,
  pa.nama_pasien,
  pa.no_whatsapp,
  pa.tempat_lahir,
  pa.tanggal_lahir,
  pa.alamat,
  COALESCE(pa.keluhan, pel.keluhan) AS keluhan,
  pa.pekerjaan,
  pa.anda_adalah
FROM pasien pa
LEFT JOIN pelayanan pel 
ON pa.id_pasien = pel.id_pasien
ORDER BY pa.id_pasien DESC
";

 $resultPasien = $conn->query($sqlPasien);

 $pasienData = [];
while ($row = $resultPasien->fetch_assoc()) {
  $pasienData[] = [
    'id_pasien'     => (int)$row['id_pasien'],
    'nama'          => $row['nama_pasien'],
    'whatsapp'      => $row['no_whatsapp'],
    'tempatLahir'   => $row['tempat_lahir'],
    'tanggalLahir'  => $row['tanggal_lahir'],
    'alamat'        => $row['alamat'],
    'keluhan'       => !empty($row['keluhan']) ? $row['keluhan'] : '-',
    'pekerjaan'     => $row['pekerjaan'],
    'andaAdalah'    => $row['anda_adalah']
  ];
}


/* =============================
   QUERY DATA RESERVASI (FINAL)
============================= */
 $sql = "
SELECT
    pel.id_pelayanan,
    pel.id_pasien,
    pel.tanggal_pelayanan,
    pel.jadwal_booking,
    pel.status,
    pel.sudah_dilayani,
    pel.keluhan AS keluhan_pelayanan,

    pa.nama_pasien,
    pa.no_whatsapp,
    pa.anda_adalah,
    pa.tempat_lahir,
    pa.tanggal_lahir,
    pa.alamat,
    pa.pekerjaan,
    pa.keluhan AS keluhan_pasien

FROM pelayanan pel
LEFT JOIN pasien pa 
ON pel.id_pasien = pa.id_pasien

ORDER BY pel.id_pelayanan DESC
";

 $result = $conn->query($sql);
if (!$result) {
    die('QUERY ERROR: ' . $conn->error);
}

/* =============================
   MAPPING DATA → JS
============================= */
 $reservations = [];
while ($r = $result->fetch_assoc()) {

 $reservations[] = [

'id' => (int)$r['id_pelayanan'],
'id_pasien' => (int)$r['id_pasien'],

'nama' => $r['nama_pasien'] ?? 'Unknown',
'whatsapp' => $r['no_whatsapp'] ?? '-',

'andaAdalah' => $r['anda_adalah'] ?? '-',

'tempatLahir' => $r['tempat_lahir'] ?? '-',
'tanggalLahir' => $r['tanggal_lahir'] ?? '-',

'alamat' => $r['alamat'] ?? '-',

'keluhan' => $r['keluhan_pasien'] ?? $r['keluhan_pelayanan'] ?? '-',

'pekerjaan' => $r['pekerjaan'] ?? '-',

'jadwalBooking' => $r['jadwal_booking'] ?? '-',

'status' => strtolower($r['status'] ?? 'menunggu'),

'sudahDilayani' => (bool)$r['sudah_dilayani'],

'timestamp' => $r['tanggal_pelayanan']

];
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - drg Pasri</title>
  <?php include 'components/navbar.php'; ?>
<script src="assets/js/navbar.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <span id="notifBadge"></span>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #555; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0;} to { transform: translateX(0); opacity: 1;} }
    .notification { animation: slideIn 0.3s ease-out; }
    .spinner { border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top: 3px solid white; width:20px; height:20px; animation: spin 1s linear infinite; }
    @keyframes spin { 0%{transform:rotate(0);}100%{transform:rotate(360deg);} }
    #notifBadge {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% {transform: scale(1);}
  50% {transform: scale(1.2);}
  100% {transform: scale(1);}
}

/* Responsive table styles */
@media (max-width: 640px) {
  .responsive-table {
    font-size: 0.75rem;
  }
  .responsive-table th, 
  .responsive-table td {
    padding: 0.25rem 0.5rem;
  }
  .action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
}

/* Toast notification styles */
.toast {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #333;
  color: white;
  padding: 12px 20px;
  border-radius: 4px;
  z-index: 1000;
  animation: slideIn 0.3s ease-out;
  max-width: 80%;
  word-wrap: break-word;
}

/* Responsive modal styles */
@media (max-width: 640px) {
  .modal-content {
    width: 95%;
    max-height: 90vh;
    overflow-y: auto;
  }
}
  </style>
</head>

<body class="font-sans text-gray-900 bg-gradient-to-br from-white via-blue-50 to-blue-100 min-h-screen">
<audio id="notifSound">
    <source src="notif.mp3" type="audio/mpeg">
</audio>
<!-- MODAL DETAIL PASIEN -->
<div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative modal-content">
    <button onclick="closeDetailModal()" class="absolute top-2 right-3 text-gray-500 hover:text-red-600 text-xl">
      <i class="fas fa-times"></i>
    </button>

    <h2 class="text-lg font-bold mb-4 text-gray-800">
      Detail Pasien
    </h2>

    <div id="detailContent" class="space-y-2 text-sm text-gray-700"></div>
  </div>
</div>


<section id="dashboard">
  <!-- Main Content -->
  <main class="max-w-7xl mx-auto py-4 sm:py-6 px-2 sm:px-4 lg:px-8">

    <!-- STATUS CARD -->
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 mb-6">

<!-- Total -->
<div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border flex justify-between items-center">
  <div>
    <p class="text-xs sm:text-sm text-gray-500">Total</p>
    <p id="statTotal" class="text-xl sm:text-2xl font-bold text-gray-800">0</p>
  </div>
  <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
    <i class="fas fa-users text-sm sm:text-base"></i>
  </div>
</div>

<!-- Menunggu -->
<div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border flex justify-between items-center">
  <div>
    <p class="text-xs sm:text-sm text-gray-500">Menunggu</p>
    <p id="statMenunggu" class="text-xl sm:text-2xl font-bold text-yellow-500">0</p>
  </div>
  <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center">
    <i class="fas fa-clock text-sm sm:text-base"></i>
  </div>
</div>

<!-- Dikonfirmasi -->
<div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border flex justify-between items-center">
  <div>
    <p class="text-xs sm:text-sm text-gray-500">Dikonfirmasi</p>
    <p id="statDikonfirmasi" class="text-xl sm:text-2xl font-bold text-green-600">0</p>
  </div>
  <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
    <i class="fas fa-check text-sm sm:text-base"></i>
  </div>
</div>

<!-- Dibatalkan -->
<div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border flex justify-between items-center">
  <div>
    <p class="text-xs sm:text-sm text-gray-500">Dibatalkan</p>
    <p id="statDibatalkan" class="text-xl sm:text-2xl font-bold text-red-500">0</p>
  </div>
  <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 text-red-500 rounded-full flex items-center justify-center">
    <i class="fas fa-xmark text-sm sm:text-base"></i>
  </div>
</div>

</div>

    <!-- Filter Section unchanged visually -->
    <div class="bg-white/95 backdrop-blur rounded-2xl shadow-xl border border-blue-100">
      <div class="p-3 sm:p-4 lg:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800"><i class="fas fa-calendar-check mr-2 text-blue-600"></i>Data Reservasi Pasien</h1>
          <div class="flex items-center w-full sm:w-auto space-x-2">
            <input id="searchInput" type="text" placeholder="Cari nama pasien..." class="flex-1 sm:flex-none px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button onclick="searchReservations()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm"><i class="fas fa-search"></i></button>
          </div>
        </div>

        <!-- FILTER TABS (cannot visually change) → we add new logic only -->
        <div class="flex flex-wrap gap-1 sm:gap-2 mt-4 bg-blue-50 p-1 rounded-xl border border-blue-100">
          <button class="filter-tab flex-1 min-w-[60px] sm:min-w-[80px] px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm font-medium bg-white text-blue-700 shadow-sm" data-filter="all" onclick="filterReservations('all')"><i class="fas fa-list mr-1"></i><span class="hidden sm:inline">Semua</span></button>
          <button class="filter-tab flex-1 min-w-[60px] sm:min-w-[80px] px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm font-medium text-gray-600" data-filter="menunggu" onclick="filterReservations('menunggu')"><i class="fas fa-hourglass-half mr-1"></i><span class="hidden sm:inline">Menunggu</span></button>
          <button class="filter-tab flex-1 min-w-[60px] sm:min-w-[80px] px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm font-medium text-gray-600" data-filter="dikonfirmasi" onclick="filterReservations('dikonfirmasi')"><i class="fas fa-check mr-1"></i><span class="hidden sm:inline">Dikonfirmasi</span></button>
          <button class="filter-tab flex-1 min-w-[60px] sm:min-w-[80px] px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm font-medium text-gray-600" data-filter="dibatalkan" onclick="filterReservations('dibatalkan')"><i class="fas fa-times mr-1"></i><span class="hidden sm:inline">Dibatalkan</span></button>
          <button class="filter-tab flex-1 min-w-[60px] sm:min-w-[80px] px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm font-medium text-gray-600" data-filter="sudah" onclick="filterReservations('sudah')"><i class="fas fa-user-check mr-1"></i><span class="hidden sm:inline">Sudah Dilayani</span></button>
          <button class="filter-tab flex-1 min-w-[60px] sm:min-w-[80px] px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm font-medium text-gray-600" data-filter="belum" onclick="filterReservations('belum')"><i class="fas fa-user-clock mr-1"></i><span class="hidden sm:inline">Belum Dilayani</span></button>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto custom-scrollbar">
      <table class="min-w-full border border-gray-300 border-collapse text-xs sm:text-sm responsive-table">
      <thead class="bg-gradient-to-r from-blue-50 to-blue-100 text-gray-700">
<tr>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">No</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-left">Nama</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden sm:table-cell">Tempat Lahir</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden sm:table-cell">Tanggal Lahir</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden sm:table-cell">WhatsApp</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">Alamat</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">Keluhan</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">Pekerjaan</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">Jadwal</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">Anda Adalah</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">Status</th>
<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">Aksi</th>

</tr>
</thead>

          <tbody id="reservationTableBody" class="bg-white divide-y divide-gray-200"></tbody>
        </table>
        <div id="noReservationsMessage" class="hidden text-center py-8 sm:py-12 text-gray-500">
          <i class="fas fa-inbox text-4xl sm:text-5xl mb-4 text-gray-300"></i>
          <p class="text-base sm:text-lg">Tidak ada data reservasi</p>
          <p class="text-sm mt-2">Belum ada reservasi yang masuk</p>
        </div>
      </div>
    </div>
  </main>
</section>

  <!-- Persentase Data Klinik -->
  <section id="clinic-stats" class="mt-6 sm:mt-10 p-4 sm:p-8 bg-white/95 backdrop-blur rounded-2xl shadow-xl border border-blue-100">
    <h2 class="text-lg sm:text-xl font-bold mb-4">Persentase Data Klinik</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-3 sm:p-4 bg-blue-100 rounded-lg">
        <p class="text-base sm:text-lg font-semibold">Total Pasien</p>
        <span id="totalPatients" class="text-lg sm:text-2xl font-bold">0%</span>
      </div>
      <div class="p-3 sm:p-4 bg-green-100 rounded-lg">
        <p class="text-base sm:text-lg font-semibold">Persentase Terlayani</p>
        <span id="percentageServed" class="text-lg sm:text-2xl font-bold">0%</span>
      </div>
      <div class="p-3 sm:p-4 bg-red-100 rounded-lg">
        <p class="text-base sm:text-lg font-semibold">Persentase Dibatalkan</p>
        <span id="percentageCancelled" class="text-lg sm:text-2xl font-bold">0%</span>
      </div>
    </div>
  </section>
<section id="laporan">
  <!-- Page percentage (kept identical) -->
  <section id="page-percentage" class="mt-10 sm:mt-20 p-6 sm:p-10 bg-gradient-to-br from-blue-50 via-white to-blue-100 rounded-3xl shadow-2xl border border-blue-200">
    <h1 class="text-2xl sm:text-3xl font-extrabold mb-6 sm:mb-10 text-gray-800 tracking-wide text-center">📊 Laporan Persentase Klinik</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8 mb-6 sm:mb-10">
      <div class="p-3 sm:p-6 bg-white rounded-xl sm:rounded-2xl shadow-md border hover:shadow-xl transition">
        <p class="text-xs sm:text-sm font-semibold text-gray-500">Total Pasien</p>
        <span id="p_total" class="text-2xl sm:text-3xl font-bold text-blue-700">0</span>
      </div>
      <div class="p-3 sm:p-6 bg-white rounded-xl sm:rounded-2xl shadow-md border hover:shadow-xl transition">
        <p class="text-xs sm:text-sm font-semibold text-gray-500">Pasien Terlayani</p>
        <span id="p_served" class="text-2xl sm:text-3xl font-bold text-green-700">0</span>
      </div>
      <div class="p-3 sm:p-6 bg-white rounded-xl sm:rounded-2xl shadow-md border hover:shadow-xl transition">
        <p class="text-xs sm:text-sm font-semibold text-gray-500">Belum Dilayani</p>
        <span id="p_pending" class="text-2xl sm:text-3xl font-bold text-yellow-700">0</span>
      </div>
      <div class="p-3 sm:p-6 bg-white rounded-xl sm:rounded-2xl shadow-md border hover:shadow-xl transition">
        <p class="text-xs sm:text-sm font-semibold text-gray-500">Pasien Dibatalkan</p>
        <span id="p_cancelled" class="text-2xl sm:text-3xl font-bold text-red-700">0</span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-8 mb-8 sm:mb-12">
      <div class="p-4 sm:p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl shadow border">
        <p class="text-sm font-semibold text-gray-700">Persentase Terlayani</p>
        <span id="p_percent_served" class="text-xl sm:text-2xl font-bold text-green-800">0%</span>
      </div>
      <div class="p-4 sm:p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl sm:rounded-2xl shadow border">
        <p class="text-sm font-semibold text-gray-700">Persentase Belum Dilayani</p>
        <span id="p_percent_pending" class="text-xl sm:text-2xl font-bold text-yellow-800">0%</span>
      </div>
      <div class="p-4 sm:p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-xl sm:rounded-2xl shadow border">
        <p class="text-sm font-semibold text-gray-700">Persentase Dibatalkan</p>
        <span id="p_percent_cancelled" class="text-xl sm:text-2xl font-bold text-red-800">0%</span>
      </div>
    </div>

    <div class="mt-8 sm:mt-12 bg-white rounded-xl sm:rounded-2xl shadow-xl border p-4 sm:p-8">
      <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-800">📄 Detail status Pasien</h2>
      <div class="overflow-x-auto custom-scrollbar">
      <table class="w-full border-collapse text-xs sm:text-sm">
        <thead>
          <tr class="bg-gray-100 text-left text-gray-700">
            <th class="border p-2 sm:p-3">Nama</th>
            <th class="border p-2 sm:p-3">Status</th>
            <th class="border p-2 sm:p-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="patientDetailTable" class="text-gray-800"></tbody>
      </table>
      </div>
    </div>
  </section>
  </section>
  
  <section id="data-pasien" class="mt-6 sm:mt-10">
  <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-blue-100">

    <div class="p-4 sm:p-6 border-b border-blue-100 flex justify-between items-center">
      <h2 class="text-xl sm:text-2xl font-bold text-blue-700 flex items-center gap-2">
        <i class="fas fa-user-injured"></i> Data Pasien
      </h2>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
    <table class="min-w-full text-xs sm:text-sm border border-gray-300 border-collapse responsive-table">
    <thead class="bg-blue-50 text-blue-800">
<tr>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">No</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-left">
Nama
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">
WhatsApp
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">
Anda Adalah
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">
Tempat, Tgl Lahir
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden lg:table-cell">
Pekerjaan
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden lg:table-cell">
Alamat
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 hidden md:table-cell">
Keluhan
</th>

<th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
Aksi
</th>

</tr>
</thead>
        <tbody id="pasienTable"></tbody>
      </table>
    </div>

  </div>
</section>

  <script>
    setInterval(() => {
  reloadFromServer();
  reloadPasien();
}, 3000);
/* ===============================
   STATE
================================ */
let reservations = <?php echo json_encode($reservations, JSON_UNESCAPED_UNICODE); ?>;
let pasien = <?php echo json_encode($pasienData, JSON_UNESCAPED_UNICODE); ?>;
let currentFilter = 'all';

/* ===============================
   INIT
================================ */
document.addEventListener('DOMContentLoaded', init);

function init() {
  render();
  renderPasien();
}

/* ===============================
   MAIN RENDER
================================ */
function render() {
  const keyword = document.getElementById('searchInput')?.value || '';
  const data = getFilteredData(currentFilter, keyword);

  renderTable(data);
  renderStats();
}

/* ===============================
   DATA FILTERING
================================ */
function getFilteredData(filter, keyword = '') {
  let data = [...reservations];

  if (filter === 'sudah') {
    data = data.filter(r => r.status === 'dikonfirmasi' && r.sudahDilayani);
  } 
  else if (filter === 'belum') {
    data = data.filter(r => r.status === 'dikonfirmasi' && !r.sudahDilayani);
  } 
  else if (filter !== 'all') {
    data = data.filter(r => r.status === filter);
  }

  if (keyword) {
    keyword = keyword.toLowerCase();
    data = data.filter(r => r.nama.toLowerCase().includes(keyword));
  }

  return data;
}

/* ===============================
   TABLE RENDER
================================ */
function renderTable(data) {
  const tbody = document.getElementById('reservationTableBody');
  const emptyMsg = document.getElementById('noReservationsMessage');

  tbody.innerHTML = '';
  emptyMsg.classList.toggle('hidden', data.length !== 0);

  data.forEach((r, i) => {
    tbody.appendChild(createRow(r, i));
  });
}

function createRow(r, i) {
  const tr = document.createElement('tr');
  tr.className = "hover:bg-blue-50 transition";

  tr.innerHTML = `
    <td class="border px-2 sm:px-4 py-1 sm:py-2 text-center">${i + 1}</td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 font-semibold">${r.nama}</td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden sm:table-cell">${r.tempatLahir}</td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden sm:table-cell">${r.tanggalLahir}</td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden sm:table-cell">${r.whatsapp}</td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell max-w-xs truncate"
        title="${r.alamat}">
        ${r.alamat}
    </td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell max-w-xs truncate"
        title="${r.keluhan}">
        ${r.keluhan}
    </td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">
        ${r.pekerjaan}
    </td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">
        ${r.jadwalBooking}
    </td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">
        ${r.andaAdalah}
    </td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2 text-center">
      <span class="px-1 sm:px-2 py-1 text-xs rounded-full ${statusBadge(r.status)}">
        ${r.status}
      </span>
    </td>

    <td class="border px-2 sm:px-4 py-1 sm:py-2">
      <div class="flex items-center justify-center gap-1 sm:gap-3 text-sm sm:text-lg action-buttons">
        ${actionButtons(r)}
      </div>
    </td>
  `;

  return tr;
}

function statusBadge(status) {
  if (status === 'menunggu') return 'bg-yellow-50 text-yellow-700';
  if (status === 'dikonfirmasi') return 'bg-green-50 text-green-700';
return 'bg-red-50 text-red-700';
}

function actionButtons(r) {
  return `

<button onclick="setStatus(${r.id}, 'dikonfirmasi')"
class="text-green-600 hover:text-green-800 transition p-1"
title="Konfirmasi">
<i class="fas fa-check"></i>
</button>

<button onclick="setStatus(${r.id}, 'dibatalkan')"
class="text-red-600 hover:text-red-800 transition p-1"
title="Batalkan">
<i class="fas fa-times"></i>
</button>

 ${
r.status === 'dikonfirmasi'
? `<button onclick="toggleServed(${r.id}, ${r.sudahDilayani ? 0 : 1})"
class="${r.sudahDilayani ? 'text-green-600' : 'text-gray-400'} hover:text-green-800 p-1"
title="Sudah Dilayani">
<i class="fas fa-user-check"></i>
</button>`
: ''
}

<button onclick="lihatDetail(${r.id})"
class="text-blue-600 hover:text-blue-800 p-1"
title="Detail">
<i class="fas fa-eye"></i>
</button>

<button onclick="deleteReservasi(${r.id})"
class="text-red-700 hover:text-red-900 p-1"
title="Hapus">
<i class="fas fa-trash"></i>
</button>

`;
}

/* ===============================
   FILTER & SEARCH
================================ */
function filterReservations(filter) {
  currentFilter = filter;
  render();
}

function searchReservations() {
  render();
}

/* ===============================
   STATISTICS
================================ */
function renderStats() {
  const total = reservations.length;

  const served     = reservations.filter(r => r.status === 'dikonfirmasi' && r.sudahDilayani).length;
  const pending    = reservations.filter(r => r.status === 'dikonfirmasi' && !r.sudahDilayani).length;
  const cancelled  = reservations.filter(r => r.status === 'dibatalkan').length;
  const menunggu   = reservations.filter(r => r.status === 'menunggu').length;
  const confirm    = reservations.filter(r => r.status === 'dikonfirmasi').length;

  setText('statTotal', total);
  setText('statMenunggu', menunggu);
  setText('statDikonfirmasi', confirm);
  setText('statDibatalkan', cancelled);

  setText('totalPatients', total);
  setText('percentageServed', percentage(served, total));
  setText('percentageCancelled', percentage(cancelled, total));

  setText('p_total', total);
  setText('p_served', served);
  setText('p_pending', pending);
  setText('p_cancelled', cancelled);

  setText('p_percent_served', percentage(served, total));
  setText('p_percent_pending', percentage(pending, total));
  setText('p_percent_cancelled', percentage(cancelled, total));

  renderPatientDetail();
}

function percentage(val, total) {
  return total ? Math.round((val / total) * 100) + '%' : '0%';
}

function setText(id, value) {
  const el = document.getElementById(id);
  if (el) el.innerText = value;
}

/* ===============================
   DETAIL TABLE
================================ */
function renderPatientDetail() {
  const tbody = document.getElementById('patientDetailTable');
  if (!tbody) return;

  tbody.innerHTML = '';

  if (!reservations.length) {
    tbody.innerHTML = `
      <tr>
        <td colspan="3" class="border p-2 sm:p-4 text-center text-gray-500">
          Tidak ada data pasien
        </td>
      </tr>`;
    return;
  }

  reservations.forEach(r => {
    const status =
      r.status === 'menunggu' ? 'Menunggu Konfirmasi' :
      r.status === 'dibatalkan' ? 'Dibatalkan' :
      r.sudahDilayani ? 'Sudah Dilayani' : 'Belum Dilayani';

    tbody.innerHTML += `
      <tr>
        <td class="border p-2 sm:p-3">${r.nama}</td>
        <td class="border p-2 sm:p-3">${status}</td>
        <td class="border p-2 sm:p-3 text-center">
          <i class="fas fa-user text-blue-600"></i>
        </td>
      </tr>`;
  });
}

/* ===============================
   AJAX
================================ */
function postAction(data) {
  return fetch('./ajax_reservasi.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(data)
  }).then(r => r.json());
}

function reloadFromServer() {
  fetch('./ajax_reservasi.php?action=get_all')
    .then(r => r.json())
    .then(res => {
      if (!res.success) return;

      reservations = res.data;
      render();
    })
    .catch(err => console.error(err));
}

/* ===============================
   ACTIONS
================================ */
function setStatus(id, status) {
  postAction({ action: 'update_status', id, status })
    .then(r => r.success && reloadFromServer());
}

function toggleServed(id, value) {
  postAction({ action: 'toggle_served', id, value })
    .then(r => r.success && reloadFromServer());
}

function deleteReservasi(id) {

if (!confirm("Yakin ingin menghapus reservasi ini?")) return;

fetch("ajax_reservasi.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/x-www-form-urlencoded"
  },
  body: new URLSearchParams({
    action: "delete_reservasi",
    id: id
  })
})
.then(res => res.json())
.then(res => {
    if(res.success){
        alert("Reservasi berhasil dihapus");
        location.reload();
    }else{
        alert(res.message);
    }
});
}

/* ===============================
   MODAL
================================ */
function closeDetailModal() {
  document.getElementById('modalDetail').classList.add('hidden');
  document.getElementById('modalDetail').classList.remove('flex');
}

function lihatDetail(id) {
  const data = reservations.find(r => r.id === id);
  if (!data) return;

  // ✅ status text
  const statusText =
    data.status === 'menunggu' ? 'Menunggu' :
    data.status === 'dikonfirmasi' ? 'Dikonfirmasi' :
    'Dibatalkan';

  // ✅ pelayanan hanya tampil kalau bukan dibatalkan
  let pelayananHTML = '';

  if (data.status !== 'dibatalkan') {
    pelayananHTML = `
      <div><strong>Pelayanan:</strong> 
        ${data.sudahDilayani ? 
          '<span class="text-green-600 font-semibold">Sudah Dilayani</span>' :
          '<span class="text-gray-600 font-semibold">Belum Dilayani</span>'
        }
      </div>
    `;
  }

  const content = `
    <div><strong>Nama:</strong> ${data.nama}</div>
    <div><strong>WhatsApp:</strong> ${data.whatsapp}</div>
    <div><strong>Anda Adalah:</strong> ${data.andaAdalah}</div>
    <div><strong>Tempat Lahir:</strong> ${data.tempatLahir}</div>
    <div><strong>Tanggal Lahir:</strong> ${data.tanggalLahir}</div>
    <div><strong>Alamat:</strong> ${data.alamat}</div>
    <div><strong>Keluhan:</strong> ${data.keluhan}</div>
    <div><strong>Pekerjaan:</strong> ${data.pekerjaan}</div>
    <div><strong>Jadwal Booking:</strong> ${data.jadwalBooking}</div>

    <div><strong>Status:</strong> 
      <span class="font-semibold ${
        data.status === 'menunggu' ? 'text-yellow-600' :
        data.status === 'dikonfirmasi' ? 'text-green-600' :
        'text-red-600'
      }">
        ${statusText}
      </span>
    </div>

    ${pelayananHTML}
  `;

  document.getElementById('detailContent').innerHTML = content;

  const modal = document.getElementById('modalDetail');
  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

function toggleSidebar() {
  document.getElementById('sidebar')
    .classList.toggle('-translate-x-full');
}

function renderPasien(){

const tbody=document.getElementById('pasienTable');

tbody.innerHTML="";

if(!pasien.length){

tbody.innerHTML=`
<tr>
<td colspan="9" class="text-center py-4 sm:py-6 text-gray-500">
Tidak ada data pasien
</td>
</tr>
`;

return;

}

pasien.forEach((p,i)=>{

tbody.innerHTML+=`

<tr class="hover:bg-blue-50">

<td class="border px-2 sm:px-4 py-1 sm:py-2 text-center">${i+1}</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 font-semibold">${p.nama}</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">${p.whatsapp ?? '-'}</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">${p.andaAdalah ?? '-'}</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">
 ${p.tempatLahir}, ${p.tanggalLahir}
</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 hidden lg:table-cell">
 ${p.pekerjaan ?? '-'}
</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 hidden lg:table-cell">
 ${p.alamat ?? '-'}
</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 hidden md:table-cell">
 ${p.keluhan ?? '-'}
</td>

<td class="border px-2 sm:px-4 py-1 sm:py-2 text-center">

<div class="flex justify-center gap-1 sm:gap-3 action-buttons">

<button onclick="lihatDetailPasien(${p.id_pasien})"
class="text-blue-600 hover:text-blue-800 p-1">

<i class="fas fa-eye"></i>

</button>

<button onclick="openEditPasien(${p.id_pasien})"
class="text-green-600 hover:text-green-800 p-1">

<i class="fas fa-pen"></i>

</button>

<button onclick="hapusPasien(${p.id_pasien})"
class="text-red-600 hover:text-red-800 p-1">

<i class="fas fa-trash"></i>

</button>

</div>

</td>

</tr>

`;

});

}

function lihatDetailPasien(id) {
  const p = pasien.find(x => x.id_pasien === id);
  if (!p) return;

  document.getElementById('detailPasienContent').innerHTML = `
    <p><b>Nama:</b> ${p.nama}</p>
    <p><b>WhatsApp:</b> ${p.whatsapp}</p>
    <p><b>Anda Adalah:</b> ${p.andaAdalah}</p>
    <p><b>Tempat, Tgl Lahir:</b> ${p.tempatLahir}, ${p.tanggalLahir}</p>
    <p><b>Pekerjaan:</b> ${p.pekerjaan}</p>
    <p><b>Alamat:</b><br>${p.alamat}</p>
    <p><b>Keluhan:</b><br>${p.keluhan}</p>
  `;

  const modal = document.getElementById('modalPasien');
  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

function closeModalPasien() {
  const modal = document.getElementById('modalPasien');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
}

/* ==============================
   PASIEN ACTION
================================ */

function openEditPasien(id) {
  const p = pasien.find(x => x.id_pasien === id);
  if (!p) return;

  document.getElementById('edit_id_pasien').value = p.id_pasien;
  document.getElementById('edit_anda_adalah').value = p.andaAdalah;

  const modal = document.getElementById('modalEditPasien');
  modal.classList.remove('hidden');
}

function closeEditPasien() {
  document.getElementById('modalEditPasien').classList.add('hidden');
}

function simpanEditPasien() {
  const id = document.getElementById('edit_id_pasien').value;
  const andaAdalah = document.getElementById('edit_anda_adalah').value;

  postAction({
    action: 'update_pasien',
    id_pasien: id,
    anda_adalah: andaAdalah
  }).then(res => {
    if (res.success) {
      alert('Data pasien berhasil diupdate');
      closeEditPasien();
      reloadPasien();
    } else {
      alert(res.message || 'Gagal update pasien');
    }
  });
}

function hapusPasien(id) {
  if (!confirm('Yakin ingin menghapus pasien ini?')) return;

  postAction({
    action: 'delete_pasien_master',
    id_pasien: id
  }).then(res => {
    if (res.success) reloadPasien();
  });
}

function reloadPasien() {
  fetch('./ajax_reservasi.php?action=get_pasien')
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        pasien = res.data;
        renderPasien();
      }
    });
}

</script>
<div id="modalPasien"
 class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-xl w-full max-w-md p-4 sm:p-6 relative modal-content">
    <button onclick="closeModalPasien()"
      class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl">
      <i class="fas fa-times"></i>
    </button>

    <h3 class="text-lg sm:text-xl font-bold mb-4 text-blue-700">
      Detail Pasien
    </h3>

    <div id="detailPasienContent" class="space-y-2 text-sm"></div>
  </div>
</div>
<div id="modalEditPasien"
 class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl w-full max-w-md p-4 sm:p-6 relative modal-content">

    <button onclick="closeEditPasien()"
      class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-xl">
      <i class="fas fa-times"></i>
    </button>

    <h3 class="text-lg sm:text-xl font-bold mb-4 text-blue-700">
      Edit Data Pasien
    </h3>

    <input type="hidden" id="edit_id_pasien">

    <label class="block text-sm font-medium mb-1">Anda Adalah</label>
    <select id="edit_anda_adalah"
      class="w-full border rounded-lg px-3 py-2 mb-4">
      <option value="Pasien Baru">Pasien Baru</option>
      <option value="Pasien Lama">Pasien Lama</option>
    </select>

    <button onclick="simpanEditPasien()"
      class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
      Simpan Perubahan
    </button>

  </div>
</div>
<script>
let lastNotifId = 0;

function updateBadge(count) {
    let badge = document.getElementById("notifBadge");
    if (!badge) return;

    if (count > 0) {
        badge.innerText = count;
        badge.style.display = "inline-block";
    } else {
        badge.style.display = "none";
    }
}

/* =============================
   AMBIL DATA NOTIF TERBARU
============================= */
function checkNotifGlobal() {
    fetch("controllers/admin/ajax/get_notifications.php?latest=1")
    .then(res => res.json())
    .then(data => {

        if (!data || data.length === 0) return;

        let newestId = data[0].id;

        if (lastNotifId !== 0 && newestId > lastNotifId) {

            data.forEach(n => {
                showToast(n.pesan);
            });

            playSound();
        }

        lastNotifId = newestId;

        // update badge
        fetch("controllers/admin/ajax/get_notifications.php?count=1")
        .then(res => res.json())
        .then(res => {
            updateBadge(res.total);
        });

    });
}

/* =============================
   POPUP
============================= */
function showToast(message) {
    let toast = document.createElement("div");
    toast.className = "toast";
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 5000);
}

/* =============================
   SOUND
============================= */
function playSound() {
    let sound = new Audio("https://cdn.pixabay.com/download/audio/2022/03/15/audio_115b9b0f3b.mp3");
    sound.play();
}

/* =============================
   RUN
============================= */
setInterval(checkNotifGlobal, 5000);

function loadBadge() {
    fetch("controllers/admin/ajax/get_notifications.php?count=1")
    .then(res => res.json())
    .then(data => {
        let badge = document.getElementById("notifBadge");
        if (!badge) return;

        if (data.total > 0) {
            badge.innerText = data.total;
            badge.style.display = "inline-block";
        } else {
            badge.style.display = "none";
        }
    });
}

setInterval(loadBadge, 5000);
loadBadge();
</script>
</body> 
</html>