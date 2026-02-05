<?php
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<div class="bg-black min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <img src="assets/img/hero_bg.jpg" class="absolute inset-0 w-full h-full object-cover brightness-50" alt="Panduan Wisata">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black"></div>
        
        <div class="container mx-auto px-4 z-10 text-center gsap-reveal">
            <span class="inline-block py-1 px-4 rounded-full bg-secondary/20 border border-secondary/30 backdrop-blur-md text-secondary text-sm font-semibold mb-6 tracking-widest uppercase">
                Travel Guide
            </span>
            <h1 class="font-display font-medium text-5xl md:text-7xl text-white mb-6 leading-tight">
                Panduan <span class="italic font-serif text-secondary">Perjalanan</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto font-light">
                Tips and trik untuk pengalaman liburan tak terlupakan di Samarinda dan sekitarnya.
            </p>
        </div>
    </div>

    <!-- Content Section -->
        <!-- How to Get There Section -->
        <div class="mb-24 gsap-reveal">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary border border-secondary/20 font-bold">01</div>
                <h2 class="font-display font-medium text-3xl md:text-4xl text-white">Cara <span class="italic font-serif text-secondary">Menuju Ke Sini</span></h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Airport 1 -->
                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 hover:border-secondary/30 transition-all group">
                    <div class="flex items-center gap-4 mb-6">
                        <i class="bi bi-airplane-engines text-3xl text-secondary"></i>
                        <div>
                            <h4 class="text-white font-bold">Bandara SAMS Sepinggan (BPN)</h4>
                            <p class="text-xs text-slate-500">Balikpapan</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Gerbang utama internasional dan domestik menuju Kalimantan Timur. Memiliki koneksi penerbangan paling lengkap dari Jakarta (CGK), Surabaya (SUB), dan Makassar (UPG).
                    </p>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3 text-xs text-slate-300 bg-white/5 p-3 rounded-xl">
                            <i class="bi bi-bus-front text-secondary"></i>
                            <span>Bus DAMRI langsung ke Samarinda (±2.5 jam)</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300 bg-white/5 p-3 rounded-xl">
                            <i class="bi bi-car-front text-secondary"></i>
                            <span>Taksi "Travel" door-to-door tersedia 24 jam</span>
                        </div>
                    </div>
                </div>

                <!-- Airport 2 -->
                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 hover:border-secondary/30 transition-all group">
                    <div class="flex items-center gap-4 mb-6">
                        <i class="bi bi-airplane text-3xl text-secondary"></i>
                        <div>
                            <h4 class="text-white font-bold">Bandara APT Pranoto (AAP)</h4>
                            <p class="text-xs text-slate-500">Samarinda</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Pilihan paling efisien jika tujuan utama adalah kota Samarinda. Berjarak sekitar 45 menit dari pusat kota Samarinda.
                    </p>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3 text-xs text-slate-300 bg-white/5 p-3 rounded-xl">
                            <i class="bi bi-taxi-front text-secondary"></i>
                            <span>Taksi online (Grab/Gojek) tersedia luas</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300 bg-white/5 p-3 rounded-xl">
                            <i class="bi bi-geo-alt text-secondary"></i>
                            <span>Dekat dengan akses wisata Budaya Pampang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Itinerary Section -->
        <div class="mb-24 gsap-reveal">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary border border-secondary/20 font-bold">02</div>
                <h2 class="font-display font-medium text-3xl md:text-4xl text-white">Rencana <span class="italic font-serif text-secondary">Perjalanan</span></h2>
            </div>

            <div class="space-y-6">
                <!-- Itinerary 1 -->
                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden group">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/3 h-64 md:h-auto relative overflow-hidden">
                            <img src="assets/img/kutai nation park.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute inset-0 p-8 flex flex-col justify-end">
                                <span class="bg-secondary text-black text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full w-fit mb-2 text-center">3 Hari 2 Malam</span>
                                <h4 class="text-white font-bold text-xl">Wild Nature Adventure</h4>
                            </div>
                        </div>
                        <div class="flex-1 p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <span class="text-secondary text-[10px] font-bold uppercase tracking-widest block mb-2">Hari 01</span>
                                    <p class="text-slate-300 text-sm">Kedatangan di Samarinda, Check-in & Makan malam di Tepian Mahakam.</p>
                                </div>
                                <div>
                                    <span class="text-secondary text-[10px] font-bold uppercase tracking-widest block mb-2">Hari 02</span>
                                    <p class="text-slate-300 text-sm">Full day trip ke Taman Nasional Kutai, bertemu Orangutan liar.</p>
                                </div>
                                <div>
                                    <span class="text-secondary text-[10px] font-bold uppercase tracking-widest block mb-2">Hari 03</span>
                                    <p class="text-slate-300 text-sm">Susur Sungai Mahakam & Belanja buah tangan di Citra Niaga.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary 2 -->
                <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden group">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/3 h-64 md:h-auto relative overflow-hidden">
                            <img src="assets/img/enggang.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute inset-0 p-8 flex flex-col justify-end">
                                <span class="bg-secondary text-black text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full w-fit mb-2 text-center">2 Hari 1 Malam</span>
                                <h4 class="text-white font-bold text-xl">Cultural Heritage Tour</h4>
                            </div>
                        </div>
                        <div class="flex-1 p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <span class="text-secondary text-[10px] font-bold uppercase tracking-widest block mb-2">Hari 01</span>
                                    <p class="text-slate-300 text-sm">Desa Budaya Pampang (Minggu), Ritual Tari Dayak & Kerajinan tangan.</p>
                                </div>
                                <div>
                                    <span class="text-secondary text-[10px] font-bold uppercase tracking-widest block mb-2">Hari 02</span>
                                    <p class="text-slate-300 text-sm">Masjid Islamic Center (Salah satu termegah di Asia Tenggara) & Museum.</p>
                                </div>
                                <div class="flex items-center text-slate-500 italic text-sm">
                                    Cocok untuk akhir pekan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24">
            
            <!-- Best Time to Visit -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.1s">
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6">
                    <i class="bi bi-brightness-high text-3xl"></i>
                </div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">Waktu Terbaik</h3>
                <p class="text-slate-400 leading-relaxed font-light">
                    Dianjurkan untuk mengunjungi Samarinda antara bulan **Mei hingga September** untuk mendapatkan cuaca cerah terbaik. Hindari puncak musim hujan di bulan Desember agar petualangan alam Anda tetap maksimal tanpa kendala cuaca.
                </p>
            </div>

            <!-- What to Bring -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.2s">
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6">
                    <i class="bi bi-briefcase text-3xl"></i>
                </div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">Perlengkapan</h3>
                <p class="text-slate-400 leading-relaxed font-light">
                    Siapkan pakaian ringan yang menyerap keringat, tabir surya, topi, dan kamera terbaik Anda. Jika berencana menjelajah alam liar, jangan lupa membawa cairan anti-serangga dan sepatu khusus lintas alam yang nyaman.
                </p>
            </div>

            <!-- Transportation -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.3s">
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6">
                    <i class="bi bi-car-front text-3xl"></i>
                </div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">Transportasi</h3>
                <p class="text-slate-400 leading-relaxed font-light">
                    Gunakan aplikasi transportasi online (Gojek/Grab) untuk mobilitas cepat di dalam kota. Untuk perjalanan ke luar kota, menyewa mobil dengan supir lokal sangat direkomendasikan.
                </p>
            </div>

            <!-- Local Etiquette -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.4s">
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6">
                    <i class="bi bi-people text-3xl"></i>
                </div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">Etika Lokal</h3>
                <p class="text-slate-400 leading-relaxed font-light">
                    Masyarakat Samarinda sangat ramah. Selalu gunakan tangan kanan saat memberi atau menerima, dan mintalah izin sebelum mengambil foto masyarakat lokal di desa budaya.
                </p>
            </div>

            <!-- Safety Tips -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.5s">
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6">
                    <i class="bi bi-shield-check text-3xl"></i>
                </div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">Keamanan</h3>
                <p class="text-slate-400 leading-relaxed font-light">
                    Pastikan Anda selalu membawa botol minum untuk menghindari dehidrasi. Ikuti instruksi petugas di lokasi wisata alam, terutama saat berada di area konservasi atau perairan.
                </p>
            </div>

            <!-- Culinary Guide -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 gsap-reveal" style="animation-delay: 0.6s">
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6">
                    <i class="bi bi-egg-fried text-3xl"></i>
                </div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">Kuliner</h3>
                <p class="text-slate-400 leading-relaxed font-light">
                    Wajib mencoba Amplang (kerupuk ikan khas), Nasi Kuning Samarinda, dan berbagai olahan Ikan Patin. Kawasan Tepian Mahakam adalah tempat terbaik untuk jajan sore hari.
                </p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-20 bg-secondary/10 border border-secondary/20 rounded-[3rem] p-10 md:p-16 text-center gsap-reveal">
            <h2 class="font-display font-medium text-3xl md:text-5xl text-white mb-6">Masih Butuh Bantuan?</h2>
            <p class="text-slate-400 max-w-xl mx-auto mb-10 font-light">
                Tim support kami siap membantu merencanakan itinerary perjalanan Anda secara gratis.
            </p>
            <a href="https://wa.me/628111111111" class="inline-flex items-center gap-3 px-10 py-4 bg-secondary text-black font-bold rounded-full hover:bg-white transition-all duration-300">
                <i class="bi bi-whatsapp"></i>
                Hubungi Support
            </a>
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
