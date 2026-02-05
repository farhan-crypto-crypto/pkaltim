<?php
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Handle Profile Update
$update_success = false;
$update_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_password = $_POST['password'];
    $user_id = $_SESSION['user_id'];

    if (empty($new_name)) {
        $update_error = 'Nama tidak boleh kosong.';
    } else {
        try {
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $update_error = 'Password minimal 6 karakter.';
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
                    $stmt->execute([$new_name, $hashed_password, $user_id]);
                    $update_success = true;
                }
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
                $stmt->execute([$new_name, $user_id]);
                $update_success = true;
            }

            if ($update_success) {
                $_SESSION['name'] = $new_name;
            }
        } catch (PDOException $e) {
            $update_error = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}

try {
    $user_id = $_SESSION['user_id'];
    $bookings = $pdo->prepare("
        SELECT b.*, d.name as dest_name, d.location 
        FROM bookings b 
        JOIN destinations d ON b.destination_id = d.id 
        WHERE b.user_id = ? 
        ORDER BY b.created_at DESC
    ");
    $bookings->execute([$user_id]);
    $bookings = $bookings->fetchAll();

    // Fetch existing reviews to avoid duplicates
    $stmt = $pdo->prepare("SELECT destination_id FROM reviews WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $reviewed_destinations = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- Page Header -->
<section class="relative pt-40 pb-16 overflow-hidden">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-secondary/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-6xl mx-auto">
            <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-2 block gsap-reveal">User Profile</span>
            <h1 class="font-display font-medium text-4xl md:text-5xl text-white mb-4 gsap-reveal">Akun <span class="italic font-serif text-secondary">Saya</span></h1>
        </div>
    </div>
</section>

<section class="pb-24" x-data="{ editModalOpen: <?php echo ($update_error || $update_success) ? 'true' : 'false'; ?> }">
    <!-- Feedback Toasts -->
    <?php if ($update_success): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-8 left-8 z-[100] bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill"></i>
            <p class="font-bold text-sm">Profil berhasil diperbarui!</p>
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Column 1: Profile Sidebar -->
            <div class="lg:col-span-3 gsap-reveal">
                <div class="bg-white/5 border border-white/10 rounded-[2rem] p-8 text-center backdrop-blur-xl sticky top-28">
                    <div class="mb-6 relative inline-block">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-secondary to-amber-200 flex items-center justify-center text-3xl font-bold text-black shadow-lg shadow-secondary/20">
                            <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-4 border-black rounded-full"></div>
                    </div>
                    <h4 class="font-display font-bold text-xl text-white mb-1 leading-tight"><?php echo htmlspecialchars($_SESSION['name']); ?></h4>
                    <p class="text-slate-400 text-[10px] mb-8 tracking-widest uppercase">Exclusive Member</p>
                    
                    <div class="space-y-3">
                        <button @click="editModalOpen = true" class="flex items-center justify-center gap-2 w-full py-3 rounded-full bg-secondary/10 border border-secondary/20 text-secondary text-sm font-bold hover:bg-secondary hover:text-black transition-all duration-300 mb-2">
                            <i class="bi bi-pencil-square"></i>
                            Edit Profil
                        </button>

                        <a href="auth/logout.php" class="flex items-center justify-center gap-2 w-full py-3 rounded-full border border-red-500/30 text-red-400 text-sm font-semibold hover:bg-red-500 hover:text-white transition-all duration-300">
                            <i class="bi bi-box-arrow-right"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Booking History -->
            <div class="lg:col-span-6">
                <?php if (isset($_GET['booked']) && $_GET['booked'] == 'true'): ?>
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-6 py-4 rounded-2xl mb-8 flex items-center justify-between gsap-reveal">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill"></i>
                            <p class="font-medium text-sm">Pesanan berhasil dibuat!</p>
                        </div>
                        <button type="button" class="text-green-400" @click="$el.parentElement.remove()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-between mb-8 gsap-reveal">
                    <h3 class="font-display font-bold text-2xl text-white">Riwayat Pemesanan</h3>
                    <span class="bg-white/5 border border-white/10 text-slate-300 text-xs font-semibold px-4 py-1.5 rounded-full">
                        <?php echo count($bookings); ?> Transaksi
                    </span>
                </div>

                <?php if (empty($bookings)): ?>
                    <div class="bg-white/5 border border-white/10 rounded-[2rem] p-12 text-center gsap-reveal">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="bi bi-ticket-perforated text-3xl text-slate-600"></i>
                        </div>
                        <p class="text-slate-400 mb-8 text-sm italic">Belum ada pemesanan.</p>
                        <a href="index.php" class="inline-block px-8 py-3 bg-secondary text-black font-bold rounded-full hover:bg-white transition-all">
                            Eksplor Sekarang
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($bookings as $index => $booking): ?>
                            <div class="group bg-white/5 border border-white/10 rounded-[2rem] overflow-hidden hover:border-secondary/50 transition-all duration-500 gsap-reveal">
                                <div class="p-6 md:p-8">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                                        <div>
                                            <span class="text-secondary text-[10px] font-bold tracking-widest uppercase mb-1 block">Destinasi</span>
                                            <h5 class="font-display font-bold text-xl text-white group-hover:text-secondary transition-colors leading-tight"><?php echo htmlspecialchars($booking['dest_name']); ?></h5>
                                        </div>
                                        <?php 
                                            $statusStyles = ''; $statusText = '';
                                            $status = strtolower(trim($booking['status']));
                                            if ($status == 'pending') {
                                                $statusStyles = 'bg-amber-500/10 text-amber-500 border-amber-500/20'; $statusText = 'Pending';
                                            } elseif ($status == 'confirmed') {
                                                $statusStyles = 'bg-blue-500/10 text-blue-500 border-blue-500/20'; $statusText = 'Verifikasi';
                                            } elseif ($status == 'approved') {
                                                $statusStyles = 'bg-green-500/10 text-green-500 border-green-500/20'; $statusText = 'Disetujui';
                                            } elseif ($status == 'cancelled') {
                                                $statusStyles = 'bg-slate-500/10 text-slate-500 border-slate-500/20'; $statusText = 'Batal';
                                            } else {
                                                $statusStyles = 'bg-red-500/10 text-red-500 border-red-500/20'; $statusText = 'Ditolak';
                                            }
                                        ?>
                                        <span class="px-4 py-1.5 rounded-full border text-[10px] font-bold uppercase tracking-widest <?php echo $statusStyles; ?>">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6 border-y border-white/5 py-6">
                                        <div>
                                            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Tanggal</p>
                                            <p class="text-white text-sm font-medium"><?php echo date('d M Y', strtotime($booking['booking_date'])); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Tiket</p>
                                            <p class="text-white text-sm font-medium"><?php echo $booking['visitors']; ?> Orang</p>
                                        </div>
                                        <div class="col-span-2 md:col-span-1">
                                            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total</p>
                                            <p class="text-secondary font-bold text-lg">Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                        <div class="text-slate-500 text-[10px]">
                                            <i class="bi bi-clock mr-1"></i>
                                            <?php echo date('d/m/y H:i', strtotime($booking['created_at'])); ?>
                                        </div>
                                        <?php if($booking['status'] == 'approved'): ?>
                                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                                <?php if (!in_array($booking['destination_id'], $reviewed_destinations)): ?>
                                                <a href="detail.php?id=<?php echo $booking['destination_id']; ?>#reviews" class="flex-1 sm:flex-none px-5 py-2.5 bg-secondary/10 border border-secondary/30 text-secondary text-xs font-bold rounded-full hover:bg-secondary hover:text-black transition-all flex items-center justify-center gap-2">
                                                    <i class="bi bi-star"></i> Review
                                                </a>
                                                <?php endif; ?>
                                                
                                                <button onclick="printTicket(<?php echo htmlspecialchars(json_encode($booking)); ?>)" class="flex-1 sm:flex-none px-5 py-2.5 bg-white/5 border border-white/20 text-white text-xs font-bold rounded-full hover:bg-white hover:text-primary transition-all flex items-center justify-center gap-2">
                                                    <i class="bi bi-printer"></i> Tiket
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Column 3: Review History -->
            <div class="lg:col-span-3">
                <div class="flex items-center gap-3 mb-8 gsap-reveal">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary border border-secondary/20">
                        <i class="bi bi-star-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-display font-medium text-white">Riwayat <span class="text-secondary italic font-serif">Ulasan</span></h3>
                    </div>
                </div>

                <?php
                $stmt = $pdo->prepare("
                    SELECT r.*, d.name as dest_name, d.image as dest_image 
                    FROM reviews r 
                    JOIN destinations d ON r.destination_id = d.id 
                    WHERE r.user_id = ? 
                    ORDER BY r.created_at DESC
                ");
                $stmt->execute([$_SESSION['user_id']]);
                $my_reviews = $stmt->fetchAll();
                ?>

                <?php if (empty($my_reviews)): ?>
                    <div class="bg-white/5 border border-white/10 rounded-[2rem] p-8 text-center gsap-reveal">
                        <p class="text-slate-400 text-xs italic">Belum ada ulasan.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($my_reviews as $review): ?>
                        <div class="bg-white/5 border border-white/10 rounded-[2rem] p-5 hover:border-secondary/30 transition-colors group gsap-reveal">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10 shrink-0">
                                    <img src="<?php echo htmlspecialchars($review['dest_image']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-white text-sm truncate"><?php echo htmlspecialchars($review['dest_name']); ?></h4>
                                    <div class="flex items-center gap-1 text-secondary text-[8px]">
                                        <div class="flex">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="bi <?php echo $i <= $review['rating'] ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-slate-500 ml-1"><?php echo date('d/m/y', strtotime($review['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-slate-300 text-[11px] italic mb-3 line-clamp-2">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                            
                            <?php if ($review['reply']): ?>
                            <div class="bg-black/40 border-l-2 border-secondary p-3 rounded-r-xl">
                                <p class="text-[8px] text-secondary font-bold uppercase tracking-widest mb-1">Respon</p>
                                <p class="text-slate-400 text-[10px] italic line-clamp-2">"<?php echo htmlspecialchars($review['reply']); ?>"</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
            <!-- Edit Profile Modal -->
            <div x-show="editModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                 @keydown.escape.window="editModalOpen = false"
                 x-cloak>
                <div class="bg-dark/95 border border-white/10 w-full max-w-md rounded-[2.5rem] p-8 md:p-10 shadow-2xl relative overflow-hidden"
                     @click.away="editModalOpen = false"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="scale-90 opacity-0"
                     x-transition:enter-end="scale-100 opacity-100">
                    
                    <!-- Decor -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="font-display font-bold text-2xl text-white">Edit <span class="text-secondary italic">Profil</span></h3>
                            <button @click="editModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>

                        <?php if ($update_error): ?>
                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-xs flex items-center gap-2">
                                <i class="bi bi-exclamation-circle"></i>
                                <?php echo $update_error; ?>
                            </div>
                        <?php endif; ?>

                        <form action="profile.php" method="POST" class="space-y-6">
                            <div>
                                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:bg-white/10 transition-all">
                            </div>
                            <div>
                                <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Password Baru (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" placeholder="••••••••"
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:bg-white/10 transition-all">
                            </div>

                            <button type="submit" name="update_profile" 
                                    class="w-full py-4 bg-secondary text-black font-bold rounded-full hover:bg-white hover:scale-[1.02] active:scale-95 transition-all duration-300 shadow-lg shadow-secondary/10 mt-4">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Hidden Printable Ticket Template -->
<div id="printable-ticket" class="hidden">
    <div style="width: 800px; margin: 0 auto; border: 2px solid #000; padding: 30px; font-family: 'Inter', sans-serif; position: relative; background: #fff;">
        <div style="display: flex; justify-between; align-items: center; border-bottom: 2px dashed #000; padding-bottom: 20px; margin-bottom: 20px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin: 0; color: #000;">PESUT<span style="color: #d4af37;">TRIP</span></h1>
                <p style="font-size: 12px; margin: 5px 0 0; color: #666; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">Official E-Ticket</p>
            </div>
            <div style="text-align: right;">
                <p style="font-size: 12px; margin: 0; color: #666; font-weight: bold;">ORDER ID</p>
                <p id="ticket-order-id" style="font-size: 18px; font-weight: 800; margin: 0;">#BOOK-000</p>
            </div>
        </div>

        <div style="display: flex; gap: 40px;">
            <div style="flex: 2;">
                <h2 id="ticket-dest-name" style="font-size: 28px; font-weight: 800; margin: 0 0 10px; color: #000;">Destination Name</h2>
                <p id="ticket-location" style="font-size: 14px; margin: 0 0 30px; color: #666;"><i class="bi bi-geo-alt"></i> Location, Samarinda</p>

                <div style="grid; grid-template-columns: repeat(2, 1fr); display: grid; gap: 20px;">
                    <div>
                        <p style="font-size: 10px; font-weight: 800; color: #999; text-transform: uppercase; margin: 0 0 5px;">Tanggal Kunjungan</p>
                        <p id="ticket-date" style="font-size: 16px; font-weight: 700; margin: 0;">01 Januari 2024</p>
                    </div>
                    <div>
                        <p style="font-size: 10px; font-weight: 800; color: #999; text-transform: uppercase; margin: 0 0 5px;">Jumlah Pengunjung</p>
                        <p id="ticket-visitors" style="font-size: 16px; font-weight: 700; margin: 0;">1 Orang</p>
                    </div>
                    <div>
                        <p style="font-size: 10px; font-weight: 800; color: #999; text-transform: uppercase; margin: 0 0 5px;">Nama Pemesan</p>
                        <p id="ticket-user-name" style="font-size: 16px; font-weight: 700; margin: 0;"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
                    </div>
                    <div>
                        <p style="font-size: 10px; font-weight: 800; color: #999; text-transform: uppercase; margin: 0 0 5px;">Status</p>
                        <p style="font-size: 14px; font-weight: 800; margin: 0; color: #059669;">LUNAS / PAID</p>
                    </div>
                </div>
            </div>
            
            <div style="flex: 1; border-left: 2px dashed #eee; padding-left: 40px; text-align: center;">
                <div style="width: 150px; h-150px; background: #eee; margin: 0 auto 15px; display: flex; items-center; justify-content: center; border-radius: 10px;">
                    <i class="bi bi-qr-code" style="font-size: 100px;"></i>
                </div>
                <p style="font-size: 10px; color: #999; margin: 0;">Scan QR Code ini di gerbang masuk</p>
            </div>
        </div>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 10px; color: #999;">
            <p style="margin: 0;">* Tiket ini berlaku sesuai tanggal kunjungan yang tertera.</p>
            <p style="margin: 5px 0 0;">* Tunjukkan tiket ini kepada petugas di lokasi untuk ditukar dengan akses masuk.</p>
        </div>
    </div>
</div>

<script>
    function printTicket(booking) {
        // Populate Ticket Data
        document.getElementById('ticket-order-id').textContent = '#BOOK-' + booking.id;
        document.getElementById('ticket-dest-name').textContent = booking.dest_name;
        document.getElementById('ticket-location').textContent = booking.location;
        document.getElementById('ticket-date').textContent = new Date(booking.booking_date).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        document.getElementById('ticket-visitors').textContent = booking.visitors + ' Orang';
        
        // Temporarily show the ticket for printing
        const ticket = document.getElementById('printable-ticket');
        ticket.classList.remove('hidden');
        
        window.print();
        
        // Hide it again
        ticket.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        gsap.to('.gsap-reveal', {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.1,
            ease: 'power4.out',
            scrollTrigger: {
                trigger: '.gsap-reveal',
                start: 'top 80%',
            }
        });
    });
</script>


<?php require_once 'includes/footer.php'; ?>
