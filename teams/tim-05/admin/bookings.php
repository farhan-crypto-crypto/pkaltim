<?php
session_start();
require_once '../config/database.php';

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';
$messageType = '';

// Handle Status Update
if (isset($_POST['status']) && isset($_POST['booking_id'])) {
    $status = $_POST['status'];
    $allowed_statuses = ['pending', 'confirmed', 'approved', 'rejected', 'cancelled'];
    
    if (in_array($status, $allowed_statuses)) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $_POST['booking_id']])) {
            $message = "Status pesanan #" . $_POST['booking_id'] . " diperbarui menjadi " . ucfirst($status) . "!";
            $messageType = "success";
        }
    }
}

// Filter Bookings
$filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$query = "SELECT b.*, u.name as user_name, d.name as dest_name 
          FROM bookings b 
          JOIN users u ON b.user_id = u.id 
          JOIN destinations d ON b.destination_id = d.id";

$params = [];
if ($filter != 'all') {
    $query .= " WHERE b.status = ?";
    $params[] = $filter;
}

$query .= " ORDER BY b.created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$title = "Kelola Pesanan";
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
            <!-- Toast Message -->
            <?php if ($message): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="fixed top-24 right-8 z-50 flex items-center gap-3 px-6 py-4 rounded-2xl border backdrop-blur-xl transition-all duration-500 <?php echo $messageType == 'success' ? 'bg-green-500/10 border-green-500/20 text-green-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
                <i class="bi <?php echo $messageType == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> text-xl"></i>
                <span class="font-bold"><?php echo $message; ?></span>
            </div>
            <?php endif; ?>

            <div class="mb-10 gsap-reveal">
                <h2 class="text-3xl font-display font-bold text-white mb-2">Riwayat Transaksi</h2>
                <p class="text-slate-500 text-sm">Validasi dan kelola semua pesanan tiket dari pengguna.</p>
            </div>

            <!-- Filters -->
            <div class="mb-8 flex flex-wrap gap-2 gsap-reveal">
                <?php
                $filters = [
                    'all' => 'Semua',
                    'pending' => 'Menunggu Bayar',
                    'confirmed' => 'Perlu Verifikasi',
                    'approved' => 'Selesai',
                    'rejected' => 'Ditolak',
                    'cancelled' => 'Dibatalkan'
                ];
                
                foreach ($filters as $key => $label): 
                    $active = $filter == $key ? 'bg-secondary text-black' : 'bg-white/5 text-slate-400 hover:bg-white/10';
                ?>
                <a href="?status=<?php echo $key; ?>" class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest transition-all <?php echo $active; ?>">
                    <?php echo $label; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="glass-card rounded-[2.5rem] overflow-hidden gsap-reveal">
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-white/5 text-slate-200 uppercase font-bold text-xs tracking-widest">
                            <tr>
                                <th class="px-8 py-6">Invoice</th>
                                <th class="px-8 py-6">Customer</th>
                                <th class="px-8 py-6">Destinasi</th>
                                <th class="px-8 py-6">Tanggal</th>
                                <th class="px-8 py-6">Total</th>
                                <th class="px-8 py-6">Status</th>
                                <th class="px-8 py-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($bookings as $booking): ?>
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-8 py-6 font-display font-bold text-white tracking-widest text-[10px] uppercase">
                                    <span class="text-slate-500">#</span><?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary font-bold text-xs border border-secondary/20">
                                            <?php echo strtoupper(substr($booking['user_name'], 0, 1)); ?>
                                        </div>
                                        <span class="text-slate-300 font-bold"><?php echo htmlspecialchars($booking['user_name']); ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-slate-300 font-medium"><?php echo htmlspecialchars($booking['dest_name']); ?></div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase mt-1">E-Ticket Type</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-slate-300"><?php echo date('d M Y', strtotime($booking['booking_date'])); ?></div>
                                    <div class="text-[10px] text-slate-600 font-bold uppercase mt-1">Visit Date</div>
                                </td>
                                <td class="px-8 py-6 font-bold text-secondary">
                                    Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                                    
                                    <?php if (!empty($booking['payment_proof'])): ?>
                                        <div class="mt-2">
                                            <a href="../<?php echo $booking['payment_proof']; ?>" target="_blank" class="text-[10px] text-blue-400 hover:text-blue-300 underline flex items-center gap-1">
                                                <i class="bi bi-paperclip"></i> Lihat Bukti
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6">
                                    <?php 
                                        if ($booking['status'] == 'pending'): ?>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                            </span>
                                        <?php elseif ($booking['status'] == 'confirmed'): ?>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Paid / Confirmed
                                            </span>
                                        <?php elseif ($booking['status'] == 'approved'): ?>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved
                                            </span>
                                        <?php elseif ($booking['status'] == 'cancelled'): ?>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-slate-500/10 text-slate-500 border border-slate-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Cancelled
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-rose-500/10 text-rose-500 border border-rose-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rejected
                                            </span>
                                        <?php endif; ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed'): ?>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" name="status" value="approved" title="Approve" class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" name="status" value="rejected" title="Reject" class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($booking['status'] != 'cancelled' && $booking['status'] != 'approved'): ?>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" name="status" value="cancelled" title="Cancel" class="w-8 h-8 rounded-lg bg-slate-500/10 text-slate-500 border border-slate-500/20 flex items-center justify-center hover:bg-slate-500 hover:text-white transition-all">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($booking['status'] == 'approved' || $booking['status'] == 'rejected'): ?>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <button type="submit" name="status" value="pending" title="Reset to Pending" class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
            stagger: 0.2,
            ease: "power4.out"
        });
    });
</script>
</body>
</html>
