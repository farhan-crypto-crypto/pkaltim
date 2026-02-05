<?php
require_once 'config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['booking_id'])) {
    header("Location: profile.php");
    exit;
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Fetch booking details
try {
    $stmt = $pdo->prepare("
        SELECT b.*, d.name as destination_name, d.image, d.category, d.location 
        FROM bookings b 
        JOIN destinations d ON b.destination_id = d.id 
        WHERE b.id = ? AND b.user_id = ?
    ");
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch();

    if (!$booking) {
        header("Location: profile.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Handle Payment Confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay'])) {
    $payment_proof = null;
    $error = null;

    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
        $target_dir = "assets/img/payments/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES["payment_proof"]["name"], PATHINFO_EXTENSION));
        $new_filename = "proof_" . $booking_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
            $payment_proof = $target_file;
        } else {
            $error = "Terjadi kesalahan saat mengunggah bukti pembayaran.";
        }
    } else {
        $error = "Harap unggah bukti pembayaran.";
    }

    if (!$error) {
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed', payment_proof = ? WHERE id = ?");
            $stmt->execute([$payment_proof, $booking_id]);
            
            echo "<script>
                alert('Pemesanan Berhasil Dikonfirmasi! Admin akan memverifikasi pembayaran Anda segera.');
                window.location.href = 'profile.php';
            </script>";
            exit;
        } catch (PDOException $e) {
            $error = "Gagal memproses pembayaran: " . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="bg-black min-h-screen pt-32 pb-20 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="mb-12 text-center gsap-reveal">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-2 block">Secure Payment</span>
                <h1 class="font-display font-medium text-4xl md:text-5xl text-white">Selesaikan <span class="italic font-serif text-secondary">Pembayaran</span></h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Summary Card -->
                <div class="lg:col-span-5 gsap-reveal" style="animation-delay: 0.1s">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] overflow-hidden">
                        <div class="aspect-video relative">
                            <img src="<?php echo htmlspecialchars($booking['image']); ?>" 
                                 class="w-full h-full object-cover" alt="Destination">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                            
                            <div class="absolute bottom-0 left-0 w-full p-8">
                                <span class="inline-block px-3 py-1 bg-secondary text-black text-[10px] font-bold uppercase tracking-wider rounded-full mb-3">
                                    <?php echo htmlspecialchars($booking['category']); ?>
                                </span>
                                <h2 class="font-display font-bold text-2xl text-white"><?php echo htmlspecialchars($booking['destination_name']); ?></h2>
                            </div>
                        </div>

                        <div class="p-8 space-y-6">
                            <div class="flex justify-between items-center text-slate-400 text-sm">
                                <span>Tanggal Kunjungan</span>
                                <span class="text-white font-medium"><?php echo date('d M Y', strtotime($booking['booking_date'])); ?></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400 text-sm">
                                <span>Jumlah Pengunjung</span>
                                <span class="text-white font-medium"><?php echo $booking['visitors']; ?> Orang</span>
                            </div>
                            
                            <hr class="border-white/10">
                            
                            <div class="space-y-2">
                                <span class="text-xs text-white/50 font-bold uppercase tracking-widest block">Total Tagihan</span>
                                <div class="flex justify-between items-end">
                                    <h3 class="text-4xl font-display font-bold text-secondary">
                                        Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                                    </h3>
                                    <span class="text-white/40 text-xs mb-1">ID: #<?php echo str_pad($booking_id, 4, '0', STR_PAD_LEFT); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="lg:col-span-7 gsap-reveal" style="animation-delay: 0.2s">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 md:p-10">
                        <h3 class="font-display font-medium text-xl text-white mb-8">Pilih Metode Pembayaran</h3>
                        
                        <div x-data="{ activeMethod: 'qris' }" class="space-y-4">
                            <!-- QRIS -->
                            <div 
                                @click="activeMethod = 'qris'"
                                :class="activeMethod === 'qris' ? 'border-secondary bg-secondary/5' : 'border-white/10 bg-black/20'"
                                class="p-6 rounded-2xl border transition-all cursor-pointer group">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                            <i class="bi bi-qr-code-scan text-2xl"></i>
                                        </div>
                                        <span class="font-bold text-white">QRIS</span>
                                    </div>
                                    <div :class="activeMethod === 'qris' ? 'bg-secondary' : 'border-2 border-white/20'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                        <div x-show="activeMethod === 'qris'" class="w-2 h-2 rounded-full bg-black"></div>
                                    </div>
                                </div>
                                <div x-show="activeMethod === 'qris'" class="text-center py-4 border-t border-white/10 mt-4">
                                    <p class="text-slate-400 text-xs mb-4 uppercase tracking-widest">Scan QR Code di bawah</p>
                                    <div class="bg-white p-4 d-inline-block rounded-3xl mb-4">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PesutTrip-<?php echo $booking_id; ?>" alt="QRIS Code" class="w-48 h-48 mx-auto">
                                    </div>
                                    <p class="text-slate-500 text-[10px]">Merchant ID: ID1234567890123</p>
                                </div>
                            </div>

                            <!-- Transfer Bank -->
                            <div 
                                @click="activeMethod = 'va'"
                                :class="activeMethod === 'va' ? 'border-secondary bg-secondary/5' : 'border-white/10 bg-black/20'"
                                class="p-6 rounded-2xl border transition-all cursor-pointer group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                            <i class="bi bi-bank text-2xl"></i>
                                        </div>
                                        <span class="font-bold text-white">Virtual Account / Transfer</span>
                                    </div>
                                    <div :class="activeMethod === 'va' ? 'bg-secondary' : 'border-2 border-white/20'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                        <div x-show="activeMethod === 'va'" class="w-2 h-2 rounded-full bg-black"></div>
                                    </div>
                                </div>
                                <div x-show="activeMethod === 'va'" class="space-y-3 pt-6 border-t border-white/10 mt-6 animate-fade-in">
                                    <div class="p-4 bg-black/40 rounded-xl border border-white/5 flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-bold text-white">BCA</span>
                                            <span class="text-[10px] text-slate-500">Virtual Account</span>
                                        </div>
                                        <span class="font-mono text-secondary text-sm">88012345678</span>
                                    </div>
                                    <div class="p-4 bg-black/40 rounded-xl border border-white/5 flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-bold text-white">MANDIRI</span>
                                            <span class="text-[10px] text-slate-500">Virtual Account</span>
                                        </div>
                                        <span class="font-mono text-secondary text-sm">90012345678</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="mt-10">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                                
                                <div class="mb-8">
                                    <label class="block text-secondary text-xs font-bold uppercase tracking-widest mb-4">Unggah Bukti Pembayaran</label>
                                    <div class="relative group">
                                        <div class="flex items-center gap-4 bg-white/5 border border-dashed border-white/20 rounded-2xl p-6 hover:border-secondary transition-all cursor-pointer relative">
                                            <input type="file" name="payment_proof" required class="absolute inset-0 opacity-0 cursor-pointer z-10" id="proofInput" onchange="document.getElementById('fileName').textContent = this.files[0].name">
                                            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                                <i class="bi bi-cloud-arrow-up text-2xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-white group-hover:text-secondary transition-colors" id="fileName">Pilih File Bukti Bayar...</p>
                                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Format: JPG, PNG, PDF (Max 2MB)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" name="pay" class="w-full py-5 bg-secondary hover:bg-white text-black font-bold rounded-2xl transition-all duration-300 shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)] transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                    <i class="bi bi-shield-lock-fill"></i>
                                    <span>Konfirmasi Pembayaran</span>
                                </button>
                            </form>
                            <a href="index.php" class="block text-center mt-6 text-slate-500 hover:text-white transition-colors text-sm font-medium">
                                Batalkan & Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.to('.gsap-reveal', {
            opacity: 1,
            y: 0,
            duration: 1.2,
            stagger: 0.15,
            ease: 'power4.out'
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
