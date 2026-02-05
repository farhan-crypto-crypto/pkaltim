<?php
session_start();
require_once '../config/database.php';

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch Stats
try {
    $total_destinations = $pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn();
    $total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    // $pending_bookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn(); 
    // We prioritize 'confirmed' (paid but not approved) as actionable items
    $need_verification = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn();
    $total_earnings = $pdo->query("SELECT SUM(total_price) FROM bookings WHERE status = 'approved'")->fetchColumn();
    
    // Fetch recent bookings for display
    $stmt = $pdo->query("
        SELECT b.*, u.name as user_name, d.name as dest_name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN destinations d ON b.destination_id = d.id 
        ORDER BY b.created_at DESC LIMIT 5
    ");
    $recent_bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$title = "Dashboard Overview";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $title; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#000000', 
                        secondary: '#d4af37',
                        dark: '#121212',
                        surface: 'rgba(255,255,255,0.03)'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gsap-reveal { opacity: 0; transform: translateY(20px); }
    </style>
</head>
<body class="bg-primary font-sans antialiased text-slate-200">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto bg-primary relative">
        <?php include 'includes/header.php'; ?>

        <main class="p-8 max-w-7xl mx-auto">
            <div class="mb-10 gsap-reveal">
                <h2 class="text-3xl font-display font-bold text-white mb-2">Selamat Datang, <?php echo explode(' ', $_SESSION['name'])[0]; ?>!</h2>
                <p class="text-slate-500 text-sm">Berikut adalah ringkasan performa PesutTrip hari ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Stat Card 1 -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-secondary transition-all duration-500 gsap-reveal">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/10 rounded-full blur-2xl group-hover:bg-secondary/20 transition-all"></div>
                    <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center border border-secondary/20 mb-6 font-bold text-secondary">
                        <i class="bi bi-geo-alt text-xl"></i>
                    </div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total Destinasi</p>
                    <p class="text-4xl font-display font-bold text-white"><?php echo $total_destinations; ?></p>
                </div>
                
                <!-- Stat Card 2 -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-blue-500 transition-all duration-500 gsap-reveal" style="animation-delay: 0.1s">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 mb-6 font-bold text-blue-500">
                        <i class="bi bi-ticket-perforated text-xl"></i>
                    </div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total Booking</p>
                    <p class="text-4xl font-display font-bold text-white"><?php echo $total_bookings; ?></p>
                </div>

                <!-- Stat Card 3 -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-orange-500 transition-all duration-500 gsap-reveal" style="animation-delay: 0.2s">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/20 transition-all"></div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center border border-orange-500/20 mb-6 font-bold text-orange-500">
                        <i class="bi bi-exclamation-circle text-xl"></i>
                    </div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Perlu Verifikasi</p>
                    <p class="text-4xl font-display font-bold text-white"><?php echo $need_verification; ?></p>
                </div>

                <!-- Stat Card 4 -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-emerald-500 transition-all duration-500 gsap-reveal" style="animation-delay: 0.3s">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 mb-6 font-bold text-emerald-500">
                        <i class="bi bi-currency-dollar text-xl"></i>
                    </div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Pendapatan</p>
                    <p class="text-2xl font-display font-bold text-white">Rp <?php echo number_format($total_earnings ?? 0, 0, ',', '.'); ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Orders -->
                <div class="glass-card rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="font-display font-bold text-xl text-white">Pesanan <span class="text-secondary italic font-serif">Terbaru</span></h3>
                        <a href="bookings.php" class="text-[10px] font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Semua Pesanan</a>
                    </div>
                    
                    <div class="space-y-4">
                        <?php if (empty($recent_bookings)): ?>
                            <div class="py-10 text-center text-slate-600 italic">Belum ada pesanan terbaru.</div>
                        <?php else: ?>
                            <?php foreach ($recent_bookings as $booking): ?>
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary font-bold text-xs">
                                        <?php echo strtoupper(substr($booking['user_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white"><?php echo htmlspecialchars($booking['user_name']); ?></p>
                                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tight"><?php echo htmlspecialchars($booking['dest_name']); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-secondary">Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></p>
                                    <span class="text-[9px] font-bold uppercase tracking-widest <?php 
                                        if($booking['status'] == 'approved') echo 'text-emerald-500';
                                        elseif($booking['status'] == 'confirmed') echo 'text-blue-500';
                                        elseif($booking['status'] == 'pending') echo 'text-amber-500';
                                        elseif($booking['status'] == 'cancelled') echo 'text-slate-500';
                                        else echo 'text-rose-500';
                                    ?>">
                                        <?php echo $booking['status']; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- System Info / Shortcuts -->
                <div class="glass-card rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.5s">
                    <h3 class="font-display font-bold text-xl text-white mb-8">Quick <span class="text-secondary italic font-serif">Actions</span></h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <a href="destinations.php" class="p-6 rounded-3xl bg-white/5 border border-white/5 hover:border-secondary/50 transition-all group">
                            <i class="bi bi-plus-circle text-2xl text-secondary mb-4 block group-hover:scale-110 transition-transform"></i>
                            <p class="text-sm font-bold text-white">Tambah Wisata</p>
                            <p class="text-[10px] text-slate-500 uppercase mt-1">Destinasi Baru</p>
                        </a>
                        <a href="bookings.php" class="p-6 rounded-3xl bg-white/5 border border-white/5 hover:border-blue-500/50 transition-all group">
                            <i class="bi bi-check2-all text-2xl text-blue-500 mb-4 block group-hover:scale-110 transition-transform"></i>
                            <p class="text-sm font-bold text-white">Cek Pesanan</p>
                            <p class="text-[10px] text-slate-500 uppercase mt-1">Approval List</p>
                        </a>
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5 col-span-2 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-white">Server Status</p>
                                <p class="text-[10px] text-emerald-500 uppercase mt-1">Operational • Active</p>
                            </div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.to(".gsap-reveal", {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.1,
            ease: "power4.out"
        });
    });
</script>
</body>
</html>
