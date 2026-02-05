<footer class="bg-primary text-slate-300 pt-16 pb-8 border-t border-white/5 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
        <svg width="100%" height="100%">
            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Brand -->
            <div>
                <a href="index.php" class="flex items-center gap-2 mb-6">
                    <i class="bi bi-compass-fill text-2xl text-secondary"></i>
                    <span class="font-display font-bold text-2xl text-white">Pesut<span class="text-secondary">Trip</span></span>
                </a>
                <p class="text-sm leading-relaxed mb-6">
                    Platform promosi pariwisata premium untuk Kota Samarinda. 
                    Kami membantu Anda menemukan keindahan tersembunyi di Kota Tepian.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-secondary hover:text-primary transition-all duration-300">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-secondary hover:text-primary transition-all duration-300">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-secondary hover:text-primary transition-all duration-300">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-display font-bold text-white text-lg mb-6">Jelajahi</h4>
                <ul class="space-y-3">
                    <li><a href="index.php" class="hover:text-secondary transition-colors inline-block">Beranda</a></li>
                    <li><a href="index.php#destinations" class="hover:text-secondary transition-colors inline-block">Destinasi Populer</a></li>
                    <li><a href="guide.php" class="hover:text-secondary transition-colors inline-block">Panduan Wisata</a></li>
                    <li><a href="about.php" class="hover:text-secondary transition-colors inline-block">Tentang Kami</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-display font-bold text-white text-lg mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="bi bi-geo-alt-fill text-secondary mt-1"></i>
                        <span>Jl. Pahlawan No. 123, Samarinda, Kalimantan Timur</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-envelope-fill text-secondary"></i>
                        <span>pesuttrip@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-telephone-fill text-secondary"></i>
                        <span>+62 811 1111 111</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-display font-bold text-white text-lg mb-6">Berita Terbaru</h4>
                <p class="text-sm mb-4">Dapatkan info promo dan destinasi terbaru.</p>
                <form class="flex gap-2">
                    <input type="email" placeholder="Email Anda" class="w-full px-4 py-2 rounded-lg bg-white/5 border border-white/10 focus:border-secondary focus:outline-none transition-colors">
                    <button type="button" class="bg-secondary text-primary px-4 py-2 rounded-lg font-bold hover:bg-amber-400 transition-colors">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 text-center text-sm">
            <p>&copy; <?php echo date('Y'); ?> PesutTrip. Made with <i class="bi bi-heart-fill text-red-500 mx-1"></i> for You.</p>
        </div>
    </div>
</footer>

<!-- GSAP Animation Config -->
<script>
    gsap.registerPlugin(ScrollTrigger);

    // Global Reveal Animation
    const revealElements = document.querySelectorAll(".gsap-reveal");
    revealElements.forEach(element => {
        gsap.to(element, {
            scrollTrigger: {
                trigger: element,
                start: "top 85%",
                toggleActions: "play none none reverse"
            },
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power3.out"
        });
    });
</script>
</body>
</html>
