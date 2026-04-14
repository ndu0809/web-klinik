<?php
session_start();
// Cek login untuk setiap request AJAX
if (!isset($_SESSION['admin_logged_in'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Sesi berakhir, silakan login ulang.']);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

 $db = new Database();
 $conn = $db->getConnection();

if (!$conn) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit;
}

header('Content-Type: application/json');

 $action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'get_all':
        $sql = "SELECT pel.id_pelayanan, pel.tanggal_pelayanan, pel.jadwal_booking, pel.status, pel.sudah_dilayani, pel.keluhan, pa.nama_pasien, pa.no_whatsapp, pa.anda_adalah, pa.tempat_lahir, pa.tanggal_lahir, pa.alamat, pa.pekerjaan FROM pelayanan pel LEFT JOIN pasien pa ON pel.id_pasien = pa.id_pasien ORDER BY pel.id_pelayanan DESC";
        $result = $conn->query($sql);
        $data = [];
        while ($r = $result->fetch_assoc()) {
            $data[] = [
                'id' => (int)$r['id_pelayanan'], 'nama' => $r['nama_pasien'] ?? 'Unknown', 'whatsapp' => $r['no_whatsapp'] ?? '-', 'andaAdalah' => $r['anda_adalah'] ?? '-', 'tempatLahir' => $r['tempat_lahir'] ?? '-', 'tanggalLahir' => $r['tanggal_lahir'] ?? '-', 'alamat' => $r['alamat'] ?? '-', 'keluhan' => $r['keluhan'] ?? '-', 'pekerjaan' => $r['pekerjaan'] ?? '-', 'jadwalBooking' => $r['jadwal_booking'] ?? '-', 'status' => strtolower($r['status'] ?? 'menunggu'), 'sudahDilayani' => (bool)$r['sudah_dilayani'], 'timestamp' => $r['tanggal_pelayanan'] ?? date('Y-m-d H:i:s')
            ];
        }
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'update_status':
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE pelayanan SET status = ? WHERE id_pelayanan = ?");
        $stmt->bind_param("si", $status, $id);
        echo json_encode(['success' => $stmt->execute()]);
        $stmt->close();
        break;

    case 'toggle_served':
        $id = (int)$_POST['id'];
        $value = (int)$_POST['value'];
        $stmt = $conn->prepare("UPDATE pelayanan SET sudah_dilayani = ? WHERE id_pelayanan = ?");
        $stmt->bind_param("ii", $value, $id);
        echo json_encode(['success' => $stmt->execute()]);
        $stmt->close();
        break;

    case 'delete_pasien':
        $id = (int)$_POST['id'];
        // Hapus dari tabel pelayanan (atau pasien, tergantung logika bisnis Anda)
        // Asumsi kita menghapus record pelayanan terkait
        $stmt = $conn->prepare("DELETE FROM pelayanan WHERE id_pelayanan = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => $success]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
        break;
}

 $conn->close();