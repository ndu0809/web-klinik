<?php

session_start();

require_once __DIR__ . '/../config/database.php';
 $db   = new Database();
 $conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Logika POST dapat ditambahkan di sini jika needed
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
    /* Modal styles (SAMA DENGAN VYNNSYNC) */
    .modal-container {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      overflow-y: auto;
    }
    .modal-container.hidden {
      display: none;
    }
    .modal-backdrop {
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      position: relative;
      background: white;
      border-radius: 0.5rem;
      max-width: 32rem;
      width: 100%;
      padding: 2rem;
      margin: auto;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      animation: modalFadeIn 0.3s ease-out;
    }
    @keyframes modalFadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }
    /* Mencegah scroll saat modal terbuka */
    body.modal-open {
      overflow: hidden;
    }
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
        <img src="1st.jpg" alt="Dokter gigi drg Pasri sedang melakukan pemeriksaan gigi pasien" class="w-full max-w-md rounded-xl shadow-lg" loading="lazy">
        <div class="absolute -top-6 -right-4 bg-white shadow-lg rounded-xl px-4 py-2 text-sm font-semibold text-green-700">
          <i class="fas fa-circle text-green-500 mr-2"></i>
          <div>Task Completed<br><span class="text-gray-500 text-xs">2 min lalu</span></div>
        </div>
        <div class="absolute -bottom-6 left-6 bg-white shadow-lg rounded-xl px-4 py-2 text-sm font-semibold text-[#353ba7]">
          <i class="fas fa-circle text-blue-500 mr-2"></i>
          <div>+23 New Members<br><span class="text-gray-500 text-xs">Minggu ini</span></div>
        </div>
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
        <img src="2nd.jpg" alt="Interior klinik gigi drg Pasri yang modern dan bersih" class="w-full max-w-xs rounded-xl shadow-lg" loading="lazy">
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
              <p class="text-gray-600">Senin - Sabtu: 16.30 - selesai.<br>Note: Jika melakukan pendaftaran pelayanan harap datang 15 menit lebih awal sebelum dilayani.</p>
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
          <img src="3rd.jpg" alt="drg Pasri sedang memeriksa pasien di klinik" class="w-full max-w-xs rounded-xl shadow-lg" loading="lazy">
          <div class="absolute -bottom-4 -right-4 bg-white shadow-lg rounded-xl px-4 py-2 text-sm font-semibold text-[#353ba7]">
            <i class="fas fa-user-md mr-2"></i>drg Pasri
          </div>
        </div>
        <div class="transition-all duration-300 hover:scale-105 hover:shadow-2xl relative">
          <img src="4th.jpg" alt="drg Pasri bersama tim medis di klinik" class="w-full max-w-xs rounded-xl shadow-lg" loading="lazy">
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
          <img src="extraction.png" alt="Prosedur pencabutan gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pencabutan Gigi / Extraction</h3>
        <p class="text-gray-600 text-sm">Membantu mencabut gigi yang bermasalah.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="toothfilling.png" alt="Prosedur penambalan gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Penambalan Gigi / Filling</h3>
        <p class="text-gray-600 text-sm">Membantu penambalan gigi yang berlubang.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="scalling.png" alt="Prosedur pembersihan karang gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pembersihan Karang Gigi / Scalling</h3>
        <p class="text-gray-600 text-sm">Membantu pembersihan karang gigi.</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12" data-aos="zoom-in">
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="rootcanal.png" alt="Prosedur perawatan saluran akar gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Perawatan Saluran Akar Gigi / Root Canal</h3>
        <p class="text-gray-600 text-sm">Membersihkan dan menyegel bagian dalam gigi yang terinfeksi atau rusak.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="cjacket.png" alt="Prosedur pembuatan porcelain crowns" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pembuatan Porcelain Crowns Jacket</h3>
        <p class="text-gray-600 text-sm">Pemasangan mahkota porselen untuk tutup gigi yang rusak.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="fissea.png" alt="Prosedur pencegahan gigi berlubang" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Pencegahan Gigi Berlubang / Fissure Sealent</h3>
        <p class="text-gray-600 text-sm">Membantu mencegah gigi berlubang.</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12" data-aos="zoom-in">
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="periodontal.jpg" alt="Prosedur perawatan jaringan periodontal" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Perawatan Jaringan Periodontal</h3>
        <p class="text-gray-600 text-sm">Menghentikan periodontitis, menghilangkan infeksi, mengurangi peradangan, memulihkan dan menjaga jaringan pendukung gigi.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="fake.png" alt="Prosedur tindakan rehabilitasi gigi" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
        </div>
        <h3 class="text-lg font-bold mb-2">Tindakan Rehabilitasi Gigi / Pemasangan Prothesa / Gigi Palsu</h3>
        <p class="text-gray-600 text-sm">Mengembalikan kesehatan, fungsi dan estetika mulut / gigi yang hilang / rusak.</p>
      </div>
      <div class="feature-card bg-white rounded-2xl shadow-md p-6 text-left">
        <div class="flex justify-center mb-6">
          <img src="ortho.png" alt="Prosedur perawatan pencegahan gigi crowded" class="w-32 h-32 rounded-xl object-cover" loading="lazy">
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
    <form id="reservationForm" class="bg-white rounded-2xl shadow-xl p-8 md:p-12 space-y-6 max-w-4xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-user mr-2"></i>Nama <span class="text-red-500">*</span>
          </label>
          <input type="text" id="nama" name="nama" required class="form-input" placeholder="Masukkan Nama Anda">
        </div>
        <div>
          <label for="whatsapp" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fab fa-whatsapp mr-2"></i>No WhatsApp <span class="text-red-500">*</span>
          </label>
          <input type="tel" id="whatsapp" name="whatsapp" required class="form-input" placeholder="Contoh: 081234567890">
          <span id="whatsapp-error" class="text-red-500 text-sm hidden">Format nomor WhatsApp tidak valid</span>
        </div>
        <div>
          <label for="pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-briefcase mr-2"></i>Pekerjaan <span class="text-red-500">*</span>
          </label>
          <input type="text" id="pekerjaan" name="pekerjaan" required class="form-input" placeholder="Masukkan pekerjaan Anda">
        </div>
        <div>
          <label for="tempatLahir" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-map-marker-alt mr-2"></i>Tempat Lahir <span class="text-red-500">*</span>
          </label>
          <input type="text" id="tempatLahir" name="tempat_lahir" required class="form-input" placeholder="Masukkan tempat lahir Anda">
        </div>
        <div>
          <label for="tanggalLahir" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-calendar-alt mr-2"></i>Tanggal Lahir <span class="text-red-500">*</span> (mm/dd/yyyy)
          </label>
          <input type="date" id="tanggalLahir" name="tanggal_lahir" required class="form-input">
        </div>
        <div>
          <label for="statusPasien" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-user-check mr-2"></i>Anda Adalah <span class="text-red-500">*</span>
          </label>
          <select id="statusPasien" name="status_pasien" required class="form-input">
            <option value="">Pilih opsi</option>
            <option value="pasien lama">Pasien Lama</option>
            <option value="pasien baru">Pasien Baru</option>
          </select>
        </div>
        <div>
          <label for="jadwalBooking" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-calendar-check mr-2"></i>Jadwal Booking <span class="text-red-500">*</span> (mm/dd/yyyy)
          </label>
          <input type="date" id="jadwalBooking" name="jadwal_booking" required class="form-input">
          <span id="date-error" class="text-red-500 text-sm hidden">Jadwal booking tidak boleh kurang dari hari ini</span>
        </div>
        <div>
          <label for="pesan" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-comment-alt mr-2"></i>Pesan (Opsional)
          </label>
          <textarea id="pesan" name="pesan" class="form-input" placeholder="Pesan tambahan (jika ada)"></textarea>
        </div>
      </div>
      <div>
        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
          <i class="fas fa-home mr-2"></i>Alamat <span class="text-red-500">*</span>
        </label>
        <textarea id="alamat" name="alamat" required class="form-input" placeholder="Masukkan alamat lengkap Anda"></textarea>
      </div>
      <div>
        <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-2">
          <i class="fas fa-notes-medical mr-2"></i>Keluhan / Layanan yang Dibutuhkan <span class="text-red-500">*</span>
        </label>
        <textarea id="keluhan" name="keluhan" required class="form-input" placeholder="Jelaskan keluhan atau layanan yang Anda butuhkan"></textarea>
      </div>
      <div class="pt-4">
        <button type="submit" class="btn-primary w-full px-6 py-3 rounded-lg text-white font-semibold" id="submit-btn">
          <i class="fab fa-whatsapp mr-2"></i>
          <span id="submit-text">Kirim Reservasi ke WhatsApp</span>
          <span id="submit-loading" class="loading hidden"></span>
        </button>
      </div>
    </form>
  </section>

  <!-- Modal Promo (SAMA DENGAN VYNNSYNC) -->
  <div id="promoModal" class="modal-container hidden">
    <div class="modal-backdrop absolute inset-0"></div>
    <div class="modal-content relative z-10">
      <div class="bg-blue-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
        <i class="fas fa-bell text-blue-500 text-3xl"></i>
      </div>
      <h3 class="text-xl font-bold mb-2">Ingin Reservasi?</h3>
      <p class="text-gray-600 mb-6">Jangan tunda lagi kesehatan gigi Anda! Lakukan reservasi sekarang juga untuk mendapatkan pelayanan terbaik dari drg Pasri.</p>
      <div class="flex gap-3 justify-center">
        <button id="modal-cta" class="px-6 py-2 bg-[#353ba7] text-white rounded-lg hover:bg-[#2d4286]">
          <i class="fas fa-calendar-check mr-2"></i>Lakukan Reservasi Sekarang
        </button>
        <button id="closeModal" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
          <i class="fas fa-times mr-2"></i>Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Terima Kasih (KHUSUS UNTUK FORM) -->
  <div id="thankYouModal" class="modal-container hidden">
    <div class="modal-backdrop absolute inset-0"></div>
    <div class="modal-content relative z-10">
      <div class="bg-green-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
      </div>
      <h3 class="text-xl font-bold mb-2">Terima Kasih!</h3>
      <p class="text-gray-600 mb-6">Anda akan diarahkan ke WhatsApp untuk mengirim reservasi Anda. Silakan lengkapi pengiriman pesan di sana.</p>
      <button id="closeThankYouModal" class="px-6 py-2 bg-[#353ba7] text-white rounded-lg hover:bg-[#2d4286]">
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
    AOS.init({ once: true, duration: 800 });
    
    const menuBtn = document.getElementById("menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    menuBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });
    
    document.querySelectorAll("#mobile-menu a").forEach(link => {
      link.addEventListener("click", () => {
        mobileMenu.classList.add("hidden");
      });
    });

    // --- MODAL LOGIC (SAMA DENGAN VYNNSYNC) ---
    const promoModal = document.getElementById("promoModal");
    const thankYouModal = document.getElementById("thankYouModal");
    const closeModalBtn = document.getElementById("closeModal");
    const closeThankYouModalBtn = document.getElementById("closeThankYouModal");
    const modalCtaBtn = document.getElementById("modal-cta");
    const body = document.body;
    
    // Variabel untuk kontrol popup scroll
    let lastScrollTop = 0;
    let popupCooldown = false;
    
    // Fungsi untuk membuka modal promo
    function openPromoModal() {
      promoModal.classList.remove("hidden");
      body.classList.add("modal-open");
    }
    
    // Fungsi untuk menutup modal promo
    function closePromoModal() {
      promoModal.classList.add("hidden");
      body.classList.remove("modal-open");
    }

    // Fungsi untuk membuka modal terima kasih
    function openThankYouModal() {
      thankYouModal.classList.remove("hidden");
      body.classList.add("modal-open");
    }
    
    // Fungsi untuk menutup modal terima kasih
    function closeThankYouModal() {
      thankYouModal.classList.add("hidden");
      body.classList.remove("modal-open");
    }
    
    // Event listener untuk tombol tutup
    closeModalBtn.addEventListener("click", closePromoModal);
    closeThankYouModalBtn.addEventListener("click", closeThankYouModal);
    
    // Tutup modal ketika klik di luar area modal
    promoModal.addEventListener("click", (e) => {
      if (e.target === promoModal) {
        closePromoModal();
      }
    });
    thankYouModal.addEventListener("click", (e) => {
      if (e.target === thankYouModal) {
        closeThankYouModal();
      }
    });
    
    // Event listener untuk CTA button di modal promo
    modalCtaBtn.addEventListener("click", () => {
      closePromoModal();
      document.getElementById('Contact').scrollIntoView({behavior: 'smooth'});
    });
    
    // Event listener untuk scroll
    document.addEventListener('scroll', () => {
      const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Deteksi arah scroll ke atas
      if (currentScrollTop < lastScrollTop && currentScrollTop < 300) {
        
        // Cek apakah sedang dalam cooldown atau modal sudah terbuka
        if (!popupCooldown && promoModal.classList.contains('hidden')) {
          openPromoModal();
          
          // Set cooldown selama 5 detik agar popup tidak muncul terlalu sering
          popupCooldown = true;
          setTimeout(() => {
            popupCooldown = false;
          }, 5000); // 5000ms = 5 detik
        }
      }
      
      // Perbarui posisi scroll terakhir
      lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;
    }, false);
    
    // Tampilkan modal setelah 10 detik pada saat halaman dimuat
    setTimeout(() => {
      if (promoModal.classList.contains('hidden')) {
        openPromoModal();
      }
    }, 10000);

    // --- FORM SUBMISSION LOGIC ---
    function validateForm() {
      const nama = document.getElementById("nama").value.trim();
      const whatsapp = document.getElementById("whatsapp").value.trim();
      const pekerjaan = document.getElementById("pekerjaan").value.trim();
      const tempatLahir = document.getElementById("tempatLahir").value.trim();
      const tanggalLahir = document.getElementById("tanggalLahir").value;
      const alamat = document.getElementById("alamat").value.trim();
      const statusPasien = document.getElementById("statusPasien").value;
      const jadwalBooking = document.getElementById("jadwalBooking").value;
      const keluhan = document.getElementById("keluhan").value.trim();
      
      document.getElementById("whatsapp-error").classList.add("hidden");
      document.getElementById("date-error").classList.add("hidden");
      
      if (!nama || !whatsapp || !pekerjaan || !tempatLahir || !tanggalLahir || !alamat || !statusPasien || !jadwalBooking || !keluhan) {
        alert("Mohon isi semua field wajib (*)");
        return false;
      }
      
      const whatsappRegex = /^(0|62)[0-9]+$/;
      if (!whatsappRegex.test(whatsapp)) {
        document.getElementById("whatsapp-error").classList.remove("hidden");
        return false;
      }
      
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const bookingDate = new Date(jadwalBooking);
      if (bookingDate < today) {
        document.getElementById("date-error").classList.remove("hidden");
        return false;
      }
      
      return true;
    }
    
    document.getElementById("reservationForm").addEventListener("submit", async function(e) {
      e.preventDefault();
      if (!validateForm()) return;
      
      const submitBtn = document.getElementById("submit-btn");
      const submitText = document.getElementById("submit-text");
      const submitLoading = document.getElementById("submit-loading");
      
      submitBtn.disabled = true;
      submitText.classList.add("hidden");
      submitLoading.classList.remove("hidden");
      
      const nama = document.getElementById("nama").value;
      const whatsapp = document.getElementById("whatsapp").value;
      const pekerjaan = document.getElementById("pekerjaan").value;
      const tempatLahir = document.getElementById("tempatLahir").value;
      const tanggalLahir = document.getElementById("tanggalLahir").value;
      const alamat = document.getElementById("alamat").value;
      const statusPasien = document.getElementById("statusPasien").value;
      const jadwalBooking = document.getElementById("jadwalBooking").value;
      const keluhan = document.getElementById("keluhan").value;
      const pesan = document.getElementById("pesan").value || "Tidak ada pesan tambahan";
      
      try {
        let reservations = JSON.parse(localStorage.getItem('drgPasriReservations') || '[]');
        reservations.unshift({
          id: Date.now().toString(),
          timestamp: new Date().toISOString(),
          nama, whatsapp, pekerjaan, tempatLahir, tanggalLahir, 
          alamat, statusPasien, jadwalBooking, keluhan, pesan
        });
        localStorage.setItem('drgPasriReservations', JSON.stringify(reservations));
      } catch (error) {
        console.error('Failed to save reservation:', error);
      }
      
      const message = `Reservasi drg Pasri\n\n` +
                     `Nama: ${nama}\n` +
                     `No WhatsApp: ${whatsapp}\n` +
                     `Pekerjaan: ${pekerjaan}\n` +
                     `Tempat Lahir: ${tempatLahir}\n` +
                     `Tanggal Lahir: ${tanggalLahir}\n` +
                     `Alamat: ${alamat}\n` +
                     `Status Pasien: ${statusPasien}\n` +
                     `Jadwal Booking: ${jadwalBooking}\n` +
                     `Keluhan/Layanan: ${keluhan}\n` +
                     `Pesan Tambahan: ${pesan}\n\n` +
                     `Terima kasih! Silakan konfirmasi reservasi.`;
                     
      await fetch('/web_klinik/controllers/api/reservasi.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          nama, whatsapp, pekerjaan, tempatLahir,
          tanggalLahir, alamat, statusPasien,
          jadwalBooking, keluhan, pesan
        })
      });
      
      const WHATSAPP_NUMBER = "6283197242550";
      const whatsappURL = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
      
      try {
        window.open(whatsappURL, '_blank');
      } catch (error) {
        console.error('Failed to open WhatsApp:', error);
        alert('Gagal membuka WhatsApp. Silakan buka manual dan kirim pesan reservasi.');
      }
      
      // Tampilkan modal terima kasih
      openThankYouModal();
      this.reset();
      
      submitBtn.disabled = false;
      submitText.classList.remove("hidden");
      submitLoading.classList.add("hidden");
    });
  </script>
</body>
</html>