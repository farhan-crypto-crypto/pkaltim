<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch featured destinations (random 6 for variety)
try {
    $stmt = $pdo->query("SELECT * FROM destinations ORDER BY RAND() LIMIT 6");
    $destinations = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- Hero Section -->
<section class="relative h-screen min-h-[700px] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="assets/img/enggang.jpg" 
             class="w-full h-full object-cover brightness-105 contrast-110 saturate-[1.1]" alt="Enggang Kalimantan">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/80"></div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 z-10 text-center relative mt-[-100px]">
        <span class="inline-block py-1 px-4 rounded-full bg-white/5 border border-white/10 backdrop-blur-md text-secondary text-sm font-semibold mb-6 tracking-widest uppercase fade-in-up">
            The Heart of Borneo
        </span>
        <h1 class="font-display font-medium text-6xl md:text-8xl text-white mb-8 leading-tight tracking-tight fade-in-up" style="animation-delay: 0.1s">
            Ayo ke <span class="italic font-serif text-secondary">Kalimantan Timur,</span> <br>
            Mau ke Mana?
        </h1>
        <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto mb-12 font-light tracking-wide fade-in-up" style="animation-delay: 0.2s">
            Jelajahi keajaiban hutan hujan tropis yang megah, selami autentisitas budaya Dayak, dan rasakan kehangatan keramahan Samarinda melalui kacamata warga lokal.
        </p>
    </div>

    <!-- Search / Filter Bar (Floating) -->
    <div class="absolute bottom-12 left-0 w-full px-4 z-20 fade-in-up" style="animation-delay: 0.4s">
        <div class="container mx-auto max-w-5xl">
            <form action="search.php" method="GET" class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-full p-2 flex flex-col md:flex-row items-center justify-between gap-2 shadow-2xl shadow-black/50 ring-1 ring-white/5">
                
                <!-- Location Input -->
                <div class="flex-1 w-full px-6 py-3 relative group">
                    <label class="block text-xs uppercase text-secondary tracking-widest font-bold mb-1">Destinasi</label>
                    <div class="flex items-center gap-3 text-white">
                        <i class="bi bi-geo-alt text-lg text-slate-400 group-focus-within:text-secondary transition-colors"></i>
                        <input type="text" name="location" placeholder="Cari nama tempat atau kecamatan..." class="bg-transparent border-none outline-none w-full placeholder-slate-500 font-medium text-white focus:ring-0 p-0 text-base">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-secondary hover:bg-white text-black font-bold px-8 py-4 rounded-full transition-all duration-300 w-full md:w-auto shadow-[0_0_15px_rgba(212,175,55,0.4)] hover:shadow-[0_0_25px_rgba(255,255,255,0.4)] flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i>
                    <span>Cari</span>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-10 mt-20 relative z-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto">
            <div class="bg-slate-900/80 backdrop-blur-xl border border-white/10 p-6 rounded-2xl text-center text-white shadow-2xl hover:-translate-y-1 transition-transform duration-300">
                <i class="bi bi-geo-alt text-3xl text-secondary mb-2 block"></i>
                <span class="font-bold text-2xl block">20+</span>
                <span class="text-sm text-slate-300">Destinasi Wisata</span>
            </div>
            <div class="bg-slate-900/80 backdrop-blur-xl border border-white/10 p-6 rounded-2xl text-center text-white shadow-2xl hover:-translate-y-1 transition-transform duration-300">
                <i class="bi bi-star text-3xl text-secondary mb-2 block"></i>
                <span class="font-bold text-2xl block">4.8</span>
                <span class="text-sm text-slate-300">Rating Rata-rata</span>
            </div>
        </div>
    </div>
</section>

<!-- First Timer Welcome Section -->
<section class="py-24 bg-black relative overflow-hidden">
    <div class="absolute top-1/2 left-0 -translate-y-1/2 w-64 h-64 bg-secondary/5 rounded-full blur-[100px]"></div>
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2 gsap-reveal">
                <span class="text-secondary font-bold tracking-[0.3em] text-xs uppercase mb-4 block">Welcome to East Borneo</span>
                <h2 class="font-display font-medium text-4xl md:text-6xl text-white mb-8 leading-tight">
                    Baru Pertama Kali di <br> <span class="italic font-serif text-secondary text-5xl md:text-7xl">Kalimantan Timur?</span>
                </h2>
                <p class="text-slate-300 text-lg mb-8 font-light leading-relaxed">
                    Kami memahami bahwa merencanakan perjalanan ke pulau terbesar di Indonesia bisa menjadi tantangan tersendiri. Dari rimbunnya hutan hujan tropis hingga warisan budaya Dayak yang mendalam, tim kami siap memandu setiap langkah petualangan Anda.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                            <i class="bi bi-check2"></i>
                        </div>
                        <span class="text-slate-200 font-medium">Panduan Lengkap</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                            <i class="bi bi-check2"></i>
                        </div>
                        <span class="text-slate-200 font-medium">Bantuan Itinerary</span>
                    </div>
                </div>
                <a href="guide.php" class="inline-flex items-center gap-3 text-secondary font-bold hover:gap-5 transition-all duration-300 group">
                    Baca Panduan Perjalanan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="lg:w-1/2 grid grid-cols-2 gap-4 gsap-reveal">
                <div class="pt-12">
                    <img src="assets/img/hero_bg.jpg" class="rounded-[2rem] w-full h-[350px] object-cover border border-white/10 hover:border-secondary transition-colors duration-500 shadow-2xl">
                </div>
                <div>
                    <img src="assets/img/enggang.jpg" class="rounded-[2rem] w-full h-[350px] object-cover border border-white/10 hover:border-secondary transition-colors duration-500 shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Planning Workflow Section -->
<section class="py-24 bg-dark relative border-t border-white/5">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-20 gsap-reveal">
            <h2 class="font-display font-medium text-4xl md:text-5xl text-white mb-6">Rencanakan Petualangan Anda</h2>
            <p class="text-slate-400 font-light">Tiga langkah mudah untuk memulai journey tak terlupakan Anda di Samarinda.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
            <!-- connecting line -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-y-[100px]"></div>
            
            <!-- Step 1 -->
            <div class="text-center group gsap-reveal">
                <div class="w-24 h-24 rounded-[2rem] bg-white/5 border border-white/10 flex items-center justify-center text-4xl text-secondary mx-auto mb-8 group-hover:bg-secondary group-hover:text-black transition-all duration-500 shadow-xl group-hover:rotate-[10deg]">
                    <i class="bi bi-map"></i>
                </div>
                <h4 class="text-white font-bold text-xl mb-4">Pilih Destinasi</h4>
                <p class="text-slate-400 text-sm font-light">Telusuri puluhan tempat wisata mulai dari air terjun hingga desa budaya.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center group gsap-reveal" style="animation-delay: 0.2s">
                <div class="w-24 h-24 rounded-[2rem] bg-white/5 border border-white/10 flex items-center justify-center text-4xl text-secondary mx-auto mb-8 group-hover:bg-secondary group-hover:text-black transition-all duration-500 shadow-xl group-hover:rotate-[-10deg]">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h4 class="text-white font-bold text-xl mb-4">Atur Jadwal</h4>
                <p class="text-slate-400 text-sm font-light">Pesan tiket secara online dan atur waktu kunjungan sesuai keinginan Anda.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center group gsap-reveal" style="animation-delay: 0.4s">
                <div class="w-24 h-24 rounded-[2rem] bg-white/5 border border-white/10 flex items-center justify-center text-4xl text-secondary mx-auto mb-8 group-hover:bg-secondary group-hover:text-black transition-all duration-500 shadow-xl group-hover:rotate-[15deg]">
                    <i class="bi bi-qr-code-scan"></i>
                </div>
                <h4 class="text-white font-bold text-xl mb-4">Mulai Perjalanan</h4>
                <p class="text-slate-400 text-sm font-light">Terima E-Tiket instan dan siap untuk menjelajahi keindahan Kalimantan!</p>
            </div>
        </div>
    </div>
</section>

<!-- Destination Cities Section (Moved & Refactored) -->
<section id="cities" class="py-24 bg-dark text-white relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-secondary/5 rounded-full blur-[120px] pointer-events-none"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <!-- Left Content: City List -->
            <div class="order-2 lg:order-1 gsap-reveal">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-4 block">Explore the Hubs</span>
                <h2 class="font-display font-medium text-4xl lg:text-5xl mb-12 leading-tight">
                    Kota-Kota <br> <span class="italic font-serif text-slate-400">Destinasi Utama</span>
                </h2>

                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-8 top-0 bottom-0 w-px bg-gradient-to-b from-secondary/50 to-transparent"></div>

                    <!-- Item 1: Samarinda -->
                    <div class="relative flex items-center gap-8 mb-12 group">
                        <div class="w-16 h-16 rounded-full bg-dark border border-secondary/30 flex items-center justify-center relative z-10 group-hover:scale-110 transition-transform duration-300 shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                            <i class="bi bi-buildings text-2xl text-secondary"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-xl mb-1 group-hover:text-secondary transition-colors">Samarinda</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Ibukota Kaltim, gerbang budaya Dayak dan eksotika Sungai Mahakam.</p>
                        </div>
                    </div>

                    <!-- Item 2: Balikpapan -->
                    <div class="relative flex items-center gap-8 mb-12 group">
                        <div class="w-16 h-16 rounded-full bg-dark border border-secondary/30 flex items-center justify-center relative z-10 group-hover:scale-110 transition-transform duration-300 shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                            <i class="bi bi-water text-2xl text-secondary"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-xl mb-1 group-hover:text-secondary transition-colors">Balikpapan</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Kota pesisir modern dengan pantai yang indah dan akses ke IKN.</p>
                        </div>
                    </div>

                    <!-- Item 3: Berau -->
                    <div class="relative flex items-center gap-8 group">
                        <div class="w-16 h-16 rounded-full bg-dark border border-secondary/30 flex items-center justify-center relative z-10 group-hover:scale-110 transition-transform duration-300 shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                            <i class="bi bi-tsunami text-2xl text-secondary"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-xl mb-1 group-hover:text-secondary transition-colors">Berau</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Gerbang surga tropis Kepulauan Derawan, Maratua, dan Kakaban.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Content: Visual -->
            <div class="relative order-1 lg:order-2 gsap-reveal">
                <div class="relative aspect-square">
                    <!-- Main Image (Circle) -->
                    <div class="absolute inset-0 rounded-full overflow-hidden border border-white/10 p-2">
                        <div class="w-full h-full rounded-full overflow-hidden relative">
                            <img src="assets/img/hero_kalimantan.png" 
                                 class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700 scale-110" alt="Landscape Kalimantan">
                            <div class="absolute inset-0 bg-secondary/10 mix-blend-overlay"></div>
                        </div>
                    </div>

                    <!-- Floating Badge -->
                    <div class="absolute bottom-10 left-0 bg-black/80 backdrop-blur-md border border-white/10 p-6 rounded-3xl max-w-[220px]">
                        <p class="text-xs text-slate-300 italic mb-2">"Kunjungi kota-kota epik di Kalimantan Timur dengan akses mudah dari PesutTrip."</p>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-secondary/20 flex items-center justify-center text-secondary">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <span class="text-[10px] text-white font-bold uppercase tracking-wider">East Borneo Gateway</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Destinations Grid -->
<section id="destinations" class="py-24 bg-black relative" x-data="{ activeCategory: 'Semua' }">
    <!-- Decorative background glow -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6 gsap-reveal">
            <div class="max-w-2xl">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-2 block">Available Tours</span>
                <h2 class="font-display font-medium text-4xl md:text-5xl text-white">Destinasi <br> <span class="italic font-serif text-secondary">Populer</span></h2>
            </div>
            
            <!-- Filter Tabs -->
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                <button 
                    @click="activeCategory = 'Semua'"
                    :class="activeCategory === 'Semua' ? 'bg-secondary text-black' : 'bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10'"
                    class="px-6 py-2 rounded-full font-semibold text-sm whitespace-nowrap transition-all duration-300 shadow-lg">
                    Semua
                </button>
                <button 
                    @click="activeCategory = 'Alam'"
                    :class="activeCategory === 'Alam' ? 'bg-secondary text-black' : 'bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10'"
                    class="px-6 py-2 rounded-full font-semibold text-sm whitespace-nowrap transition-all duration-300 shadow-lg">
                    Alam
                </button>
                <button 
                    @click="activeCategory = 'Budaya'"
                    :class="activeCategory === 'Budaya' ? 'bg-secondary text-black' : 'bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10'"
                    class="px-6 py-2 rounded-full font-semibold text-sm whitespace-nowrap transition-all duration-300 shadow-lg">
                    Budaya
                </button>
                <button 
                    @click="activeCategory = 'Kuliner'"
                    :class="activeCategory === 'Kuliner' ? 'bg-secondary text-black' : 'bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10'"
                    class="px-6 py-2 rounded-full font-semibold text-sm whitespace-nowrap transition-all duration-300 shadow-lg">
                    Kuliner
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($destinations as $dest): ?>
                <div 
                    x-show="activeCategory === 'Semua' || '<?php echo $dest['category']; ?>' === activeCategory"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
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
        
        <div class="text-center mt-16">
            <a href="destinations.php" class="inline-block px-10 py-4 bg-transparent border border-white/20 text-white font-semibold rounded-full hover:bg-white hover:text-black transition-all duration-300 tracking-wide">
                Lihat Semua Destinasi
            </a>
        </div>
    </div>
</section>


<!-- Call to Action -->
<section class="py-24 bg-black relative border-t border-white/5">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="font-display font-medium text-4xl lg:text-6xl text-white mb-8">Siap Menjelajahi <span class="text-secondary italic font-serif">Borneo?</span></h2>
            <p class="text-slate-400 text-lg mb-10 font-light">
                Jangan lewatkan kesempatan untuk melihat keajaiban alam yang sesungguhnya.
            </p>
            <a href="#destinations" class="inline-block px-12 py-4 bg-white text-black font-bold text-lg rounded-full hover:bg-secondary transition-all duration-300 hover:scale-105 shadow-[0_0_30px_rgba(255,255,255,0.2)]">
                Pesan Tiket Sekarang
            </a>
        </div>
    </div>
</section>

<!-- JS Scripts -->
<script src="assets/js/main.js"></script>

<?php require_once 'includes/footer.php'; ?>
