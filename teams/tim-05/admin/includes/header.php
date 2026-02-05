<header class="bg-black/80 backdrop-blur-md border-b border-white/5 p-6 flex justify-between items-center sticky top-0 z-30">
    <div class="flex items-center gap-4">
        <!-- Dashboard Breadcrumb etc could go here -->
        <h1 class="font-display font-bold text-2xl text-white"><?php echo $title ?? 'Admin Panel'; ?></h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden sm:flex flex-col items-end">
            <span class="text-sm font-bold text-white"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></span>
            <span class="text-[10px] uppercase text-secondary font-bold tracking-widest">Administrator</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary font-bold">
            <?php echo strtoupper(substr($_SESSION['name'] ?? 'A', 0, 1)); ?>
        </div>
    </div>
</header>
