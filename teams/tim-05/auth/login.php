<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            $error = "Email atau password salah!";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - PesutTrip</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#000000', 
                        secondary: '#d4af37',
                        dark: '#121212'
                    },
                    fontFamily: { 
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-black text-slate-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Visual Side -->
        <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-secondary/10 z-0"></div>
            <img src="../assets/img/hero_kalimantan.png" class="absolute inset-0 w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/40 to-transparent"></div>
            
            <div class="relative z-10 px-16 max-w-2xl">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-4 block">Welcome Back</span>
                <h2 class="font-display text-5xl font-medium text-white mb-6 leading-tight">
                    Lanjutkan <br> <span class="text-secondary italic font-serif">Petualanganmu</span>
                </h2>
                <p class="text-slate-300 text-lg font-light leading-relaxed">
                    Eksplorasi keindahan tersembunyi Samarinda dan Kalimantan. Hutan hujan, sungai, dan budaya menanti.
                </p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-dark border-l border-white/5 relative">
            <!-- Exit Button -->
            <a href="../index.php" class="absolute top-8 right-8 w-12 h-12 rounded-full bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white hover:text-black hover:border-white transition-all duration-300 group shadow-lg" title="Kembali ke Beranda">
                <i class="bi bi-x-lg text-xl"></i>
            </a>

            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <a href="../index.php" class="inline-block flex items-center justify-center gap-2 mb-6 group">
                         <span class="font-display font-bold text-3xl tracking-tight text-white">
                            Pesut<span class="text-secondary">Trip</span>
                        </span>
                    </a>
                    <h1 class="text-2xl font-bold text-white mb-2">Masuk ke Akun</h1>
                    <p class="text-slate-400 text-sm">Silakan masukkan detail akun Anda</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6 text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-secondary mb-2 tracking-wider">Email Address</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all placeholder-slate-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-secondary mb-2 tracking-wider">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all placeholder-slate-500">
                    </div>
                    
                    <button type="submit" class="w-full bg-secondary text-black font-bold py-4 rounded-full hover:bg-white transition-all duration-300 shadow-[0_0_20px_rgba(212,175,55,0.2)] mt-4">
                        Masuk Sekarang
                    </button>
                </form>

                <p class="text-center mt-8 text-slate-500 text-sm">
                    Belum punya akun? <a href="register.php" class="text-secondary font-bold hover:text-white transition-colors">Daftar disini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
