<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $error = "Email sudah terdaftar!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    $success = "Registrasi berhasil!";
                } else {
                    $error = "Gagal mendaftar.";
                }
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - PesutTrip</title>
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
            
            <div class="relative z-10 px-16 max-w-2xl text-right">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-4 block">Bergabunglah</span>
                <h2 class="font-display text-5xl font-medium text-white mb-6 leading-tight">
                    Mulai Petualangan <br> <span class="text-secondary italic font-serif">Barumu</span>
                </h2>
                <p class="text-slate-300 text-lg font-light leading-relaxed ml-auto">
                    Akses eksklusif ke destinasi terbaik Kalimantan dan nikmati kemudahan perjalanan.
                </p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-dark border-l border-white/5 overflow-y-auto relative">
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
                    <h1 class="text-2xl font-bold text-white mb-2">Buat Akun Baru</h1>
                    <p class="text-slate-400 text-sm">Masukan detail Anda untuk mendaftar</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6 text-sm"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl mb-6 text-sm">
                        <?php echo $success; ?> <a href="login.php" class="font-bold underline text-white">Masuk sekarang</a>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-secondary mb-2 tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all placeholder-slate-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-secondary mb-2 tracking-wider">Email Address</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all placeholder-slate-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-secondary mb-2 tracking-wider">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all placeholder-slate-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-secondary mb-2 tracking-wider">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all placeholder-slate-500">
                    </div>
                    
                    <button type="submit" class="w-full bg-secondary text-black font-bold py-4 rounded-full hover:bg-white transition-all duration-300 shadow-[0_0_20px_rgba(212,175,55,0.2)] mt-4">
                        Daftar Akun
                    </button>
                </form>

                <p class="text-center mt-8 text-slate-500 text-sm">
                    Sudah punya akun? <a href="login.php" class="text-secondary font-bold hover:text-white transition-colors">Masuk disini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
