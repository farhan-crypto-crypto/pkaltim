<?php
session_start();
require_once '../config/database.php';

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Handle Reply Submission
if (isset($_POST['submit_reply'])) {
    $review_id = $_POST['review_id'];
    $reply_content = $_POST['reply_content'];
    
    try {
        $stmt = $pdo->prepare("UPDATE reviews SET reply = ?, reply_at = NOW() WHERE id = ?");
        $stmt->execute([$reply_content, $review_id]);
        $success_msg = "Balasan berhasil dikirim!";
    } catch (PDOException $e) {
        $error_msg = "Gagal mengirim balasan.";
    }
}

// Handle Delete Review
if (isset($_POST['delete_review'])) {
    $review_id = $_POST['review_id'];
    $dest_id = $_POST['destination_id'];
    
    try {
        // Get image path for deletion
        $stmt = $pdo->prepare("SELECT image FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        $review = $stmt->fetch();
        
        if ($review && $review['image'] && file_exists('../' . $review['image'])) {
            unlink('../' . $review['image']);
        }

        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        
        // Recalculate average rating for the destination
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE destination_id = ?");
        $stmt->execute([$dest_id]);
        $avg_rating = $stmt->fetch()['avg_rating'];
        $avg_rating = $avg_rating ? round($avg_rating, 1) : 0;
        
        $stmt = $pdo->prepare("UPDATE destinations SET rating = ? WHERE id = ?");
        $stmt->execute([$avg_rating, $dest_id]);
        
        $success_msg = "Ulasan berhasil dihapus!";
    } catch (PDOException $e) {
        $error_msg = "Gagal menghapus ulasan.";
    }
}

// Fetch Stats
try {
    $total_reviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    $pending_replies = $pdo->query("SELECT COUNT(*) FROM reviews WHERE reply IS NULL OR reply = ''")->fetchColumn();
    $avg_rating = $pdo->query("SELECT AVG(rating) FROM reviews")->fetchColumn();
    $avg_rating = $avg_rating ? round($avg_rating, 1) : 0;
} catch (PDOException $e) {
    $total_reviews = 0; $pending_replies = 0; $avg_rating = 0;
}

// Fetch all reviews
$stmt = $pdo->query("
    SELECT r.*, u.name as user_name, d.name as dest_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN destinations d ON r.destination_id = d.id 
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = "Manajemen Ulasan";
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
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #444; }
    </style>
</head>
<body class="bg-primary font-sans antialiased text-slate-200">

<div class="flex h-screen overflow-hidden" x-data="{ 
    searchQuery: '', 
    ratingFilter: 'all',
    reviews: <?php echo htmlspecialchars(json_encode($reviews), ENT_QUOTES, 'UTF-8'); ?>,
    get filteredReviews() {
        return this.reviews.filter(r => {
            const matchesSearch = r.user_name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                 r.dest_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                 r.comment.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesRating = this.ratingFilter === 'all' || r.rating == this.ratingFilter;
            return matchesSearch && matchesRating;
        })
    }
}">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto bg-primary relative">
        <?php include 'includes/header.php'; ?>

        <main class="p-8 max-w-7xl mx-auto">
            <!-- Page Header & Stats -->
            <div class="mb-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8 gsap-reveal">
                <div>
                    <h2 class="text-3xl font-display font-bold text-white mb-2">Kelola <span class="text-secondary italic font-serif">Ulasan</span></h2>
                    <p class="text-slate-500 text-sm">Moderasi pengalaman dan masukan dari pengunjung secara real-time.</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex gap-4">
                    <div class="glass-card px-6 py-4 rounded-3xl flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary border border-secondary/20">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-display font-bold text-white leading-none"><?php echo $total_reviews; ?></p>
                            <p class="text-[9px] uppercase font-bold text-slate-500 tracking-widest mt-1">Total</p>
                        </div>
                    </div>
                    <div class="glass-card px-6 py-4 rounded-3xl flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500 border border-orange-500/20">
                            <i class="bi bi-reply"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-display font-bold text-white leading-none"><?php echo $pending_replies; ?></p>
                            <p class="text-[9px] uppercase font-bold text-slate-500 tracking-widest mt-1">Tertunda</p>
                        </div>
                    </div>
                    <div class="glass-card px-6 py-4 rounded-3xl flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 border border-emerald-500/20">
                            <i class="bi bi-star"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-display font-bold text-white leading-none"><?php echo $avg_rating; ?></p>
                            <p class="text-[9px] uppercase font-bold text-slate-500 tracking-widest mt-1">Avg Rating</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($success_msg)): ?>
                <div class="mb-8 p-5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-[1.5rem] flex items-center gap-3 gsap-reveal shadow-lg">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <span class="font-medium text-sm"><?php echo $success_msg; ?></span>
                </div>
            <?php endif; ?>

            <!-- Controls -->
            <div class="mb-8 flex flex-col md:flex-row gap-4 gsap-reveal" style="animation-delay: 0.1s">
                <div class="relative flex-1 group">
                    <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-secondary transition-colors"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari pengunjung, lokasi, atau isi ulasan..." 
                           class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-white placeholder-slate-600 focus:outline-none focus:border-secondary/50 transition-all">
                </div>
                <div class="relative min-w-[200px]">
                    <i class="bi bi-filter absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <select x-model="ratingFilter" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-secondary/50 appearance-none transition-all">
                        <option value="all" class="bg-dark">Semua Rating</option>
                        <option value="5" class="bg-dark">⭐⭐⭐⭐⭐ 5 Stars</option>
                        <option value="4" class="bg-dark">⭐⭐⭐⭐ 4 Stars</option>
                        <option value="3" class="bg-dark">⭐⭐⭐ 3 Stars</option>
                        <option value="2" class="bg-dark">⭐⭐ 2 Stars</option>
                        <option value="1" class="bg-dark">⭐ 1 Star</option>
                    </select>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="glass-card rounded-[2.5rem] overflow-hidden gsap-reveal shadow-2xl" style="animation-delay: 0.2s">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-500 border-b border-white/5">Pengunjung & Lokasi</th>
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-500 border-b border-white/5">Rating</th>
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-500 border-b border-white/5">Ulasan</th>
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-500 border-b border-white/5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <template x-if="filteredReviews.length === 0">
                                <tr>
                                    <td colspan="4" class="px-8 py-24 text-center">
                                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-700">
                                            <i class="bi bi-chat-dots text-3xl"></i>
                                        </div>
                                        <h4 class="text-white font-bold text-lg mb-1">Ulasan tidak ditemukan</h4>
                                        <p class="text-slate-500 text-sm">Coba ubah kata kunci atau filter pencarian Anda.</p>
                                    </td>
                                </tr>
                            </template>
                            
                            <template x-for="r in filteredReviews" :key="r.id">
                                <tr class="hover:bg-white/[0.01] transition-all duration-300 group">
                                    <td class="px-8 py-8">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-secondary/20 to-secondary/5 flex items-center justify-center text-secondary font-display font-bold text-lg border border-secondary/20 shadow-inner">
                                                <span x-text="r.user_name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <p class="text-white font-bold text-base mb-1" x-text="r.user_name"></p>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest bg-white/5 px-2 py-0.5 rounded" x-text="r.dest_name"></span>
                                                    <span class="text-[10px] text-slate-600 tracking-tight" x-text="new Date(r.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'})"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex text-secondary text-[10px] gap-0.5">
                                                <template x-for="i in 5">
                                                    <i class="bi" :class="i <= r.rating ? 'bi-star-fill' : 'bi-star'"></i>
                                                </template>
                                            </div>
                                            <span class="bg-secondary/10 text-secondary text-[10px] px-2 py-0.5 rounded-full font-bold inline-flex items-center justify-center w-fit border border-secondary/20" x-text="r.rating + '.0'"></span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8 max-w-md">
                                        <div class="relative">
                                            <p class="text-slate-400 text-sm leading-relaxed mb-4 line-clamp-3 italic" x-text="'\u0022' + r.comment + '\u0022'"></p>
                                            
                                            <!-- Visitor Image Thumbnail -->
                                            <template x-if="r.image">
                                                <div class="relative group/thumb w-24 h-16 rounded-xl overflow-hidden border border-white/10 mb-4 cursor-pointer" @click="window.open('../' + r.image, '_blank')">
                                                    <img :src="'../' + r.image" class="w-full h-full object-cover transform group-hover/thumb:scale-110 transition-duration-500">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/thumb:opacity-100 transition-opacity flex items-center justify-center">
                                                        <i class="bi bi-zoom-in text-white text-sm"></i>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Reply Tag -->
                                            <template x-if="r.reply">
                                                <div class="mt-4 flex items-center gap-2 text-emerald-500 bg-emerald-500/10 px-3 py-1.5 rounded-lg w-fit border border-emerald-500/20">
                                                    <i class="bi bi-check-circle-fill text-xs"></i>
                                                    <span class="text-[10px] font-bold uppercase tracking-widest">Sudah Dibalas</span>
                                                </div>
                                            </template>
                                            <template x-if="!r.reply">
                                                <div class="mt-4 flex items-center gap-2 text-slate-500 bg-white/5 px-3 py-1.5 rounded-lg w-fit border border-white/5">
                                                    <i class="bi bi-clock-history text-xs"></i>
                                                    <span class="text-[10px] font-bold uppercase tracking-widest">Belum Dibalas</span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8 text-right">
                                        <div class="flex items-center gap-3 justify-end">
                                            <button @click="openReplyModal(r.id, r.reply || '')" 
                                                    class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center hover:bg-secondary hover:text-black transition-all shadow-lg hover:rotate-[10deg]"
                                                    title="Balas Ulasan">
                                                <i class="bi bi-reply-fill text-xl"></i>
                                            </button>
                                            <form method="POST" @submit.prevent="if(confirm('Hapus ulasan ini secara permanen?')) $el.submit()">
                                                <input type="hidden" name="review_id" :value="r.id">
                                                <input type="hidden" name="destination_id" :value="r.destination_id">
                                                <button type="submit" name="delete_review" 
                                                        class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg hover:rotate-[-10deg]">
                                                    <i class="bi bi-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen px-4 p-6">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-xl" onclick="closeReplyModal()"></div>
        <div class="bg-dark border border-white/10 rounded-[2.5rem] w-full max-w-xl p-10 relative z-10 shadow-2xl transform scale-95 transition-transform duration-300" id="replyModalContent">
            
            <button onclick="closeReplyModal()" class="absolute top-8 right-8 text-slate-500 hover:text-white transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>

            <div class="mb-10">
                <span class="text-secondary font-bold tracking-[0.3em] text-[10px] uppercase mb-2 block">Moderator Panel</span>
                <h3 class="text-3xl font-bold text-white font-display">Balas <span class="text-secondary italic font-serif">Ulasan</span></h3>
                <p class="text-slate-500 text-sm mt-2">Dapatkan loyalitas dengan memberikan respon yang hangat dan informatif.</p>
            </div>

            <form method="POST">
                <input type="hidden" name="review_id" id="modalReviewId">
                <div class="mb-8">
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-4">Isi Pesan Balasan</label>
                    <textarea name="reply_content" id="modalReplyContent" required rows="6" 
                              class="w-full bg-white/5 border border-white/10 rounded-[2rem] p-6 text-white focus:outline-none focus:border-secondary transition-all placeholder-slate-700 leading-relaxed shadow-inner" 
                              placeholder="Halo! Terima kasih atas ulasannya..."></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="closeReplyModal()" class="py-4 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:bg-white/10 transition-all font-bold tracking-wide">Batal</button>
                    <button type="submit" name="submit_reply" class="py-4 rounded-2xl bg-secondary text-black hover:bg-white transition-all font-bold tracking-wide shadow-xl shadow-secondary/20">Kirim Respon</button>
                </div>
            </form>
        </div>
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

    function openReplyModal(id, currentReply) {
        document.getElementById('modalReviewId').value = id;
        document.getElementById('modalReplyContent').value = currentReply;
        
        const modal = document.getElementById('replyModal');
        const content = document.getElementById('replyModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeReplyModal() {
        const modal = document.getElementById('replyModal');
        const content = document.getElementById('replyModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

</body>
</html>
