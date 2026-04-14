<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

 $db   = new Database();
 $conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Logika POST Anda di sini jika ada
}

?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>drg Pasri - Reservasi Layanan Kesehatan Gigi Terpercaya</title>
  <meta name="description" content="Layanan kesehatan gigi terbaik oleh drg Pasri. Reservasi online mudah dan cepat. Pelayanan profesional dengan peralatan modern.">
  <link rel="icon" href="favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root { --primary: #698ae8; --primary-dark: #353ba7; --primary-darker: #2d4286; --light: #e1e7f6; --lighter: #f8f9fe; }
    .gradient-body { background: linear-gradient(to bottom right, var(--light), var(--lighter), var(--primary)); }
    .btn-primary { background: linear-gradient(to right, var(--primary), var(--primary-dark)); transition: all 0.3s; }
    .btn-primary:hover { background: linear-gradient(to right, var(--primary-dark), var(--primary-darker)); transform: scale(1.05); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .btn-map { background: linear-gradient(to right, #4CAF50, #45a049); transition: all 0.3s; }
    .btn-map:hover { background: linear-gradient(to right, #45a049, #3d8b40); transform: scale(1.05); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .form-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; transition: all 0.2s; }
    .form-input:focus { outline: none; border-color: transparent; box-shadow: 0 0 0 2px var(--primary-dark); }
    .feature-card { transition: all 0.3s; }
    .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .modal-backdrop { background-color: rgba(0,0,0,0.5); }
    .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s ease-in-out infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .skip-link { position: absolute; top: -40px; left: 0; background: var(--primary-dark); color: white; padding: 8px; z-index: 100; transition: top 0.3s; }
    .skip-link:focus { top: 0; }
  </style>
</head>
<body class="font-sans text-gray-900 gradient-body">
  <a href="#main-content" class="skip-link">Skip to main content</a>
  <nav class="sticky top-0 z-50 bg-gradient-to-r from-white/20 to-white/10 backdrop-blur-lg border border-white/20 shadow-lg" data-aos="fade-down">
    <header class="flex justify-between items-center px-6 md:px-20 py-6">
      <div class="flex items-center text-[#353ba7] font-bold text-xl">
        <i class="fas fa-star mr-2" aria-hidden="true"></i>
        <span>drg Pasri</span>
      </div>
      <ul class="hidden md:flex space-x-10 ml-20 font-medium">
        <li><a href="#Home" class="hover:text-[#353ba7]"><i class="fas fa-home mr-2"></i>Home</a></li>
        <li><a href="#Clinic" class="hover:text-[#353ba7]"><i class="fas fa-clinic-medical mr-2"></i>Klinik</a></li>
        <li><a href="#About" class="hover:text-[#353ba7]"><i class="fas fa-info-circle mr-2"></i>About</a></li>
        <li><a href="#Features" class="hover:text-[#353ba7]"><i class="fas fa-cogs mr-2"></i>Service</a></li>
        <li><a href="#Contact" class="hover:text-[#353ba7]"><i class="fas fa-envelope mr-2"></i>Contact</a></li>
      </ul>
      <button class="md:hidden text-2xl" id="menu-btn" aria-label="Toggle mobile menu">
        <i class="fas fa-bars"></i>
      </button>
    </header>
    <ul id="mobile-menu" class="hidden flex-col space-y-4 px-6 pb-6 font-medium bg-white/80 backdrop-blur-md border-t">
      <li><a href="#Home" class="hover:text-[#353ba7]"><i class="fas fa-home mr-2"></i>Home</a></li>
      <li><a href="#Clinic" class="hover:text-[#353ba7]"><i class="fas fa-clinic-medical mr-2"></i>Clinic</a></li>
      <li><a href="#About" class="hover:text-[#353ba7]"><i class="fas fa-info-circle mr-2"></i>About</a></li>
      <li><a href="#Features" class="hover:text-[#353ba7]"><i class="fas fa-cogs mr-2"></i>Service</a></li>
      <li><a href="#Contact" class="hover:text-[#353ba7]"><i class="fas fa-envelope mr-2"></i>Contact</a></li>
    </ul>
  </nav>

  <section id="Home" class="flex flex-col md:flex-row justify-between items-center px-6 md:px-20 py-16 space-y-10 md:space-y-0" id="main-content">
    <div class="max-w-xl text-center md:text-left" data-aos="fade-right">
      <div class="flex items-center justify-center md:justify-start mb-5">
        <div class="bg-[#e1e7f6] border border-[#698ae8]/40 px-4 py-1 rounded-full text-[#353ba7] font-semibold text-sm">
          <i class="fas fa-bolt mr-2"></i>Now Available
        </div>
      </div>
      <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-6">
        <span class="bg-gradient-to-r from-[#698ae8] via-[#353ba7] to-[#2d4286] bg-clip-text text-transparent">Senyum Bebas Bermasalah? drg Pasri Ahlinya!</span><br>
        Produk & Layanan Terpercaya untuk Anda💡
      </h1>
      <p class="text-base md:text-lg text-gray-600 mb-8">
        Senyum dari gigi sehat anda adalah prioritas kami.<br>
        Jadwal Klinik: Senin-Sabtu (16.30 WIB)
      </p>
      <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0 mb-6">
        <a href="#Contact" class="btn-primary px-6 py-3 rounded-lg text-white font-semibold text-center">
          <i class="fas fa-calendar-check mr-2"></i>Lakukan Reservasi
        </a>
      </div>
      <div class="flex items-center justify-center md:justify-start text-gray-600 text-sm">
        <div class="text-yellow-500">
          <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
        </div>
        <span class="ml-2">4.9/5 dari 2,341 reviews | Dipercaya oleh 500 orang</span>
      </div>
    </div>
    <div class="relative" data-aos="fade-left">
      <div class="transition-all duration-300 hover:scale-105 hover:shadow-2xl relative">
      <img src="<?= BASE_URL ?>user/1st.jpg" alt="Dokter gigi drg Pasri sedang melakukan pemeriksaan gigi pasien" class="w-full max-w-md rounded-xl shadow-lg" loading="lazy">
      </div>
    </div>
  </section>

  <section id="Clinic" class="px-6 md:px-20 py-20 bg-white" data-aos="fade-up">
    <div class="max-w-6xl mx-auto text-center mb-12">
      <div class="inline-block bg-[#e1e7f6] text-[#353ba7] border border-[#698ae8]/40 px-5 py-1 rounded-full font-semibold text-sm mb-6">
        <i class="fas fa-clinic-medical mr-2"></i>Profil Klinik
      </div>
      <h2 class="text-3xl md:text-4xl font-bold mb-7">
        Kenali <span class="bg-gradient-to-r from-[#698ae8] via-[#353ba7] to-[#2d4286] bg-clip-text text-transparent">Klinik Kami</span>
      </h2>
      <p class="text-gray-600 max-w-3xl mx-auto">
        Klinik Drg Pasri ini bertujuan untuk memberikan perawatan kesehatan gigi, seperti pencegahan karies, pengobatan gusi, dan edukasi oral hygiene, serta pelayanan ramah dan nyaman untuk pasien. 
        <br><br>Klinik ini berlokasikan di Jl. Pacuan, Kubu Gadang (setelah pasar ibu), Kec. Payakumbuh Utara, Kota Payakumbuh, Sumatera Barat yang mulai beroperasi sejak 2016, dengan jadwal buka pada hari senin-sabtu pukul 16.30.
      </p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div class="relative flex justify-center" data-aos="fade-left">
      <img src="<?= BASE_URL ?>user/2nd.jpg" alt="Interior klinik gigi drg Pasri yang modern dan bersih" class="w-full max-w-xs rounded-xl shadow-lg" loading="lazy">
        <div class="absolute -bottom-6 -right-6 bg-white shadow-lg rounded-xl px-4 py-2 text-sm font-semibold text-[#353ba7]">
          <i class="fas fa-award mr-2"></i>Tersertifikasi Kementerian Kesehatan
        </div>
      </div>
      <div data-aos="fade-right">
        <h3 class="text-2xl font-bold mb-6 text-[#353ba7]">Klinik Gigi drg Pasri</h3>
        <div class="space-y-4 mb-8">
          <div class="flex items-start">
            <i class="fas fa-map-marker-alt text-blue-500 text-xl mr-4 mt-1"></i>
            <div>
              <h4 class="font-semibold mb-1">Lokasi Strategis</h4>
              <p class="text-gray-600">Jl. Kesehatan No. 123, Jakarta Selatan. Mudah diakses dengan transportasi umum dan tersedia parkir luas.</p>
            </div>
          </div>
          <div class="flex items-start">
            <i class="fas fa-clock text-blue-500 text-xl mr-4 mt-1"></i>
            <div>
              <h4 class="font-semibold mb-1">Jam Operasional</h4>
              <p class="text-gray-600">Senin - Sabtu: 09.00 - 20.00<br>NOTE:jika melakukan pendaftaran pelayanan, harap datang 15 menit lebih awal sebelum dilayani</p>
            </div>
          </div>
          <div class="flex items-start">
            <i class="fas fa-tools text-blue-500 text-xl mr-4 mt-1"></i>
            <div>
              <h4 class="font-semibold mb-1">Peralatan Modern</h4>
              <p class="text-gray-600">Menggunakan peralatan gigi terkini dengan teknologi digital untuk hasil yang akurat dan nyaman.</p>
            </div>
          </div>
          <div class="flex items-start">
            <i class="fas fa-shield-alt text-blue-500 text-xl mr-4 mt-1"></i>
            <div>
              <h4 class="font-semibold mb-1">Standar Keamanan Tinggi</h4>
              <p class="text-gray-600">Protokol sterilisasi ketat sesuai standar internasional untuk keamanan pasien.</p>
            </div>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="https://www.google.com/maps/place/Praktek+drg.+Pasri+2/@-0.229835,100.6309176,15z/data=!4m10!1m2!2m1!1sklinik+drg+pasri!3m6!1s0x2e2ab53b83182793:0xc9caef2ea03ba540!8m2!3d-0.2261066!4d100.6420717!15sChBrbGluaWsgZHJnIHBhc2lykgEHZGVudGlzdKoBWQoIL20vMDk5ZnoQASoUIhBrbGluaWsgZHJnIHBhc2lyKCYyHxABIhtQbmdhYYC0mW9ICXX3Yz-zywwMkGojzJQJoHAyFBACIhBrbGluaWsgZHJnIHBhc2ly4AEA!16s%2Fg%2F11sddtxf5w?entry=ttu&g_ep=EgoyMDI1MTEwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="btn-map px-6 py-3 rounded-lg text-white font-semibold text-center">
            <i class="fas fa-map-marked-alt mr-2"></i>Lihat di Peta
          </a>
          <a href="tel:6283197242550" class="btn-primary px-6 py-3 rounded-lg text-white font-semibold text-center">
            <i class="fas fa-phone-alt mr-2"></i>Hubungi Kami
          </a>
        </div>
      </div>
    </div>
  </section>

  <section id="About" class="px-6 md:px-20 py-20 bg-gray-50" data-aos="fade-up">
    <div class="max-w-6xl mx-auto text-center mb-12">
      <div class="inline-block bg-[#e1e7f6] text-[#353ba7] border border-[#698ae8]/40 px-5 py-1 rounded-full font-semibold text-sm mb-6">
        <i class="fas fa-info-circle mr-2"></i>About Us
      </div>
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        Siapa <span class="bg-gradient-to-r from-[#698ae8] via-[#353ba7] to-[#2d4286] bg-clip-text text-transparent">drg Pasri?</span>
      </h2>
    </div>
    <div class="flex flex-col lg:flex-row gap-12 items-center">
      <div class="w-full lg:w-1/2" data-aos="fade-right">
        <div class="mb-8">
          <h3 class="text-xl font-bold mb-4 text-[#353ba7]">Riwayat Pendidikan & Karir</h3>
          <p class="text-gray-600 mb-3">
            Pada tahun 1992, drg Pasri telah menyelesaikan pendidikan Sekolah Pengatur Rawat Gigi (SPRG) di Depkes, Bukittinggi. Lalu beliau bekerja di Puskesmas Balai Jariang pada tahun 1993-1998.
          </p>
          <p class="text-gray-600 mb-3">
            drg Pasri pun mengambil pendidikan Diploma III (D3) di bidang Teknik Perawatan Gigi di Jakarta pada tahun 1998-2001, lalu melakukan dinas lagi di Puskesmas Balai Jariang pada tahun 2001-2006. "Pasri, Am.Tg".
          </p>
          <p class="text-gray-600">
            Pada tahun 2006-2013 ia melanjutkan kuliah di Universitas Baiturrahmah dengan mengambil jurusan kedokteran gigi dan mendapatkan gelar drg Pasri. Setelah menyelesaikan kuliahnya, drg Pasri pun bekerja di Rumah Sakit Umum Daerah Adnaan WD sejak tahun 2016 hingga sekarang, dan membuka klinik gigi.
          </p>
        </div>
        <div class="space-y-6 mb-8">
          <div class="flex items-start">
            <i class="fas fa-tooth text-blue-500 text-2xl mr-4 mt-1"></i>
            <div>
              <h3 class="text-xl font-semibold mb-2">Pelayanan Gigi Terbaik</h3>
              <p class="text-gray-600">drg Pasri hadir untuk membantu Anda mendapatkan pelayanan kesehatan gigi terbaik.</p>
            </div>
          </div>
          <div class="flex items-start">
            <i class="fas fa-user-md text-blue-500 text-2xl mr-4 mt-1"></i>
            <div>
              <h3 class="text-xl font-semibold mb-2">Tim Profesional</h3>
              <p class="text-gray-600">Dokter gigi berpengalaman dan perawat profesional siap memberikan pelayanan terbaik untuk Anda dan keluarga.</p>
            </div>
          </div>
          <div class="flex items-start">
            <i class="fas fa-heart text-blue-500 text-2xl mr-4 mt-1"></i>
            <div>
              <h3 class="text-xl font-semibold mb-2">Senyum Sehat</h3>
              <p class="text-gray-600">Kami percaya senyum yang sehat adalah kunci kesehatan tubuh secara keseluruhan.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full lg:w-1/2 flex flex-col items-center gap-6" data-aos="fade-left">
        <div class="transition-all duration-300 hover:scale-105 hover:shadow-2xl relative">
        <img src="<?= BASE_URL ?>user/3rd.jpg" alt="drg Pasri sedang memeriksa pasien di klinik" class="w-full max-w-xs rounded-xl shadow-lg" loading="lazy">
          <div class="absolute -bottom-4 -right-4 bg-white shadow-lg rounded-xl px-4 py-2 text-sm font-semibold text-[#353ba7]">
            <i class="fas fa-user-md mr-2"></i>drg Pasri
          </div>
        </div>
        <div class="transition-all duration-300 hover:scale-105 hover:shadow-2xl relative">
        <img src="<?= BASE_URL ?>user/4th.jpg" alt="drg Pasri bersama tim medis di klinik" class="w-full max-w-xs rounded-xl shadow-lg" loading="lazy">
          <div class="absolute -bottom-4 -right-4 bg-white shadow-lg rounded-xl px-4 py-2 text-sm font-semibold text-[#353ba7]">
            <i class="fas fa-user-md mr-2"></i>drg Pasri
          </div>
        </div>
      </div>
    </div>
    <div class="text-center mt-8">
      <a href="#" class="inline-block btn-primary px-6 py-3 rounded-lg text-white font-semibold">
        <i class="fas fa-arrow-right mr-2"></i>Pelajari Lebih Lanjut
      </a>
    </div>
  </section>

  <section id="Features" class="px-6 md:px-20 py-16 text-center">
    <div class="inline-block bg-[#e1e7f6] text-[#353ba7] border border-[#698ae8]/40 px-5 py-1 rounded-full font-semibold text-sm mb-6">
      <i class="fas fa-cogs mr-2"></i>Services
    </div>
    <h2 class="text-3xl md:text-4xl font-bold mb-4">
      Everything you <span class="bg-gradient-to-r from-[#698ae8] to-[#2d4286] bg-clip-text text-transparent">need to succeed</span>
    </h2>
    <p class="text-gray-600 max-w-2xl mx-auto mb-12">Kami sedia melayani anda dengan pelayanan terbaik</p>
  </section>

  <section class="px-6 md:px-20 pb-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="zoom-in">
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/extraction.png" alt="Prosedur pencabutan gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pencabutan Gigi / Extraction</h3>
        <p class="text-gray-600 text-sm">Membantu mencabut gigi yang bermasalah.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/toothfilling.png" alt="Prosedur penambalan gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Penambalan Gigi / Filling</h3>
        <p class="text-gray-600 text-sm">Membantu penambalan gigi yang berlubang.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/scalling.png" alt="Prosedur pembersihan karang gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pembersihan Karang Gigi / Scalling</h3>
        <p class="text-gray-600 text-sm">Membantu pembersihan karang gigi.</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12" data-aos="zoom-in">
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/rootcanal.png" alt="Prosedur perawatan saluran akar gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Perawatan Saluran Akar Gigi / Root Canal</h3>
        <p class="text-gray-600 text-sm">Membersihkan dan menyegel bagian dalam gigi yang terinfeksi atau rusak.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/cjacket.png" alt="Prosedur pembuatan porcelain crowns" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pembuatan Porcelain Crowns Jacket</h3>
        <p class="text-gray-600 text-sm">Pemasangan mahkota porselen untuk tutup gigi yang rusak.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/fissea.png" alt="Prosedur pencegahan gigi berlubang" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pencegahan Gigi Berlubang / Fissure Sealent</h3>
        <p class="text-gray-600 text-sm">Membantu mencegah gigi berlubang.</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12" data-aos="zoom-in">
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/periodontal.jpg" alt="Prosedur perawatan jaringan periodontal" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Perawatan Jaringan Periodontal</h3>
        <p class="text-gray-600 text-sm">Menghentikan periodontitis, menghilangkan infeksi, mengurangi peradangan, memulihkan dan menjaga jaringan pendukung gigi.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/fake.png" alt="Prosedur tindakan rehabilitasi gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Tindakan Rehabilitasi Gigi / Pemasangan Prothesa / Gigi Palsu</h3>
        <p class="text-gray-600 text-sm">Mengembalikan kesehatan, fungsi dan estetika mulut / gigi yang hilang / rusak.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>user/ortho.png" alt="Prosedur perawatan pencegahan gigi crowded" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Perawatan Pencegahan Gigi Crowded Pada Anak / Pemasangan Ortho Lepasan</h3>
        <p class="text-gray-600 text-sm">Mengoreksi posisi gigi dan rahang yang tidak sejajar dengan alat ortodontik (kawat gigi / aligner bening).</p>
      </div>
    </div>
  </section>

  <section id="Contact" class="px-6 md:px-20 py-20 bg-gray-50" data-aos="fade-up">
    <div class="max-w-4xl mx-auto text-center mb-12">
      <div class="inline-block bg-[#e1e7f6] text-[#353ba7] border border-[#698ae8]/40 px-5 py-1 rounded-full font-semibold text-sm mb-6">
        <i class="fas fa-phone-alt mr-2"></i>Lakukan Reservasi Sekarang!
      </div>
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        Hubungi Kami untuk <span class="bg-gradient-to-r from-[#698ae8] via-[#353ba7] to-[#2d4286] bg-clip-text text-transparent">Reservasi</span>
      </h2>
      <p class="text-gray-600 max-w-2xl mx-auto">
        Isi formulir di bawah ini untuk melakukan reservasi. Data akan dikirim langsung ke WhatsApp kami untuk diproses lebih lanjut.
      </p>
    </div>
    <!-- PERUBAHAN 1: Tambahkan autocomplete="off" di form -->
    <form id="reservationForm" class="bg-white rounded-2xl shadow-xl p-8 md:p-12 space-y-6 max-w-4xl mx-auto" autocomplete="off">
  
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Nama -->
        <div>
          <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-user mr-2"></i>Nama <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <input type="text" id="nama" name="field_nama" required class="form-input" placeholder="Masukkan Nama Anda" autocomplete="off" readonly>
        </div>

        <!-- WhatsApp -->
        <div>
          <label for="whatsapp" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fab fa-whatsapp mr-2"></i>No WhatsApp <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <input type="tel" id="whatsapp" name="field_whatsapp" required class="form-input" placeholder="Contoh: 081234567890" autocomplete="off" readonly>
          <span id="whatsapp-error" class="text-red-500 text-sm hidden">Format nomor WhatsApp tidak valid</span>
        </div>

        <!-- Pekerjaan -->
        <div>
          <label for="pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-briefcase mr-2"></i>Pekerjaan <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <input type="text" id="pekerjaan" name="field_pekerjaan" required class="form-input" placeholder="Masukkan pekerjaan Anda" autocomplete="off" readonly>
        </div>

         <!-- Status Pasien -->
        <div>
          <label for="statusPasien" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-user-check mr-2"></i>Anda Adalah <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 3: Ganti name dan gunakan autocomplete="new-password" -->
          <select id="statusPasien" name="field_status_pasien" required class="form-input" autocomplete="new-password">
            <option value="">Pilih Status</option>
            <option value="pasien lama">Pasien Lama</option>
            <option value="pasien baru">Pasien Baru</option>
          </select>
        </div>
        
        <!-- Tanggal Lahir -->
        <div>
          <label for="tanggalLahir" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-calendar-alt mr-2"></i>Tanggal Lahir <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <input type="date" id="tanggalLahir" name="field_tanggal_lahir" required class="form-input" autocomplete="off" readonly>
        </div>

        <!-- Jadwal Booking -->
        <div>
          <label for="jadwalBooking" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-calendar-check mr-2"></i>Jadwal Booking <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <input type="date" id="jadwalBooking" name="field_jadwal_booking" required class="form-input" autocomplete="off" readonly>
          <span id="date-error" class="text-red-500 text-sm hidden">Jadwal booking tidak boleh kurang dari hari ini</span>
        </div>

        <!-- Tempat Lahir -->
        <div class="md:col-span-2">
          <label for="tempatLahir" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-home mr-2"></i>Tempat lahir <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <textarea id="tempatLahir" name="field_tempat_lahir" required class="form-input" placeholder="Masukkan Tempat Lahir Anda" autocomplete="off" readonly></textarea>
        </div>

        <!-- Alamat (FULL WIDTH) -->
        <div class="md:col-span-2">
          <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-home mr-2"></i>Alamat <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <textarea id="alamat" name="field_alamat" required class="form-input" placeholder="Masukkan alamat lengkap Anda" autocomplete="off" readonly></textarea>
        </div>

        <!-- Keluhan (FULL WIDTH) -->
        <div class="md:col-span-2">
          <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-notes-medical mr-2"></i>Keluhan <span class="text-red-500">*</span>
          </label>
          <!-- PERUBAHAN 2: Ganti name, tambah readonly & autocomplete -->
          <textarea id="keluhan" name="field_keluhan" required class="form-input" placeholder="Jelaskan keluhan atau layanan yang Anda butuhkan" autocomplete="off" readonly></textarea>
        </div>

        <!-- TOMBOL (FULL WIDTH & DI BAWAH) -->
        <div class="md:col-span-2 pt-6">
          <button type="submit" class="btn-primary w-full px-6 py-3 rounded-lg text-white font-semibold" id="submit-btn">
            <i class="fab fa-whatsapp mr-2"></i>
            <span id="submit-text">Kirim Reservasi ke WhatsApp</span>
            <span id="submit-loading" class="loading hidden"></span>
          </button>
        </div>
      </div>
    </form>
  </section>

  <div id="successModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md mx-auto text-center">
      <div class="bg-green-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
      </div>
      <h3 class="text-xl font-bold mb-2">Terima Kasih!</h3>
      <p class="text-gray-600 mb-6">Anda akan diarahkan ke WhatsApp untuk mengirim reservasi Anda. Silakan lengkapi pengiriman pesan di sana.</p>
      <button id="closeModal" class="px-6 py-2 bg-[#353ba7] text-white rounded-lg hover:bg-[#2d4286]">
        <i class="fas fa-check mr-2"></i>OK
      </button>
    </div>
  </div>

  <footer class="bg-gradient-to-r from-[#2d4286] via-[#353ba7] to-[#698ae8] text-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 px-6 md:px-10 py-12">
      <div class="md:col-span-2">
        <div class="flex items-center mb-4">
          <div class="bg-[#698ae8] p-2 rounded-lg mr-2">
            <i class="fas fa-bolt text-white"></i>
          </div>
          <span class="text-xl font-bold text-white">drg Pasri</span>
        </div>
        <p class="text-gray-300 mb-4">Senyum dari gigi sehat anda adalah prioritas kami.</p>
      </div>
      <div>
        <h3 class="font-semibold text-white mb-4">
          <i class="fas fa-cogs mr-2"></i>Our Service:
        </h3>
        <div class="grid grid-cols-2 gap-2">
          <div><i class="fas fa-home mr-2"></i><a href="#Home" class="hover:text-white">Home</a></div>
          <div><i class="fas fa-clinic-medical mr-2"></i><a href="#Clinic" class="hover:text-white">Klinik</a></div>
          <div><i class="fas fa-info-circle mr-2"></i><a href="#About" class="hover:text-white">About</a></div>
          <div><i class="fas fa-cogs mr-2"></i><a href="#Features" class="hover:text-white">Service</a></div>
          <div><i class="fas fa-envelope mr-2"></i><a href="#Contact" class="hover:text-white">Contact</a></div>
        </div>
      </div>
      <div>
        <h3 class="font-semibold text-white mb-4">
          <i class="fas fa-address-book mr-2"></i>Contact Us:
        </h3>
        <div class="grid grid-cols-2 gap-2">
          <div><i class="fab fa-whatsapp mr-2"></i><a href="tel:6283197242550" class="hover:text-white">WhatsApp</a></div>
          <div><i class="fas fa-map-marker-alt mr-2"></i><a href="https://www.google.com/maps/place/Praktek+drg.+Pasri+2/@-0.229835,100.6309176,15z/data=!4m10!1m2!2m1!1sklinik+drg+pasri!3m6!1s0x2e2ab53b83182793:0xc9caef2ea03ba540!8m2!3d-0.2261066!4d100.6420717!15sChBrbGluaWsgZHJnIHBhc2lykgEHZGVudGlzdKoBWQoIL20vMDk5ZnoQASoUIhBrbGluaWsgZHJnIHBhc2lyKCYyHxABIhtQbmdhYYC0mW9ICXX3Yz-zywwMkGojzJQJoHAyFBACIhBrbGluaWsgZHJnIHBhc2ly4AEA!16s%2Fg%2F11sddtxf5w?entry=ttu&g_ep=EgoyMDI1MTEwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="hover:text-white">Alamat</a></div>
        </div>
      </div>
    </div>
    <div class="border-t border-[#e1e7f6]/20 py-6 text-center text-gray-300 text-sm">
      <i class="fas fa-copyright mr-2"></i>2025 drg Pasri. All rights reserved.
    </div>
  </footer>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script>
  // ===============================
  // GLOBAL CONFIG
  // ===============================
  const BASE_URL = "<?= BASE_URL ?>";

  // ===============================
  // INIT LIBRARY
  // ===============================
  AOS.init({
    once: true,
    duration: 800
  });

  // ===============================
  // ELEMENTS
  // ===============================
  const menuBtn      = document.getElementById("menu-btn");
  const mobileMenu   = document.getElementById("mobile-menu");
  const successModal = document.getElementById("successModal");
  const closeModalBtn = document.getElementById("closeModal");
  const form         = document.getElementById("reservationForm");

  // ===============================
  // UI HANDLER
  // ===============================
  menuBtn?.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });

  document.querySelectorAll("#mobile-menu a").forEach(link => {
    link.addEventListener("click", () => {
      mobileMenu.classList.add("hidden");
    });
  });

  closeModalBtn?.addEventListener("click", () => {
    successModal.classList.add("hidden");
  });

  // ===============================
  // VALIDATION
  // ===============================
  function validateForm() {
    const get = id => document.getElementById(id).value.trim();

    const data = {
      nama: get("nama"),
      whatsapp: get("whatsapp"),
      pekerjaan: get("pekerjaan"),
      tempatLahir: get("tempatLahir"),
      tanggalLahir: document.getElementById("tanggalLahir").value,
      alamat: get("alamat"),
      statusPasien: document.getElementById("statusPasien").value,
      jadwalBooking: document.getElementById("jadwalBooking").value,
      keluhan: get("keluhan")
    };

    // reset error
    document.getElementById("whatsapp-error").classList.add("hidden");
    document.getElementById("date-error").classList.add("hidden");

    // wajib isi
    if (Object.values(data).some(v => !v)) {
      alert("Mohon isi semua field wajib (*)");
      return false;
    }

    // validasi whatsapp
    if (!/^(0|62)[0-9]+$/.test(data.whatsapp)) {
      document.getElementById("whatsapp-error").classList.remove("hidden");
      return false;
    }

    // validasi tanggal booking
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (new Date(data.jadwalBooking) < today) {
      document.getElementById("date-error").classList.remove("hidden");
      return false;
    }

    return data;
  }

  // ===============================
  // SUBMIT FORM (FETCH)
  // ===============================
  form?.addEventListener("submit", async e => {
    e.preventDefault();

    const data = validateForm();
    if (!data) return;

    try {
      const response = await fetch(
        BASE_URL + "controllers/api/reservasi.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            nama: data.nama,
            whatsapp: data.whatsapp,
            pekerjaan: data.pekerjaan,
            tempatLahir: data.tempatLahir,
            tanggalLahir: data.tanggalLahir,
            alamat: data.alamat,
            anda_adalah: data.statusPasien, // 🔑 kunci backend
            jadwalBooking: data.jadwalBooking,
            keluhan: data.keluhan
          })
        }
      );

      const result = await response.json();

      if (!result.success) {
        alert(result.message || "Gagal menyimpan reservasi");
        return;
      }

      // ===============================
      // OPEN WHATSAPP
      // ===============================
      const message =
        `Reservasi drg Pasri\n\n` +
        `Nama: ${data.nama}\n` +
        `No WA: ${data.whatsapp}\n` +
        `Status Pasien: ${data.statusPasien}\n` +
        `Jadwal: ${data.jadwalBooking}\n` +
        `Keluhan: ${data.keluhan}`;

      window.open(
        `https://wa.me/6283197242550?text=${encodeURIComponent(message)}`,
        "_blank"
      );

      successModal.classList.remove("hidden");
      form.reset();

    } catch (err) {
      console.error(err);
      alert("Terjadi kesalahan koneksi");
    }
  });

  // ===============================
  // PERUBAHAN 4: SCRIPT UNTUK MENGHILANGKAN AUTOFILL
  // ===============================
  document.addEventListener("DOMContentLoaded", function() {
    // Pilih semua input dan textarea yang memiliki atribut readonly
    const readonlyInputs = document.querySelectorAll('input[readonly], textarea[readonly]');
    
    readonlyInputs.forEach(input => {
      // Saat user fokus ke field, hapus atribut readonly
      input.addEventListener('focus', function() {
        this.removeAttribute('readonly');
      });
      
      // (Opsional) Saat user keluar dari field (blur), tambahkan kembali readonly
      // ini mungkin mengganggu user jika ingin mengedit lagi, jadi sebaiknya tidak digunakan
      // input.addEventListener('blur', function() {
      //   this.setAttribute('readonly', 'readonly');
      // });
    });
  });
</script>

</body>
</html>