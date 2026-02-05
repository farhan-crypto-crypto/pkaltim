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

// Handle User Deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION['user_id']) {
        $message = "Anda tidak dapat menghapus akun Anda sendiri!";
        $messageType = "error";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$user_id])) {
                $message = "Pengguna berhasil dihapus!";
                $messageType = "success";
            }
        } catch (PDOException $e) {
            $message = "Gagal menghapus pengguna: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// Fetch Users (excluding current admin to be safe, or just show all)
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$title = "Kelola Pengguna";
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
                <h2 class="text-3xl font-display font-bold text-white mb-2">Kelola Pengguna</h2>
                <p class="text-slate-500 text-sm">Daftar semua customer dan pengelola sistem.</p>
            </div>

            <div class="glass-card rounded-[2.5rem] overflow-hidden gsap-reveal">
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-white/5 text-slate-200 uppercase font-bold text-xs tracking-widest">
                            <tr>
                                <th class="px-8 py-6">User</th>
                                <th class="px-8 py-6">Email</th>
                                <th class="px-8 py-6">Role</th>
                                <th class="px-8 py-6">Registered At</th>
                                <th class="px-8 py-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary font-bold text-sm border border-secondary/20">
                                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-white font-bold"><?php echo htmlspecialchars($user['name']); ?></span>
                                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Account ID: #<?php echo $user['id']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-slate-300 font-medium">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td class="px-8 py-6">
                                    <?php if ($user['role'] == 'admin'): ?>
                                        <span class="px-3 py-1 bg-secondary/10 text-secondary border border-secondary/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                            Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-white/5 text-slate-400 border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                            User
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-slate-400"><?php echo date('d M Y', strtotime($user['created_at'])); ?></div>
                                    <div class="text-[10px] text-slate-600 font-bold uppercase mt-1"><?php echo date('H:i', strtotime($user['created_at'])); ?> WIB</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center">
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" name="delete_user" class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all group-hover:scale-110">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-[10px] text-slate-600 font-bold uppercase italic">Current User</span>
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
