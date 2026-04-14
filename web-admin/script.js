/* ===============================
   STATE
================================ */
// Variabel 'reservations' sudah didefinisikan di dashboard.php
let currentFilter = 'all';

/* ===============================
   INIT
================================ */
document.addEventListener('DOMContentLoaded', init);

function init() {
  render();
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
  tr.innerHTML = `
    <td class="px-4 py-2">${i + 1}</td>
    <td class="px-4 py-2 font-semibold">${r.nama}</td>
    <td class="px-4 py-2 hidden sm:table-cell">${r.tempatLahir}</td>
    <td class="px-4 py-2 hidden sm:table-cell">${r.tanggalLahir}</td>
    <td class="px-4 py-2 hidden sm:table-cell">${r.whatsapp}</td>
    <td class="px-4 py-2 hidden md:table-cell max-w-xs truncate" title="${r.alamat}">${r.alamat}</td>
    <td class="px-4 py-2 hidden md:table-cell max-w-xs truncate" title="${r.keluhan}">${r.keluhan}</td>
    <td class="px-4 py-2 hidden md:table-cell">${r.pekerjaan}</td>
    <td class="px-4 py-2 hidden md:table-cell">${r.jadwalBooking}</td>
    <td class="px-4 py-2 hidden md:table-cell">${r.andaAdalah}</td>
    <td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded-full ${statusBadge(r.status)}">${r.status}</span></td>
    <td class="px-4 py-2 space-x-2">${actionButtons(r)}</td>
  `;
  return tr;
}

function statusBadge(status) {
  if (status === 'menunggu') return 'bg-yellow-100 text-yellow-800';
  if (status === 'dikonfirmasi') return 'bg-green-100 text-green-800';
  return 'bg-red-100 text-red-800';
}

function actionButtons(r) {
  return `
    <button onclick="setStatus(${r.id}, 'dikonfirmasi')"><i class="fas fa-check text-green-600"></i></button>
    <button onclick="setStatus(${r.id}, 'dibatalkan')"><i class="fas fa-times text-red-600"></i></button>
    ${r.status === 'dikonfirmasi' ? `<button onclick="toggleServed(${r.id}, ${r.sudahDilayani ? 0 : 1})"><i class="fas fa-user-check ${r.sudahDilayani ? 'text-green-600' : 'text-gray-400'}"></i></button>` : ''}
    <button onclick="lihatDetail(${r.id})"><i class="fas fa-eye text-blue-600"></i></button>
    <button onclick="deletePasien(${r.id})"><i class="fas fa-trash text-red-700"></i></button>
  `;
}

/* ===============================
   FILTER & SEARCH
================================ */
function filterReservations(filter) {
  // Update visual tab
  document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.classList.remove('bg-white', 'text-blue-700', 'shadow-sm');
    tab.classList.add('text-gray-600');
  });
  const activeTab = document.querySelector(`[data-filter="${filter}"]`);
  if (activeTab) {
    activeTab.classList.add('bg-white', 'text-blue-700', 'shadow-sm');
    activeTab.classList.remove('text-gray-600');
  }

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
    tbody.innerHTML = `<tr><td colspan="3" class="border p-4 text-center text-gray-500">Tidak ada data pasien</td></tr>`;
    return;
  }

  reservations.forEach(r => {
    const status = r.status === 'menunggu' ? 'Menunggu Konfirmasi' : r.status === 'dibatalkan' ? 'Dibatalkan' : r.sudahDilayani ? 'Sudah Dilayani' : 'Belum Dilayani';
    tbody.innerHTML += `<tr><td class="border p-3">${r.nama}</td><td class="border p-3">${status}</td><td class="border p-3 text-center"><i class="fas fa-user text-blue-600"></i></td></tr>`;
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
    });
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

function deletePasien(id) {
  if (!confirm('Yakin ingin menghapus pasien ini?')) return;
  postAction({ action: 'delete_pasien', id })
    .then(r => r.success && reloadFromServer());
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
    <div><strong>Status:</strong> <span class="font-semibold ${data.status === 'menunggu' ? 'text-yellow-600' : data.status === 'dikonfirmasi' ? 'text-green-600' : 'text-red-600'}">${data.status}</span></div>
    <div><strong>Pelayanan:</strong> ${data.sudahDilayani ? '<span class="text-green-600 font-semibold">Sudah Dilayani</span>' : '<span class="text-gray-600 font-semibold">Belum Dilayani</span>'}</div>
  `;

  document.getElementById('detailContent').innerHTML = content;
  const modal = document.getElementById('modalDetail');
  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}