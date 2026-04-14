tailwind.config.css
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    // Scan file HTML di root (untuk landing page statis seperti index.html)
    "./*.html",
    "./index.html",
    // Scan folder src jika ada JS/komponen (misal untuk React/Vue, atau script tambahan)
    "./src//*.{html,js,ts,jsx,tsx}",
    // Tambah path lain jika ada folder assets atau pages
    "./pages//*.{html,js}",
    "./components//*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      // Warna custom berdasarkan tema situs drg Pasri (gradient biru)
      colors: {
        primary: {
          light: '#e1e7f6',        // Background light blue (from-[#e1e7f6])
          lighter: '#f8f9fe',      // Very light blue (via-[#f8f9fe])
          blue: '#698ae8',         // Main blue (to-[#698ae8])
          dark: '#353ba7',         // Dark blue (via-[#353ba7])
          darker: '#2d4286',       // Darker blue (to-[#2d4286])
          accent: '#2d4286',       // Untuk shadow atau border
        },
        // Warna tambahan untuk elemen seperti rating (yellow) atau success (green)
        success: {
          500: '#10b981',          // Green untuk "Task Completed"
          600: '#059669',
        },
        warning: {
          500: '#f59e0b',          // Yellow untuk stars ★★★★★
        },
        gray: {
          50: '#f9fafb',           // Very light gray untuk text
          600: '#4b5563',          // Gray untuk body text
        },
      },
      // Font family: Gunakan Inter (modern sans-serif) sebagai default
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        // Tambah font bold untuk heading jika perlu
        bold: ['Inter', 'bold'],
      },
      // Spacing, sizing, atau breakpoints custom jika perlu (misal untuk mobile-first)
      spacing: {
        '18': '4.5rem',          // Custom spacing untuk py-18 jika butuh
      },
      // Animation extend untuk hover effects (seperti scale-105 yang sudah ada)
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'scale-up': 'scaleUp 0.3s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        scaleUp: {
          '0%': { transform: 'scale(1)' },
          '100%': { transform: 'scale(1.05)' },
        },
      },
      // Border radius custom untuk rounded-2xl yang konsisten
      borderRadius: {
        '2xl': '1rem',           // Sesuaikan jika perlu
      },
    },
  },
  // Plugins: Tambah plugin untuk fitur tambahan
  plugins: [
    // Plugin forms: Membuat input, select, textarea lebih styled (rounded, focus ring)
    require('@tailwindcss/forms'),
    // Plugin typography: Untuk styling prose (p, h1, dll.) jika ada konten panjang
    require('@tailwindcss/typography'),
    // Plugin aspect-ratio: Jika butuh gambar dengan ratio tetap
    require('@tailwindcss/aspect-ratio'),
  ],
}