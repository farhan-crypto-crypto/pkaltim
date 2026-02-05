<?php
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<div class="bg-black min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
        <img src="assets/img/hero_kalimantan.png" class="absolute inset-0 w-full h-full object-cover brightness-[0.3]" alt="Tentang PesutTrip">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black"></div>
        
        <div class="container mx-auto px-4 z-10 text-center gsap-reveal">
            <span class="inline-block py-1 px-4 rounded-full bg-secondary/20 border border-secondary/30 backdrop-blur-md text-secondary text-sm font-semibold mb-6 tracking-widest uppercase">
                Our Story
            </span>
            <h1 class="font-display font-medium text-5xl md:text-8xl text-white mb-8 leading-tight">
                Mengenal <span class="italic font-serif text-secondary">PesutTrip</span>
            </h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed">
                Platform reservasi pariwisata terpercaya yang didedikasikan untuk memperkenalkan keindahan tersembunyi Kalimantan Timur kepada dunia.
            </p>
        </div>
    </div>

    <!-- Mission & Vision Section -->
    <div class="container mx-auto px-4 lg:px-8 py-24 border-t border-white/5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center mb-32">
            <div class="gsap-reveal">
                <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-4 block">Visi Kami</span>
                <h2 class="font-display font-medium text-4xl md:text-5xl text-white mb-8 leading-tight">
                    Menjadi Pintu Gerbang Utama Pariwisata <span class="italic font-serif text-secondary">Borneo.</span>
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed font-light mb-8">
                Kami percaya bahwa Kalimantan Timur menyimpan potensi destinasi kelas dunia yang belum terjamah sepenuhnya. Misi PesutTrip adalah menjadi jembatan bagi setiap pelancong untuk mengeksplorasi keajaiban alam dan kedalaman budaya Dayak dengan standar kenyamanan dan keamanan terbaik.
                </p>
                <div class="flex flex-col gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <p class="text-slate-300"><span class="text-white font-bold">Terverifikasi:</span> Seluruh destinasi di platform kami telah melalui proses kurasi ketat.</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                            <i class="bi bi-heart"></i>
                        </div>
                        <p class="text-slate-300"><span class="text-white font-bold">Lokal:</span> Kami bekerja sama langsung dengan pemandu dan UMKM lokal.</p>
                    </div>
                </div>
            </div>
            
            <div class="relative gsap-reveal" style="animation-delay: 0.2s">
                <div class="aspect-square rounded-[3rem] overflow-hidden border border-white/10 p-3">
                    <img src="assets/img/feature_kalimantan.png" class="w-full h-full object-cover rounded-[2.5rem] opacity-80" alt="Kalimantan Nature">
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-secondary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-secondary/5 rounded-full blur-[100px]"></div>
            </div>
        </div>

        <!-- Core Values -->
        <div class="text-center mb-16 gsap-reveal">
            <h2 class="font-display font-medium text-4xl text-white mb-4">Nilai-Nilai <span class="italic font-serif text-secondary">Fundamental</span></h2>
            <p class="text-slate-400 max-w-xl mx-auto font-light">Prinsip utama yang menjadikan PesutTrip sebagai partner perjalanan terpercaya Anda di Samarinda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-10 text-center hover:border-secondary/30 transition-all group gsap-reveal" style="animation-delay: 0.1s">
                <i class="bi bi-shield-lock text-5xl text-secondary mb-6 block group-hover:scale-110 transition-transform"></i>
                <h4 class="text-xl text-white font-bold mb-4">Kepercayaan</h4>
                <p class="text-slate-400 font-light text-sm leading-relaxed">Sistem pembayaran yang aman dan transparansi harga tanpa biaya tersembunyi.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-10 text-center hover:border-secondary/30 transition-all group gsap-reveal" style="animation-delay: 0.2s">
                <i class="bi bi-sustainable text-5xl text-secondary mb-6 block group-hover:scale-110 transition-transform"></i>
                <h4 class="text-xl text-white font-bold mb-4">Keberlanjutan</h4>
                <p class="text-slate-400 font-light text-sm leading-relaxed">Mendukung ekowisata yang menjaga kelestarian alam dan kesejahteraan warga lokal.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-10 text-center hover:border-secondary/30 transition-all group gsap-reveal" style="animation-delay: 0.3s">
                <i class="bi bi-award text-5xl text-secondary mb-6 block group-hover:scale-110 transition-transform"></i>
                <h4 class="text-xl text-white font-bold mb-4">Kualitas Premium</h4>
                <p class="text-slate-400 font-light text-sm leading-relaxed">Memberikan pelayanan terbaik mulai dari pemilihan destinasi hingga dukungan pelanggan.</p>
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
