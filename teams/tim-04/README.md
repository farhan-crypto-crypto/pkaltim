# Tim 04 - Wisata Alam Kalimantan Timur
**Project:** Liangpran Eco-Tourism (Mahakam Ulu)  
**PIC:** Ghani  
**Subtema:** Wisata Alam - Gunung Liangpran | Mahakam Ulu

## 👥 Anggota Tim
1. Andhika Galih Isya Putra (Leader)
2. Muhammad Aldiansyah
3. Al-Ghani Desta Setyawan

## 📝 Deskripsi Project
Frontend statis dengan simulasi sistem fullstack (Mock Backend) untuk wisata alam Gunung Liangpran. Project ini mengusung tema **"Dark Nature"** dengan fokus pada pengalaman visual immersive dan storytelling.

### 📂 Struktur Directory (`draft/`)
Project utama terletak di dalam folder `draft/`.

- **`index.html`**: Halaman publik utama (Landing Page). Menggunakan konten statis untuk performa dan stabilitas maksimal.
- **`admin.html`**: Dashboard Admin dengan simulasi CRUD. Menggunakan `system.js` sebagai mock database.
    - **Login**: `admin` / `123`
- **`system.js`**: Layer simulasi backend & database (menggunakan `localStorage`).

## ✨ Fitur & Teknologi
### Frontend (Public)
- **Framework**: Tailwind CSS (CDN)
- **Animation**: GSAP (ScrollTrigger, SplitText), Lenis (Smooth Scroll)
- **Interactive**: Custom Cursor, WebGL Fog (Three.js)
- **Content**: Static Data for Nature, Culture, Biodiversity, & Trails.

### Admin Panel (Private)
- **UI**: Custom "Dark Nature" Glassmorphism
- **Features**:
    - [x] Login Authentication (Mock Session)
    - [x] CRUD Data (Biodiversity & Trails)
    - [x] Real-time Analytics Chart (Chart.js)
    - [x] Responsive Sidebar Layout

## 🎯 Status Target Fitur
- [x] CRUD lengkap (Simulated via LocalStorage)
- [x] Login admin (Simulated)
- [x] Responsive UI (Mobile First)
- [x] Integrasi Peta (Google Maps Embed)
- [x] Database (Mock Relational DB di `system.js`)
- [ ] Deploy hosting

## 🚀 Cara Menjalankan
1. Buka `draft/index.html` untuk melihat website publik.
2. Buka `draft/admin.html` untuk masuk ke panel admin (Gunakan akun: `admin` / `123`).

---
**Last Update:** 10 Januari 2026
**Version:** V4 (Static Refactor)
