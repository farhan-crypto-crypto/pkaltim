<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$location = isset($_GET['location']) ? $_GET['location'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$guests = isset($_GET['guests']) ? $_GET['guests'] : 1;

try {
    // Basic search query (Name or Location)
    $sql = "SELECT * FROM destinations WHERE name LIKE ? OR location LIKE ?";
    $params = ["%$location%", "%$location%"];
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $destinations = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- Header Section (Compact) -->
<section class="bg-black pt-32 pb-12 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-black/80 to-black z-10"></div>
        <img src="assets/img/hero_kalimantan.png" class="w-full h-full object-cover opacity-30" alt="Background">
    </div>
    <div class="container mx-auto px-4 relative z-20 text-center">
        <h1 class="font-display font-bold text-3xl text-white mb-2">Hasil Pencarian</h1>
        <p class="text-slate-400">
            Menampilkan hasil untuk "<span class="text-secondary"><?php echo htmlspecialchars($location); ?></span>"
            <?php if($date): ?> pada tanggal <?php echo htmlspecialchars($date); ?><?php endif; ?>
        </p>
    </div>
</section>

<!-- Results Grid -->
<section class="py-12 bg-black min-h-screen relative">
    <div class="absolute top-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <?php if(count($destinations) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($destinations as $dest): ?>
                    <div class="group relative bg-white/5 border border-white/10 rounded-[2rem] overflow-hidden hover:border-secondary/50 transition-all duration-500">
                        <!-- Image Area (Tall) -->
                        <div class="relative aspect-[3/4] overflow-hidden">
                            <img src="<?php echo htmlspecialchars($dest['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($dest['name']); ?>" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 block">
                            
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                            
                            <!-- Price Tag (Floating) -->
                            <div class="absolute top-4 left-4 bg-black/40 backdrop-blur-md border border-white/10 text-white font-medium px-4 py-1.5 rounded-full text-xs">
                               Starting from <span class="text-secondary font-bold ml-1">Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?></span>
                            </div>

                            <!-- Content Overlay -->
                            <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                <!-- Rating -->
                                <div class="flex items-center gap-1 mb-2">
                                    <i class="bi bi-star-fill text-secondary text-xs"></i>
                                    <span class="text-white/90 text-xs font-medium"><?php echo $dest['rating']; ?></span>
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
        <?php else: ?>
            <div class="text-center py-20 bg-white/5 rounded-3xl border border-white/10">
                <i class="bi bi-search text-6xl text-slate-600 mb-6 inline-block"></i>
                <h3 class="text-2xl font-display font-bold text-white mb-2">Tidak ditemukan</h3>
                <p class="text-slate-400 max-w-md mx-auto mb-8">Maaf, kami tidak menemukan destinasi yang cocok dengan pencarian Anda. Coba kata kunci lain.</p>
                <a href="index.php" class="px-8 py-3 bg-secondary text-black font-bold rounded-full hover:bg-white transition-colors">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
