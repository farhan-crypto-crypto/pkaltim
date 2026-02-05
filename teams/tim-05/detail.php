<?php
require_once 'config/database.php';
require_once 'includes/header.php';

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

    // Fetch Reviews
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as user_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.destination_id = ? 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$id]);
    $reviews = $stmt->fetchAll();

    // Count reviews
    $total_reviews = count($reviews);

    // Fetch Visitor Images for Gallery
    $stmt = $pdo->prepare("SELECT image FROM reviews WHERE destination_id = ? AND image IS NOT NULL ORDER BY created_at DESC");
    $stmt->execute([$id]);
    $visitor_images = $stmt->fetchAll();

    // Check if current user can review (must have an approved booking AND not reviewed yet)
    $can_review = false;
    $has_reviewed = false;
    
    if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'user') {
        // 1. Check for approved booking
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND destination_id = ? AND status = 'approved'");
        $stmt->execute([$_SESSION['user_id'], $id]);
        if ($stmt->fetchColumn() > 0) {
            $can_review = true;
        }
        
        // 2. Check if already reviewed (Fetch data for editing)
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ? AND destination_id = ?");
        $stmt->execute([$_SESSION['user_id'], $id]);
        $my_review = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($my_review) {
            $has_reviewed = true;
            $can_review = true; // Allow opening modal for editing
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="bg-black min-h-screen">
    <!-- Hero for Detail -->
    <div class="relative h-[70vh] min-h-[500px] overflow-hidden">
        <img src="<?php echo htmlspecialchars($dest['image']); ?>" class="w-full h-full object-cover grayscale-[0.2] hover:grayscale-0 transition-all duration-1000 scale-105" alt="<?php echo htmlspecialchars($dest['name']); ?>">
        
        <!-- Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-8 md:p-20 z-10">
            <div class="container mx-auto">
                <div class="max-w-4xl gsap-reveal">
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <span class="bg-secondary/20 text-secondary px-4 py-1.5 rounded-full backdrop-blur-md border border-secondary/30 text-xs font-bold uppercase tracking-widest">
                            <?php echo htmlspecialchars($dest['category']); ?>
                        </span>
                        <div class="flex items-center gap-2 text-white/90 text-sm font-medium">
                            <!-- Precise Star Rating -->
                            <div class="relative flex text-white/30">
                                <?php for($i=0; $i<5; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                <div class="absolute top-0 left-0 flex overflow-hidden text-secondary" style="width: <?php echo ($dest['rating'] / 5) * 100; ?>%">
                                    <?php for($i=0; $i<5; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                </div>
                            </div>
                            <span class="ml-1"><?php echo number_format($dest['rating'], 1); ?></span>
                            <span class="text-white/40">•</span>
                            <span><?php echo $total_reviews; ?> Reviews</span>
                        </div>
                    </div>
                    
                    <h1 class="font-display font-medium text-5xl md:text-7xl text-white mb-6 leading-tight tracking-tight">
                        <?php echo htmlspecialchars($dest['name']); ?>
                    </h1>
                    
                    <p class="text-slate-300 text-lg md:text-xl flex items-center gap-3 font-light">
                        <i class="bi bi-geo-alt text-secondary"></i> 
                        <?php echo htmlspecialchars($dest['location']); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container mx-auto px-4 lg:px-8 py-16 -mt-16 relative z-20">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Main Content (Left) -->
            <div class="lg:w-2/3 space-y-8">
                <!-- About Section -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 md:p-12 gsap-reveal" style="animation-delay: 0.1s">
                    <h3 class="font-display font-bold text-2xl text-white mb-8 flex items-center gap-4">
                        <span class="w-8 h-1 bg-secondary rounded-full"></span>
                        Tentang Destinasi
                    </h3>
                    <div class="prose prose-invert max-w-none">
                        <p class="text-slate-300 text-lg leading-relaxed font-light whitespace-pre-line">
                            <?php echo htmlspecialchars($dest['description']); ?>
                        </p>
                    </div>
                    
                    <!-- Facilities Header -->
                    <div class="mt-12 pt-10 border-t border-white/10">
                        <h4 class="text-white font-bold mb-8 uppercase tracking-widest text-xs opacity-60">Fasilitas & Highlight</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="flex items-center gap-4 text-slate-200 bg-white/5 p-5 rounded-2xl border border-white/5 hover:border-secondary/30 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-black transition-all">
                                    <i class="bi bi-camera text-xl"></i>
                                </div>
                                <span class="font-medium">Spot Foto Estetik</span>
                            </div>
                            <div class="flex items-center gap-4 text-slate-200 bg-white/5 p-5 rounded-2xl border border-white/5 hover:border-secondary/30 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-black transition-all">
                                    <i class="bi bi-p-circle text-xl"></i>
                                </div>
                                <span class="font-medium">Area Parkir Luas</span>
                            </div>
                            <div class="flex items-center gap-4 text-slate-200 bg-white/5 p-5 rounded-2xl border border-white/5 hover:border-secondary/30 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-black transition-all">
                                    <i class="bi bi-shop text-xl"></i>
                                </div>
                                <span class="font-medium">Kantin & Resto</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 md:p-12 gsap-reveal" style="animation-delay: 0.2s">
                    <h3 class="font-display font-bold text-2xl text-white mb-8 flex items-center gap-4">
                        <span class="w-8 h-1 bg-secondary rounded-full"></span>
                        Galeri Foto
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group overflow-hidden rounded-3xl aspect-video relative">
                            <img src="<?php echo htmlspecialchars($dest['image']); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div class="group overflow-hidden rounded-3xl aspect-video relative">
                            <?php if (!empty($visitor_images)): ?>
                                <img src="<?php echo htmlspecialchars($visitor_images[0]['image']); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <img src="assets/img/feature_kalimantan.png" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 opacity-50">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                        </div>
                    </div>
                </div>

                <!-- Visitor Gallery Section -->
                <?php if (!empty($visitor_images)): ?>
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 md:p-12 gsap-reveal" style="animation-delay: 0.25s">
                    <h3 class="font-display font-bold text-2xl text-white mb-8 flex items-center gap-4">
                        <span class="w-8 h-1 bg-secondary rounded-full"></span>
                        Galeri Kegiatan Pengunjung
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($visitor_images as $img): ?>
                        <div class="group relative aspect-square rounded-2xl overflow-hidden border border-white/10 hover:border-secondary/50 transition-all duration-500">
                            <img src="<?php echo htmlspecialchars($img['image']); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="bi bi-zoom-in text-white text-2xl"></i>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews Section -->
                <div id="reviews" class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 md:p-12 gsap-reveal" style="animation-delay: 0.3s">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                        <h3 class="font-display font-bold text-2xl text-white flex items-center gap-4">
                            <span class="w-8 h-1 bg-secondary rounded-full"></span>
                            Ulasan Pengunjung
                        </h3>
                        
                        <?php if ($has_reviewed): ?>
                        <button onclick='openEditModal(<?php echo json_encode($my_review); ?>)' class="px-6 py-3 bg-secondary/10 border border-secondary/20 hover:bg-secondary/20 rounded-2xl text-secondary text-sm font-bold transition-all flex items-center gap-2">
                            <i class="bi bi-pencil-square"></i> Edit Ulasan Saya
                        </button>
                        <?php elseif ($can_review): ?>
                        <button onclick="toggleModal(true)" class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white text-sm font-bold transition-all flex items-center gap-2">
                            <i class="bi bi-pencil-square"></i> Tulis Ulasan
                        </button>
                        <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] == 'user'): ?>
                        <div class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl text-slate-500 text-xs font-medium flex items-center gap-2 italic">
                            <i class="bi bi-info-circle"></i> Selesaikan kunjungan untuk mengulas
                        </div>
                        <?php endif; ?>
                    </div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('reviewModal');
        if (show) {
            modal.classList.remove('hidden');
            // Default reset
            if(!document.getElementById('modal-title').getAttribute('data-editing')) {
                setRating(5);
                document.querySelector('textarea[name="comment"]').value = '';
                document.getElementById('modal-title').innerHTML = 'Tulis <span class="text-secondary italic font-serif">Ulasan</span>';
            }
        } else {
            modal.classList.add('hidden');
            // Clear editing state on close
            document.getElementById('modal-title').removeAttribute('data-editing');
        }
    }

    function openEditModal(reviewData) {
        const modal = document.getElementById('reviewModal');
        const title = document.getElementById('modal-title');
        
        title.innerHTML = 'Edit <span class="text-secondary italic font-serif">Ulasan</span>';
        title.setAttribute('data-editing', 'true');
        
        // Fill data
        setRating(reviewData.rating);
        document.querySelector('textarea[name="comment"]').value = reviewData.comment;
        
        modal.classList.remove('hidden');
    }

    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach(btn => {
            const btnRating = parseInt(btn.dataset.rating);
            if (btnRating <= rating) {
                btn.classList.remove('bg-white/5', 'border-white/10', 'text-slate-500');
                btn.classList.add('bg-secondary', 'border-secondary', 'text-black');
            } else {
                btn.classList.add('bg-white/5', 'border-white/10', 'text-slate-500');
                btn.classList.remove('bg-secondary', 'border-secondary', 'text-black');
            }
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        const placeholder = document.getElementById('uploadPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImage() {
        const container = document.getElementById('imagePreviewContainer');
        const placeholder = document.getElementById('uploadPlaceholder');
        const input = document.querySelector('input[type="file"]');
        
        input.value = '';
        container.classList.add('hidden');
        placeholder.classList.remove('hidden');
    }
</script>

                    <?php if (empty($reviews)): ?>
                        <div class="py-24 text-center border border-dashed border-white/10 rounded-[2.5rem] bg-white/[0.01]">
                            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-chat-dots text-3xl text-secondary/50"></i>
                            </div>
                            <h4 class="text-white font-bold text-xl mb-2">Belum ada ulasan</h4>
                            <p class="text-slate-500 max-w-xs mx-auto">Jadilah yang pertama untuk menceritakan pengalaman seru Anda di sini!</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($reviews as $review): ?>
                            <div class="p-8 rounded-[2rem] bg-white/[0.03] border border-white/10 backdrop-blur-sm hover:bg-white/[0.05] hover:border-secondary/30 transition-all duration-500 group">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-secondary/20 to-secondary/5 flex items-center justify-center text-secondary font-display font-bold text-xl border border-secondary/20 shadow-inner">
                                            <?php echo strtoupper(substr($review['user_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-white font-bold"><?php echo htmlspecialchars($review['user_name']); ?></h4>
                                                <span class="bg-secondary/10 text-secondary text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter border border-secondary/20">Verified Visitor</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="flex text-secondary text-[10px] gap-0.5">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <i class="bi <?php echo $i <= $review['rating'] ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest bg-white/5 px-2 py-0.5 rounded-md"><?php echo date('d M Y', strtotime($review['created_at'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <i class="bi bi-quote absolute -top-2 -left-2 text-4xl text-white/5 pointer-events-none"></i>
                                    <p class="text-slate-300 leading-relaxed italic relative z-10 pl-4 border-l-2 border-secondary/30">
                                        "<?php echo htmlspecialchars($review['comment']); ?>"
                                    </p>
                                </div>

                                <?php if ($review['image']): ?>
                                <div class="mt-8 rounded-[1.5rem] overflow-hidden border border-white/10 w-full max-w-[400px] aspect-video group/img relative">
                                    <img src="<?php echo htmlspecialchars($review['image']); ?>" class="w-full h-full object-cover transform group-hover/img:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover/img:opacity-100 transition-opacity flex items-end p-4">
                                        <span class="text-white text-xs font-medium flex items-center gap-2">
                                            <i class="bi bi-camera"></i> Photo by Visitor
                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Admin Reply -->
                                <?php if (!empty($review['reply'])): ?>
                                <div class="mt-8 ml-4 md:ml-12 p-6 bg-secondary/5 border-l-2 border-secondary rounded-2xl relative">
                                    <div class="absolute -top-3 left-6 px-3 py-1 bg-secondary text-black text-[10px] font-bold uppercase tracking-widest rounded-full">Official Response</div>
                                    <p class="text-slate-300 text-sm italic mt-2">"<?php echo htmlspecialchars($review['reply']); ?>"</p>
                                    <?php if(!empty($review['reply_at'])): ?>
                                        <div class="mt-3 text-[10px] text-white/30 font-medium"><?php echo date('d M Y', strtotime($review['reply_at'])); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Review Modal (Vanilla JS) -->
                <div id="reviewModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <!-- Backdrop -->
                        <div onclick="toggleModal(false)" class="fixed inset-0 bg-black/90 backdrop-blur-xl transition-opacity animate-fade-in cursor-pointer"></div>
                        
                        <!-- Modal Panel -->
                        <div class="inline-block align-bottom bg-black border border-white/10 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-8 md:p-10 relative z-10 animate-scale-up max-h-[90vh] overflow-y-auto custom-scrollbar">
                            
                            <form action="submit_review.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="destination_id" value="<?php echo $id; ?>">
                                <div class="flex items-center justify-between mb-8">
                                    <h3 class="text-2xl font-display font-bold text-white" id="modal-title">Tulis <span class="text-secondary italic font-serif">Ulasan</span></h3>
                                    <button type="button" onclick="toggleModal(false)" class="text-slate-500 hover:text-white transition-colors">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <!-- Star Selection -->
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-4">Rating Anda</label>
                                        <div class="flex gap-3" id="starContainer">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                            <button type="button" 
                                                    onclick="setRating(<?php echo $i; ?>)" 
                                                    class="w-12 h-12 rounded-xl flex items-center justify-center transition-all border star-btn"
                                                    data-rating="<?php echo $i; ?>">
                                                <i class="bi bi-star-fill text-lg"></i>
                                            </button>
                                            <?php endfor; ?>
                                            <input type="hidden" name="rating" id="ratingInput" value="5">
                                        </div>
                                    </div>

                                    <!-- Comment Textarea -->
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-4">Ceritakan Pengalaman Anda</label>
                                        <textarea name="comment" required rows="4" 
                                                  class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary transition-all"
                                                  placeholder="Tuliskan ulasan Anda mengenai tempat ini..."></textarea>
                                    </div>

                                    <!-- Image Upload -->
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-4">Sisipkan Gambar (Opsional)</label>
                                        <div class="relative group">
                                            <input type="file" name="image" accept="image/*" 
                                                   onchange="previewImage(this)"
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div class="w-full bg-white/5 border border-white/10 border-dashed rounded-2xl p-8 text-center group-hover:bg-white/10 transition-all" id="uploadPlaceholder">
                                                <i class="bi bi-cloud-arrow-up text-3xl text-slate-500 mb-2 block"></i>
                                                <span class="text-sm text-slate-400">Pilih foto pengalaman Anda</span>
                                            </div>
                                        </div>
                                        <!-- Preview -->
                                        <div id="imagePreviewContainer" class="hidden mt-4 relative rounded-2xl overflow-hidden border border-white/10 aspect-video">
                                            <img id="imagePreview" class="w-full h-full object-cover">
                                            <button type="button" onclick="clearImage()" class="absolute top-2 right-2 bg-black/60 backdrop-blur-md text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-500 transition-colors">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full mt-10 py-5 bg-secondary hover:bg-white text-black font-bold rounded-2xl transition-all duration-300 shadow-[0_10px_30px_rgba(212,175,55,0.3)]">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Widget (Right/Sticky) -->
            <div class="lg:w-1/3">
                <div class="sticky top-32 space-y-6 gsap-reveal" style="animation-delay: 0.3s">
                    <!-- Price Card -->
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/20 rounded-full blur-[80px] -mr-10 -mt-10"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-end gap-2 mb-8">
                                <div>
                                    <span class="text-xs text-white/50 font-bold uppercase tracking-widest block mb-1">Mulai dari</span>
                                    <h2 class="font-display font-bold text-5xl text-white whitespace-nowrap">
                                        <?php echo $dest['price'] > 0 ? 'Rp ' . number_format($dest['price'], 0, ',', '.') : 'Gratis'; ?>
                                    </h2>
                                </div>
                                <?php if($dest['price'] > 0): ?>
                                    <span class="text-white/40 mb-2">/ orang</span>
                                <?php endif; ?>
                            </div>

                            <hr class="border-white/10 mb-8">

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($_SESSION['role'] == 'user'): ?>
                                    <a href="booking.php?id=<?php echo $dest['id']; ?>" class="block w-full text-center py-5 bg-secondary hover:bg-white text-black font-bold rounded-2xl transition-all duration-300 shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)] transform hover:-translate-y-1">
                                        Pesan Tiket Sekarang
                                    </a>
                                <?php else: ?>
                                    <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 p-4 rounded-xl text-center font-medium">
                                        Login as Admin
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="auth/login.php" class="block w-full text-center py-5 bg-white/10 text-white font-bold rounded-2xl hover:bg-white hover:text-black transition-all duration-300">
                                    Login untuk Memesan
                                </a>
                            <?php endif; ?>

                            <div class="mt-8 space-y-4">
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    <i class="bi bi-check-circle text-secondary"></i>
                                    <span>Konfirmasi Instan</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    <i class="bi bi-check-circle text-secondary"></i>
                                    <span>Bebas Pilih Tanggal</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    <i class="bi bi-shield-check text-secondary"></i>
                                    <span>Pembayaran Terenkripsi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-secondary/10 backdrop-blur-md rounded-3xl p-6 border border-secondary/20 group hover:bg-secondary/20 transition-all">
                        <h5 class="font-bold text-white mb-3 flex items-center gap-3">
                            <i class="bi bi-info-circle-fill text-secondary"></i> 
                            Info Penting
                        </h5>
                        <p class="text-sm text-slate-300 leading-relaxed group-hover:text-white transition-colors">
                            Pastikan datang 30 menit sebelum jam operasional berakhir untuk pengalaman maksimal bagi Anda dan keluarga.
                        </p>
                    </div>
                    
                    <!-- Back Button -->
                    <a href="index.php#destinations" class="flex items-center justify-center gap-2 text-white/40 hover:text-white transition-colors py-4 text-sm font-medium">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Destinasi
                    </a>
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
            duration: 1,
            stagger: 0.1,
            ease: 'power4.out',
            scrollTrigger: {
                trigger: '.gsap-reveal',
                start: 'top 90%',
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
