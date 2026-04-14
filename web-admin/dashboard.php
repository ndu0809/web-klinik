<?php
session_start();

/* =============================
   CEK LOGIN
============================= */
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../auth/login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config/database.php';

/* =============================
   KONEKSI DATABASE
============================= */
 $db   = new Database();
 $conn = $db->getConnection();

if (!$conn) {
    die('Koneksi database gagal');
}

/* =============================
   QUERY DATA RESERVASI (FINAL)
============================= */
 $sql = "
SELECT
    pel.id_pelayanan,
    pel.tanggal_pelayanan,
    pel.jadwal_booking,
    pel.status,
    pel.sudah_dilayani,
    pel.keluhan,

    pa.nama_pasien,
    pa.no_whatsapp,
    pa.anda_adalah,
    pa.tempat_lahir,
    pa.tanggal_lahir,
    pa.alamat,
    pa.pekerjaan

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
    'id'            => (int)$r['id_pelayanan'],
    'nama'          => $r['nama_pasien'] ?? 'Unknown',
    'whatsapp'      => $r['no_whatsapp'] ?? '-',
    'andaAdalah'    => $r['anda_adalah'] ?? '-',
    'tempatLahir'   => $r['tempat_lahir'] ?? '-',
    'tanggalLahir'  => $r['tanggal_lahir'] ?? '-',
    'alamat'        => $r['alamat'] ?? '-',
    'keluhan'       => $r['keluhan'] ?? '-',
    'pekerjaan'     => $r['pekerjaan'] ?? '-',
    'jadwalBooking' => $r['jadwal_booking'] ?? '-',
    'status'        => strtolower($r['status'] ?? 'menunggu'),
    'sudahDilayani' => (bool)$r['sudah_dilayani'],
    'timestamp'     => $r['tanggal_pelayanan'] ?? date('Y-m-d H:i:s'),
  ];  
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - drg Pasri</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body class="font-sans text-gray-900 bg-gray-100 min-h-screen">

<!-- MODAL DETAIL PASIEN -->
<div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
    <button onclick="closeDetailModal()" class="absolute top-2 right-3 text-gray-500 hover:text-red-600">
      <i class="fas fa-times"></i>
    </button>
    <h2 class="text-lg font-bold mb-4 text-gray-800">Detail Pasien</h2>
    <div id="detailContent" class="space-y-2 text-sm text-gray-700"></div>
  </div>
</div>

<!-- NAVBAR ADMIN -->
<nav class="sticky top-0 z-50 bg-gradient-to-r from-blue-600 via-blue-400 to-white shadow-md">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">
      <!-- KIRI -->
      <div class="flex items-center gap-4 text-white">
        <button onclick="toggleMobileMenu()" class="md:hidden text-2xl"><i class="fas fa-bars"></i></button>
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow"><i class="fas fa-tooth text-lg"></i></div>
          <div class="leading-tight"><p class="font-bold text-lg text-white">drg Pasri</p><p class="text-xs text-blue-100 hidden sm:block">Admin Klinik</p></div>
        </div>
        <div class="hidden md:flex items-center gap-6 ml-10 font-semibold text-blue-900">
          <a href="#dashboard" class="flex items-center gap-2 hover:text-blue-700 transition"><i class="fas fa-gauge"></i>Dashboard</a>
          <a href="#laporan" class="flex items-center gap-2 hover:text-blue-700 transition"><i class="fas fa-chart-pie"></i>Laporan Persentase</a>
        </div>
      </div>
      <!-- KANAN -->
      <div class="flex items-center gap-5 text-blue-900">
        <button onclick="location.reload()" title="Refresh" class="hover:text-blue-700 transition"><i class="fas fa-rotate-right"></i></button>
        <div class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-full shadow"><img src="../../assets/img/admin.jpg" class="w-9 h-9 rounded-full object-cover"><span class="hidden sm:block font-semibold">Admin</span></div>
        <a href="../../auth/logout.php" class="text-red-500 hover:text-red-600 transition"><i class="fas fa-sign-out-alt"></i></a>
      </div>
    </div>
  </div>
  <!-- MENU MOBILE -->
  <div id="mobileMenu" class="hidden md:hidden bg-white px-6 py-4 border-t space-y-4 font-semibold text-blue-900">
    <a href="#dashboard" class="flex items-center gap-2 hover:text-blue-700"><i class="fas fa-gauge"></i>Dashboard</a>
    <a href="#laporan" class="flex items-center gap-2 hover:text-blue-700"><i class="fas fa-chart-pie"></i>Laporan Persentase</a>
  </div>
