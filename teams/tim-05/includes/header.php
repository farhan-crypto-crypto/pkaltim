<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PesutTrip - Jelajahi Keindahan Samarinda</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN for Dev) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000', // Pure Black
                        secondary: '#d4af37', // Gold
                        accent: '#F59E0B', // Amber
                        dark: '#121212', // Dark Grey
                        light: '#F8FAFC',
                        surface: 'rgba(255,255,255,0.03)' // Very subtle overlay
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/ScrollTrigger.min.js"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        [x-cloak] { display: none !important; }
        .gsap-reveal { opacity: 0; transform: translateY(30px); }
        
        /* Glassmorphism */
        .glass {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Animations */
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        .animate-scale-up { animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        /* Custom Scrollbar for Modal */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.5); }

        /* Print Layout */
        @media print {
            body * { visibility: hidden; }
            #printable-ticket, #printable-ticket * { visibility: visible; }
            #printable-ticket {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-black text-slate-200 antialiased selection:bg-secondary selection:text-black">

<!-- Navbar -->
<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'glass shadow-lg': scrolled, 'bg-transparent py-4': !scrolled, 'py-2': scrolled }"
     class="fixed w-full z-50 transition-all duration-300">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Clock & Calendar (Left Side) -->
            <div x-data="{ 
                    time: new Date(),
                    init() { setInterval(() => this.time = new Date(), 1000) }
                 }" 
                 class="hidden lg:flex items-center gap-4 mr-8 text-xs font-medium bg-white/5 border border-white/10 px-4 py-2 rounded-full backdrop-blur-md">
                <div class="flex items-center gap-2 text-slate-300">
                    <i class="bi bi-calendar4-week"></i>
                    <span x-text="time.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' })"></span>
                </div>
                <div class="w-px h-4 bg-white/10"></div>
                <div class="flex items-center gap-2 text-secondary">
                    <i class="bi bi-clock"></i>
                    <span x-text="time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':')"></span>
                </div>
            </div>

            <!-- Logo -->
            <a href="index.php" class="flex-shrink-0 flex items-center gap-2 group mr-6 lg:mr-10">
                <i class="bi bi-airplane-fill text-3xl text-secondary transition-transform group-hover:-rotate-12 group-hover:scale-110"></i>
                <span class="font-display font-bold text-2xl tracking-tight text-white">
                    Pesut<span class="text-secondary">Trip</span>
                </span>
            </a>

            <!-- Header Search Bar (Tiket.com Style) -->
            <div class="hidden lg:flex flex-1 max-w-md mr-auto">
                <form action="search.php" method="GET" class="w-full relative group">
                    <input type="text" 
                           name="location" 
                           placeholder="Mau liburan ke mana?" 
                           class="w-full bg-white/10 border border-white/20 rounded-full py-2.5 pl-12 pr-4 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:bg-white/15 transition-all duration-300">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-secondary transition-colors"></i>
                </form>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="index.php" class="text-sm font-medium transition-colors hover:text-secondary <?php echo $current_page == 'index.php' ? 'text-secondary' : 'text-slate-300'; ?>">Beranda</a>
                <a href="destinations.php" class="text-sm font-medium transition-colors hover:text-secondary <?php echo $current_page == 'destinations.php' ? 'text-secondary' : 'text-slate-300'; ?>">Destinasi</a>
                <a href="guide.php" class="text-sm font-medium transition-colors hover:text-secondary <?php echo $current_page == 'guide.php' ? 'text-secondary' : 'text-slate-300'; ?>">Panduan</a>
                <a href="about.php" class="text-sm font-medium transition-colors hover:text-secondary <?php echo $current_page == 'about.php' ? 'text-secondary' : 'text-slate-300'; ?>">Tentang</a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="admin/dashboard.php" class="text-sm font-medium text-slate-300 hover:text-secondary">Dashboard</a>
                    <?php else: ?>
                        <a href="profile.php" class="text-sm font-medium transition-colors hover:text-secondary <?php echo $current_page == 'profile.php' ? 'text-secondary' : 'text-slate-300'; ?>">Profil</a>
                    <?php endif; ?>
                    
                    <a href="auth/logout.php" class="px-5 py-2.5 text-sm font-semibold text-white border border-white/20 rounded-full hover:bg-white hover:text-primary transition-all duration-300">
                        Keluar
                    </a>
                <?php else: ?>
                    <div class="flex items-center gap-4">
                        <a href="auth/login.php" class="text-sm font-medium text-white hover:text-secondary transition-colors">Masuk</a>
                        <a href="auth/register.php" class="px-5 py-2.5 text-sm font-semibold text-primary bg-secondary rounded-full hover:bg-amber-400 hover:shadow-lg hover:shadow-amber-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                            Daftar
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white focus:outline-none">
                <i class="bi" :class="mobileMenuOpen ? 'bi-x-lg' : 'bi-list'" style="font-size: 1.5rem;"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @click.away="mobileMenuOpen = false"
         class="md:hidden absolute top-full left-0 w-full glass border-t border-white/10 shadow-xl">
        <div class="flex flex-col p-4 space-y-4 text-center">
            <a href="index.php" class="text-white hover:text-secondary font-medium">Beranda</a>
            <a href="destinations.php" class="text-white hover:text-secondary font-medium">Destinasi</a>
            <a href="guide.php" class="text-white hover:text-secondary font-medium">Panduan</a>
            <a href="about.php" class="text-white hover:text-secondary font-medium">Tentang</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="text-white hover:text-secondary font-medium">Profil</a>
                <a href="auth/logout.php" class="text-red-400 hover:text-red-300 font-medium">Keluar</a>
            <?php else: ?>
                <hr class="border-white/10">
                <a href="auth/login.php" class="text-white hover:text-secondary font-medium">Masuk</a>
                <a href="auth/register.php" class="inline-block bg-secondary text-primary font-bold py-2 px-6 rounded-full">Daftar Sekarang</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Content Padding -->
<div class=""></div>
