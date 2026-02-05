<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<div class="w-72 bg-black border-r border-white/10 hidden md:flex flex-col h-full">
    <div class="p-8 border-b border-white/5">
        <a href="../index.php" class="flex items-center gap-2 group">
            <i class="bi bi-compass-fill text-3xl text-secondary transition-transform group-hover:rotate-45"></i>
            <span class="font-display font-bold text-2xl tracking-tight text-white">
                Pesut<span class="text-secondary">Trip</span>
            </span>
        </a>
        <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-500 mt-4 block">Navigation Menu</span>
    </div>
    
    <nav class="flex-1 p-6 space-y-2">
        <a href="dashboard.php" class="flex items-center gap-4 px-5 py-4 transition-all duration-300 rounded-2xl <?php echo $current_page == 'dashboard.php' ? 'bg-secondary text-primary shadow-[0_0_20px_rgba(212,175,55,0.2)]' : 'text-slate-400 hover:bg-white/5 hover:text-white'; ?>">
            <i class="bi bi-grid-fill text-xl"></i>
            <span class="font-<?php echo $current_page == 'dashboard.php' ? 'bold' : 'medium'; ?> tracking-wide">Dashboard</span>
        </a>
        <a href="destinations.php" class="flex items-center gap-4 px-5 py-4 transition-all duration-300 rounded-2xl <?php echo $current_page == 'destinations.php' ? 'bg-secondary text-primary shadow-[0_0_20px_rgba(212,175,55,0.2)]' : 'text-slate-400 hover:bg-white/5 hover:text-white'; ?>">
            <i class="bi bi-map text-xl"></i>
            <span class="font-<?php echo $current_page == 'destinations.php' ? 'bold' : 'medium'; ?> tracking-wide">Destinasi</span>
        </a>
        <a href="bookings.php" class="flex items-center gap-4 px-5 py-4 transition-all duration-300 rounded-2xl <?php echo $current_page == 'bookings.php' ? 'bg-secondary text-primary shadow-[0_0_20px_rgba(212,175,55,0.2)]' : 'text-slate-400 hover:bg-white/5 hover:text-white'; ?>">
            <i class="bi bi-calendar-check text-xl"></i>
            <span class="font-<?php echo $current_page == 'bookings.php' ? 'bold' : 'medium'; ?> tracking-wide">Pesanan</span>
        </a>
        <a href="reviews.php" class="flex items-center gap-4 px-5 py-4 transition-all duration-300 rounded-2xl <?php echo $current_page == 'reviews.php' ? 'bg-secondary text-primary shadow-[0_0_20px_rgba(212,175,55,0.2)]' : 'text-slate-400 hover:bg-white/5 hover:text-white'; ?>">
            <i class="bi bi-chat-left-text text-xl"></i>
            <span class="font-<?php echo $current_page == 'reviews.php' ? 'bold' : 'medium'; ?> tracking-wide">Ulasan</span>
        </a>
        <a href="users.php" class="flex items-center gap-4 px-5 py-4 transition-all duration-300 rounded-2xl <?php echo $current_page == 'users.php' ? 'bg-secondary text-primary shadow-[0_0_20px_rgba(212,175,55,0.2)]' : 'text-slate-400 hover:bg-white/5 hover:text-white'; ?>">
            <i class="bi bi-people text-xl"></i>
            <span class="font-<?php echo $current_page == 'users.php' ? 'bold' : 'medium'; ?> tracking-wide">Pengguna</span>
        </a>
    </nav>

    <div class="p-6 border-t border-white/5">
        <a href="../auth/logout.php" class="flex items-center gap-3 px-5 py-4 text-red-400 hover:bg-red-400/10 hover:text-red-300 rounded-2xl transition-all duration-300">
            <i class="bi bi-box-arrow-left text-xl"></i> 
            <span class="font-bold tracking-wide text-sm">Keluar</span>
        </a>
    </div>
</div>
