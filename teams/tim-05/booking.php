<?php
require_once 'config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
    $stmt->execute([$id]);
    $dest = $stmt->fetch();

    if (!$dest) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $visitors = $_POST['visitors'];
    
    if ($visitors < 1) {
        $error = "Jumlah pengunjung minimal 1.";
    } else {
        $total_price = $dest['price'] * $visitors;
        
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, destination_id, booking_date, visitors, total_price, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            if ($stmt->execute([$_SESSION['user_id'], $id, $date, $visitors, $total_price])) {
                $booking_id = $pdo->lastInsertId();
                header("Location: payment.php?booking_id=" . $booking_id);
                exit;
            } else {
                $error = "Gagal memproses pemesanan.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="min-h-screen pt-32 pb-20 bg-black relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-12 text-center gsap-reveal">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-2 block">Reservation Form</span>
                <h1 class="font-display font-medium text-4xl md:text-5xl text-white">Konfirmasi <span class="italic font-serif text-secondary">Pemesanan</span></h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Left: Destination Info -->
                <div class="lg:col-span-2 gsap-reveal" style="animation-delay: 0.1s">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] overflow-hidden sticky top-32">
                        <div class="aspect-[4/5] relative">
                            <img src="<?php echo htmlspecialchars($dest['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($dest['name']); ?>" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                            
                            <div class="absolute bottom-0 left-0 w-full p-8">
                                <span class="inline-block px-3 py-1 bg-secondary text-black text-[10px] font-bold uppercase tracking-wider rounded-full mb-3">
                                    <?php echo htmlspecialchars($dest['category']); ?>
                                </span>
                                <h2 class="font-display font-bold text-2xl text-white mb-2"><?php echo htmlspecialchars($dest['name']); ?></h2>
                                <div class="flex items-center gap-2 text-slate-300 text-sm">
                                    <i class="bi bi-geo-alt text-secondary"></i>
                                    <span><?php echo htmlspecialchars($dest['location']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8 border-t border-white/5">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-slate-400 text-sm">Harga per orang</span>
                                <span class="text-white font-bold">Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="flex justify-between items-center text-secondary">
                                <span class="text-sm font-medium">Rating</span>
                                <div class="flex items-center gap-1">
                                    <i class="bi bi-star-fill text-xs"></i>
                                    <span class="font-bold"><?php echo $dest['rating']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Booking Form -->
                <div class="lg:col-span-3 gsap-reveal" style="animation-delay: 0.2s">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 md:p-10">
                        <?php if ($error): ?>
                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-2xl mb-8 flex items-center gap-3">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span class="text-sm font-medium"><?php echo $error; ?></span>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-8">
                            <!-- Date Selection -->
                            <div>
                                <label class="block text-secondary text-xs font-bold uppercase tracking-widest mb-4">Pilih Tanggal Kunjungan</label>
                                <div class="relative group">
                                    <i class="bi bi-calendar-event absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-secondary transition-colors"></i>
                                    <input type="date" name="date" required 
                                           min="<?php echo date('Y-m-d'); ?>"
                                           class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-14 pr-6 text-white appearance-none focus:outline-none focus:border-secondary/50 focus:ring-1 focus:ring-secondary/50 transition-all [&::-webkit-calendar-picker-indicator]:invert [&::-webkit-calendar-picker-indicator]:opacity-50 hover:[&::-webkit-calendar-picker-indicator]:opacity-100">
                                </div>
                            </div>

                            <!-- Visitors Selection -->
                            <div>
                                <label class="block text-secondary text-xs font-bold uppercase tracking-widest mb-4">Jumlah Pengunjung</label>
                                <div class="relative group">
                                    <i class="bi bi-people absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-secondary transition-colors"></i>
                                    <input type="number" name="visitors" id="visitors" value="1" min="1" required
                                           class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-14 pr-6 text-white focus:outline-none focus:border-secondary/50 focus:ring-1 focus:ring-secondary/50 transition-all">
                                </div>
                            </div>

                            <!-- Payment Summary -->
                            <div class="bg-white/5 border border-white/5 rounded-3xl p-6 space-y-4">
                                <div class="flex justify-between items-center text-slate-400">
                                    <span class="text-sm">Total Pembayaran</span>
                                    <span class="text-xs italic">(Termasuk pajak & biaya layanan)</span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <span class="text-slate-300 text-sm">Estimasi Biaya</span>
                                    <h3 class="text-3xl font-display font-bold text-secondary" id="totalPriceDisplay">
                                        Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?>
                                    </h3>
                                </div>
                            </div>

                            <div class="pt-4 space-y-4">
                                <button type="submit" class="w-full py-5 bg-secondary hover:bg-white text-black font-bold rounded-2xl transition-all duration-300 shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)] transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                    <span>Lanjut ke Pembayaran</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                                
                                <a href="detail.php?id=<?php echo $id; ?>" class="block w-full py-4 text-center text-slate-400 hover:text-white transition-colors text-sm font-medium">
                                    Batalkan Pemesanan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const pricePerPerson = <?php echo $dest['price']; ?>;
    const visitorsInput = document.getElementById('visitors');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');

    visitorsInput.addEventListener('input', function() {
        const visitors = Math.max(1, parseInt(this.value) || 0);
        const total = visitors * pricePerPerson;
        totalPriceDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    });

    // Simple GSAP trigger
    document.addEventListener('DOMContentLoaded', () => {
        gsap.to('.gsap-reveal', {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.2,
            ease: 'power3.out'
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