</nav>

<section id="dashboard">
  <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-lg shadow p-4"><div class="flex items-center justify-between"><div><p class="text-gray-500 text-xs">Total</p><p id="statTotal" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-users text-blue-500 text-2xl"></i></div></div>
      <div class="bg-white rounded-lg shadow p-4"><div class="flex items-center justify-between"><div><p class="text-gray-500 text-xs">Menunggu</p><p id="statMenunggu" class="text-2xl font-bold text-yellow-600">0</p></div><i class="fas fa-clock text-yellow-500 text-2xl"></i></div></div>
      <div class="bg-white rounded-lg shadow p-4"><div class="flex items-center justify-between"><div><p class="text-gray-500 text-xs">Dikonfirmasi</p><p id="statDikonfirmasi" class="text-2xl font-bold text-green-600">0</p></div><i class="fas fa-check-circle text-green-500 text-2xl"></i></div></div>
      <div class="bg-white rounded-lg shadow p-4"><div class="flex items-center justify-between"><div><p class="text-gray-500 text-xs">Dibatalkan</p><p id="statDibatalkan" class="text-2xl font-bold text-red-600">0</p></div><i class="fas fa-times-circle text-red-500 text-2xl"></i></div></div>
    </div>
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md">
      <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-800"><i class="fas fa-calendar-check mr-2 text-blue-600"></i>Data Reservasi Pasien</h1>
          <div class="flex items-center space-x-2"><input id="searchInput" type="text" placeholder="Cari nama pasien..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><button onclick="searchReservations()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm"><i class="fas fa-search"></i></button></div>
        </div>
        <!-- FILTER TABS -->
        <div class="flex flex-wrap gap-2 mt-4 bg-gray-100 p-1 rounded-lg">
          <button class="filter-tab flex-1 min-w-[80px] px-3 py-2 rounded-md text-sm font-medium bg-white text-blue-700 shadow-sm" data-filter="all" onclick="filterReservations('all')"><i class="fas fa-list mr-1"></i>Semua</button>
          <button class="filter-tab flex-1 min-w-[80px] px-3 py-2 rounded-md text-sm font-medium text-gray-600" data-filter="menunggu" onclick="filterReservations('menunggu')"><i class="fas fa-hourglass-half mr-1"></i>Menunggu</button>
          <button class="filter-tab flex-1 min-w-[80px] px-3 py-2 rounded-md text-sm font-medium text-gray-600" data-filter="dikonfirmasi" onclick="filterReservations('dikonfirmasi')"><i class="fas fa-check mr-1"></i>Dikonfirmasi</button>
          <button class="filter-tab flex-1 min-w-[80px] px-3 py-2 rounded-md text-sm font-medium text-gray-600" data-filter="dibatalkan" onclick="filterReservations('dibatalkan')"><i class="fas fa-times mr-1"></i>Dibatalkan</button>
          <button class="filter-tab flex-1 min-w-[80px] px-3 py-2 rounded-md text-sm font-medium text-gray-600" data-filter="sudah" onclick="filterReservations('sudah')"><i class="fas fa-user-check mr-1"></i>Sudah Dilayani</button>
          <button class="filter-tab flex-1 min-w-[80px] px-3 py-2 rounded-md text-sm font-medium text-gray-600" data-filter="belum" onclick="filterReservations('belum')"><i class="fas fa-user-clock mr-1"></i>Belum Dilayani</button>
        </div>
      </div>
      <!-- Table -->
      <div class="overflow-x-auto custom-scrollbar">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th><th class="px-4 py-3 hidden sm:table-cell text-xs font-medium text-gray-500 uppercase">Tempat Lahir</th><th class="px-4 py-3 hidden sm:table-cell text-xs font-medium text-gray-500 uppercase">Tanggal Lahir</th><th class="px-4 py-3 hidden sm:table-cell text-xs font-medium text-gray-500 uppercase">WhatsApp</th><th class="px-4 py-3 hidden md:table-cell text-xs font-medium text-gray-500 uppercase">Alamat</th><th class="px-4 py-3 hidden md:table-cell text-xs font-medium text-gray-500 uppercase">Keluhan</th><th class="px-4 py-3 hidden md:table-cell text-xs font-medium text-gray-500 uppercase">Pekerjaan</th><th class="px-4 py-3 hidden md:table-cell text-xs font-medium text-gray-500 uppercase">Jadwal</th><th class="px-4 py-3 hidden md:table-cell text-xs font-medium text-gray-500 uppercase">Anda Adalah</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead>
          <tbody id="reservationTableBody" class="bg-white divide-y divide-gray-200"></tbody>
        </table>
        <div id="noReservationsMessage" class="hidden text-center py-12 text-gray-500"><i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i><p class="text-lg">Tidak ada data reservasi</p><p class="text-sm mt-2">Belum ada reservasi yang masuk</p></div>
      </div>
    </div>
  </main>
