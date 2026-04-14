<?php
session_start();

/* =============================
   KONEKSI DATABASE
============================= */
 $root = dirname(__DIR__, 2); // Menggunakan __DIR__ yang lebih standar
require_once $root . '/config/database.php';

 $db   = new Database();
 $conn = $db->getConnection();

if (!$conn) {
    die("Koneksi database gagal");
}

/* =============================
   PERUBAHAN: LOGIKA UNTUK MENYIMPAN DATA
============================= */
// Inisialisasi variabel untuk pesan
 $success_message = '';
 $error_message = '';

// Cek apakah form telah disubmit (method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil ID admin yang akan diedit dari form (bukan dari URL)
    $admin_id_to_update = $_POST['id_admin'] ?? 0;

    // Ambil data dari form untuk tabel admin
    $nama_admin  = $_POST['nama_admin'] ?? '';
    $username    = $_POST['username'] ?? '';
    $password    = $_POST['password'] ?? ''; // Password bisa kosong jika tidak diubah
    $email       = $_POST['email'] ?? '';
    $no_telp     = $_POST['no_telp'] ?? '';
    $alamat      = $_POST['alamat'] ?? '';

    // Ambil data dari form untuk tabel klinik
    $nama_klinik = $_POST['nama_klinik'] ?? '';
    $email_klinik = $_POST['email_klinik'] ?? '';
    $no_telp_klinik = $_POST['no_telp_klinik'] ?? '';
    $alamat_klinik = $_POST['alamat_klinik'] ?? '';

    // Validasi sederhana (bisa dikembangkan)
    if (empty($nama_admin) || empty($username) || empty($email)) {
        $error_message = "Nama, Username, dan Email Admin wajib diisi.";
    } else {
        try {
            // --- UPDATE DATA ADMIN ---
            // Jika password diisi, hash dan update. Jika tidak, biarkan password lama.
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_admin = "UPDATE admin SET nama_admin = ?, username = ?, password = ?, email = ?, no_telp = ?, alamat = ? WHERE id_admin = ?";
                $stmt_admin = $conn->prepare($sql_admin);
                $stmt_admin->bind_param("ssssssi", $nama_admin, $username, $hashed_password, $email, $no_telp, $alamat, $admin_id_to_update);
            } else {
                $sql_admin = "UPDATE admin SET nama_admin = ?, username = ?, email = ?, no_telp = ?, alamat = ? WHERE id_admin = ?";
                $stmt_admin = $conn->prepare($sql_admin);
                $stmt_admin->bind_param("sssssi", $nama_admin, $username, $email, $no_telp, $alamat, $admin_id_to_update);
            }
            $stmt_admin->execute();

            // --- UPDATE DATA KLINIK ---
            // Asumsikan tabel setting_klinik hanya punya satu baris dengan id=1
            $sql_klinik = "UPDATE setting_klinik SET nama_klinik = ?, email = ?, no_telp = ?, alamat = ? WHERE id = 1";
            $stmt_klinik = $conn->prepare($sql_klinik);
            $stmt_klinik->bind_param("ssss", $nama_klinik, $email_klinik, $no_telp_klinik, $alamat_klinik);
            $stmt_klinik->execute();

            $success_message = "Data berhasil disimpan!";

        } catch (Exception $e) {
            $error_message = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
        }
    }
}


/* =============================
   DATA ADMIN LOGIN (untuk default)
============================= */
 $admin_id = $_SESSION['admin_id'] ?? 1;

/* =============================
   PERUBAHAN: AMBIL ADMIN TERPILIH
============================= */
// Jika ini adalah hasil POST, gunakan ID dari form. Jika bukan, gunakan dari URL.
 $selected_id = $admin_id; // Default
if (isset($_POST['id_admin'])) {
    $selected_id = $_POST['id_admin'];
} elseif (isset($_GET['id_admin'])) {
    $selected_id = $_GET['id_admin'];
}

// Ambil daftar semua admin untuk dropdown
 $admins = $conn->query("SELECT id_admin, nama_admin FROM admin ORDER BY nama_admin ASC");

// Ambil data admin terpilih untuk ditampilkan di form
 $stmt = $conn->prepare("SELECT * FROM admin WHERE id_admin = ?");
 $stmt->bind_param("i", $selected_id);
 $stmt->execute();
 $admin = $stmt->get_result()->fetch_assoc();

/* =============================
   AMBIL DATA KLINIK
============================= */
// Ambil data klinik untuk ditampilkan di form
 $klinik = $conn->query("SELECT * FROM setting_klinik LIMIT 1")->fetch_assoc();

?>

<!-- =============================
   TAMBAHAN: BAGIAN HTML UNTUK MENAMPILKAN FORM
============================= -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Lengkap Klinik</title>
    <!-- Saya asumsikan Anda menggunakan Bootstrap untuk styling 'btn' -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Pengaturan Lengkap Klinik</h1>
    <hr>

    <!-- Tampilkan Pesan Sukses atau Error -->
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Form untuk menyimpan data -->
    <form action="pengaturan.php" method="POST">
        <!-- Hidden input untuk tahu admin mana yang sedang diedit -->
        <input type="hidden" name="id_admin" value="<?= htmlspecialchars($admin['id_admin'] ?? $selected_id) ?>">

        <!-- Dropdown untuk memilih admin -->
        <div class="mb-3">
            <label for="admin_selector" class="form-label">Pilih Admin</label>
            <select name="id_admin" id="admin_selector" class="form-select" onchange="this.form.submit()">
                <?php while ($row = $admins->fetch_assoc()): ?>
                    <option value="<?= $row['id_admin'] ?>" <?= ($row['id_admin'] == ($admin['id_admin'] ?? '')) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['nama_admin']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <small class="form-text text-muted">Pilih admin untuk mengubah data, lalu klik "Simpan".</small>
        </div>

        <h3>Data Admin</h3>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nama_admin" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama_admin" name="nama_admin" value="<?= htmlspecialchars($admin['nama_admin'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
            </div>
             <div class="col-md-6 mb-3">
                <label for="no_telp" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= htmlspecialchars($admin['no_telp'] ?? '') ?>">
            </div>
            <div class="col-md-12 mb-3">
                <label for="alamat_admin" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat_admin" name="alamat" rows="3"><?= htmlspecialchars($admin['alamat'] ?? '') ?></textarea>
            </div>
        </div>

        <h3>Data Klinik</h3>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nama_klinik" class="form-label">Nama Klinik</label>
                <input type="text" class="form-control" id="nama_klinik" name="nama_klinik" value="<?= htmlspecialchars($klinik['nama_klinik'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="email_klinik" class="form-label">Email Klinik</label>
                <input type="email" class="form-control" id="email_klinik" name="email_klinik" value="<?= htmlspecialchars($klinik['email'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="no_telp_klinik" class="form-label">No. Telepon Klinik</label>
                <input type="text" class="form-control" id="no_telp_klinik" name="no_telp_klinik" value="<?= htmlspecialchars($klinik['no_telp'] ?? '') ?>">
            </div>
            <div class="col-md-12 mb-3">
                <label for="alamat_klinik" class="form-label">Alamat Klinik</label>
                <textarea class="form-control" id="alamat_klinik" name="alamat_klinik" rows="3"><?= htmlspecialchars($klinik['alamat'] ?? '') ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

</body>
</html>