<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifikasi - drg Pasri</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    min-height: 100vh;
}

/* Container */
.container {
    width: 92%;
    max-width: 900px;
    margin: 40px auto;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.header h2 {
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header h2 i {
    color: #4e73df;
}

/* Badge total */
.badge {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: bold;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

/* Back button */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: white;
    color: #4e73df;
    border: 2px solid #4e73df;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.back-btn:hover {
    background: #4e73df;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.back-btn i {
    font-size: 16px;
}

/* Card */
.card {
    background: white;
    border-radius: 15px;
    padding: 10px 0;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* Item notifikasi */
.notif {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    transition: 0.3s;
    border-bottom: 1px solid #f1f1f1;
}

.notif:hover {
    background: #f9fbff;
    transform: scale(1.01);
}

/* Icon */
.icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Warna jenis notif */
.success { background: linear-gradient(135deg, #1cc88a, #17a673); }
.warning { background: linear-gradient(135deg, #f6c23e, #dda20a); color: black; }
.danger  { background: linear-gradient(135deg, #e74a3b, #be2617); }
.info    { background: linear-gradient(135deg, #36b9cc, #258391); }

/* Text */
.text {
    flex: 1;
}

.text b {
    color: #2c3e50;
    font-size: 15px;
}

/* Message */
.text div {
    margin-top: 3px;
    color: #555;
}

/* Time */
.time {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

/* Empty state */
.empty {
    text-align: center;
    padding: 40px;
    color: #888;
}

/* Responsive */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    
    .container {
        width: 95%;
        margin: 20px auto;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2><i class="fas fa-bell"></i> Notifikasi Sistem</h2>
        <div class="header-actions">
            <span id="totalNotif" class="badge">0</span>
            <a href="index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="card" id="notifContainer">
        <!-- isi notifikasi -->
    </div>

</div>

<script>
function loadNotif() {
    fetch('ajax_notifications.php')
    .then(res => res.json())
    .then(data => {
        let html = '';
        let total = data.length;

        if (total === 0) {
            html = '<div class="empty">📭 Tidak ada notifikasi</div>';
        }

        data.forEach(n => {
            html += `
            <div class="notif">
                <div class="icon ${n.type}">
                    ${getIcon(n.type)}
                </div>
                <div class="text">
                    <b>${n.title}</b><br>
                    ${n.message}
                    <div class="time">${n.time}</div>
                </div>
            </div>
            `;
        });

        document.getElementById('notifContainer').innerHTML = html;
        document.getElementById('totalNotif').innerText = total;
    })
    .catch(error => {
        console.error('Error loading notifications:', error);
        document.getElementById('notifContainer').innerHTML = 
            '<div class="empty">❌ Terjadi kesalahan saat memuat notifikasi</div>';
    });
}

function getIcon(type) {
    switch(type) {
        case 'success': return '✔';
        case 'warning': return '⚠';
        case 'danger': return '✖';
        case 'info': return 'ℹ';
        default: return '•';
    }
}

// Auto refresh tiap 5 detik
setInterval(loadNotif, 5000);
loadNotif();
</script>

</body>
</html>