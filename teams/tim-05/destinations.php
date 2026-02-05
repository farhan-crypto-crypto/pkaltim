<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch ALL destinations
try {
    $stmt = $pdo->query("SELECT * FROM destinations ORDER BY name ASC");
    $destinations = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- Header Section -->
<section class="bg-black pt-32 pb-16 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-black/40 to-black/80 z-10"></div>
        <img src="assets/img/enggang.jpg" class="w-full h-full object-cover brightness-105 contrast-110 saturate-[1.1]" alt="Enggang Kalimantan">
    </div>
    <div class="container mx-auto px-4 relative z-20 text-center">
        <h1 class="font-display font-medium text-5xl md:text-6xl text-white mb-6">Semua <span class="text-secondary italic font-serif">Destinasi</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto font-light">
            Jelajahi seluruh koleksi tempat wisata indah di Samarinda yang telah kami kurasi khusus untuk Anda.
        </p>
    </div>
</section>

<!-- Destinations Grid -->
<section class="py-16 bg-black min-h-screen relative" x-data="{ searchQuery: '', activeCategory: 'Semua' }">
    <!-- Decorative background glow -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Search and Filter Bar -->
        <div class="mb-16 max-w-4xl mx-auto flex flex-col gap-6">
            <!-- Search Input -->
            <div class="relative group">
                <input type="text" x-model="searchQuery" placeholder="Cari destinasi wisata..." class="w-full pl-16 pr-8 py-5 rounded-full bg-white/5 border border-white/10 text-white placeholder-slate-500 shadow-lg focus:outline-none focus:border-secondary/50 focus:bg-white/10 transition-all">
                <i class="bi bi-search absolute left-6 top-1/2 transform -translate-y-1/2 text-slate-500 text-lg group-focus-within:text-secondary transition-colors"></i>
            </div>
            
            <!-- Category Pills -->
            <div class="flex justify-center gap-3 overflow-x-auto pb-2 scrollbar-hide">
                <template x-for="cat in ['Semua', 'Alam', 'Budaya', 'Kuliner']">
                    <button 
                        @click="activeCategory = cat"
                        :class="activeCategory === cat ? 'bg-secondary text-black' : 'bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10'"
                        class="px-8 py-2.5 rounded-full font-semibold text-sm whitespace-nowrap transition-all duration-300 shadow-md"
                        x-text="cat">
                    </button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($destinations as $dest): ?>
                <div 
                    x-show="(activeCategory === 'Semua' || '<?php echo $dest['category']; ?>' === activeCategory) && ('<?php echo addslashes(strtolower($dest['name'])); ?>'.includes(searchQuery.toLowerCase()))"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="group relative bg-white/5 border border-white/10 rounded-[2rem] overflow-hidden hover:border-secondary/50 transition-all duration-500">
                    <!-- Image Area (Tall) -->
                    <div class="relative aspect-[3/4] overflow-hidden">
                        <img src="<?php echo htmlspecialchars($dest['image']); ?>" 
                             alt="<?php echo htmlspecialchars($dest['name']); ?>" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 block">
                        
                        <!-- Overlay Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                        
                        <!-- Price Tag (Floating) -->
                        <div class="absolute top-4 left-4 bg-black/40 backdrop-blur-md border border-white/10 text-white font-medium px-4 py-1.5 rounded-full text-xs">
                           Start from <span class="text-secondary font-bold ml-1">Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?></span>
                        </div>

                        <!-- Content Overlay -->
                        <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                            <!-- Rating -->
                            <div class="flex items-center gap-2 mb-2">
                                <div class="relative flex text-white/30 text-[10px]">
                                    <?php for($i=0; $i<5; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                    <div class="absolute top-0 left-0 flex overflow-hidden text-secondary" style="width: <?php echo ($dest['rating'] / 5) * 100; ?>%">
                                        <?php for($i=0; $i<5; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                    </div>
                                </div>
                                <span class="text-white/90 text-xs font-medium"><?php echo number_format($dest['rating'], 1); ?></span>
                                <span class="text-white/40 text-xs">•</span>
                                <span class="text-white/70 text-xs"><?php echo htmlspecialchars($dest['location']); ?></span>
                            </div>

                            <h3 class="font-display font-bold text-xl text-white mb-4 leading-tight">
                                <?php echo htmlspecialchars($dest['name']); ?>
                            </h3>

                            <a href="detail.php?id=<?php echo $dest['id']; ?>" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white group-hover:bg-secondary group-hover:text-black group-hover:border-secondary transition-all duration-300">
                                <i class="bi bi-arrow-up-right text-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(empty($destinations)): ?>
            <div class="text-center py-20">
                <i class="bi bi-map text-6xl text-white/20 mb-4 inline-block"></i>
                <h3 class="text-xl font-bold text-slate-500">Belum ada destinasi yang tersedia.</h3>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