</section>

<!-- Persentase Data Klinik -->
<section id="clinic-stats" class="mt-10 p-6 bg-white rounded-xl shadow">
  <h2 class="text-xl font-bold mb-4">Persentase Data Klinik</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="p-4 bg-blue-100 rounded-lg"><p class="text-lg font-semibold">Total Pasien</p><span id="totalPatients">0%</span></div>
    <div class="p-4 bg-green-100 rounded-lg"><p class="text-lg font-semibold">Persentase Terlayani</p><span id="percentageServed">0%</span></div>
    <div class="p-4 bg-red-100 rounded-lg"><p class="text-lg font-semibold">Persentase Dibatalkan</p><span id="percentageCancelled">0%</span></div>
  </div>
</section>

<section id="laporan">
  <!-- Page percentage -->
  <section id="page-percentage" class="mt-20 p-10 bg-gradient-to-br from-gray-50 to-gray-200 rounded-2xl shadow-2xl border border-gray-300">
    <h1 class="text-3xl font-extrabold mb-10 text-gray-800 tracking-wide text-center">📊 Laporan Persentase Klinik</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
      <div class="p-6 bg-white rounded-2xl shadow-md border hover:shadow-xl transition"><p class="text-sm font-semibold text-gray-500">Total Pasien</p><span id="p_total" class="text-3xl font-bold text-blue-700">0</span></div>
      <div class="p-6 bg-white rounded-2xl shadow-md border hover:shadow-xl transition"><p class="text-sm font-semibold text-gray-500">Pasien Terlayani</p><span id="p_served" class="text-3xl font-bold text-green-700">0</span></div>
      <div class="p-6 bg-white rounded-2xl shadow-md border hover:shadow-xl transition"><p class="text-sm font-semibold text-gray-500">Belum Dilayani</p><span id="p_pending" class="text-3xl font-bold text-yellow-700">0</span></div>
      <div class="p-6 bg-white rounded-2xl shadow-md border hover:shadow-xl transition"><p class="text-sm font-semibold text-gray-500">Pasien Dibatalkan</p><span id="p_cancelled" class="text-3xl font-bold text-red-700">0</span></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
      <div class="p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow border"><p class="text-sm font-semibold text-gray-700">Persentase Terlayani</p><span id="p_percent_served" class="text-2xl font-bold text-green-800">0%</span></div>
      <div class="p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow border"><p class="text-sm font-semibold text-gray-700">Persentase Belum Dilayani</p><span id="p_percent_pending" class="text-2xl font-bold text-yellow-800">0%</span></div>
      <div class="p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow border"><p class="text-sm font-semibold text-gray-700">Persentase Dibatalkan</p><span id="p_percent_cancelled" class="text-2xl font-bold text-red-800">0%</span></div>
    </div>
    <div class="mt-12 bg-white rounded-2xl shadow-xl border p-8"><h2 class="text-2xl font-bold mb-6 text-gray-800">📄 Detail status Pasien</h2><table class="w-full border-collapse"><thead><tr class="bg-gray-100 text-left text-gray-700"><th class="border p-3">Nama</th><th class="border p-3">Status</th><th class="border p-3">Aksi</th></tr></thead><tbody id="patientDetailTable" class="text-gray-800"></tbody></table></div>
  </section>
</section>

<!-- Script untuk mem-pass data dari PHP ke JS -->
<script>
  const reservations = <?php echo json_encode($reservations, JSON_UNESCAPED_UNICODE); ?>;
</script>

<!-- Memanggil file JavaScript eksternal -->
<script src="script.js" defer></script>

</body> 
</html>