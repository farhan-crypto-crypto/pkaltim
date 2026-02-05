<?php
session_start();
require_once '../config/database.php';

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';
$messageType = '';

// Handle Image Upload Function
function uploadImage($file, $targetDir = "../assets/img/destinations/") {
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $fileName = time() . '_' . uniqid() . '.' . $fileType;
    $targetFile = $targetDir . $fileName;
    
    // Check if image file is an actual image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) return ['error' => 'File is not an image.'];
    
    // Check file size (2MB max)
    if ($file["size"] > 2000000) return ['error' => 'File is too large (Max 2MB).'];
    
    // Allow certain file formats
    if(!in_array($fileType, ['jpg', 'jpeg', 'png', 'webp'])) return ['error' => 'Only JPG, JPEG, PNG & WEBP allowed.'];
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ['success' => 'assets/img/destinations/' . $fileName];
    } else {
        return ['error' => 'Failed to upload image.'];
    }
}

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Destination
    if (isset($_POST['add_destination'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        
        $imagePath = 'https://placehold.co/600x400'; // Default
        
        if (!empty($_FILES['image']['name'])) {
            $upload = uploadImage($_FILES['image']);
            if (isset($upload['success'])) {
                $imagePath = $upload['success'];
            } else {
                $message = $upload['error'];
                $messageType = 'error';
            }
        }

        if ($messageType != 'error') {
            $rating = !empty($_POST['rating']) ? $_POST['rating'] : 0.0;
            $stmt = $pdo->prepare("INSERT INTO destinations (name, location, price, category, description, image, rating) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $location, $price, $category, $description, $imagePath, $rating])) {
                $message = "Destinasi berhasil ditambahkan!";
                $messageType = "success";
            }
        }
    } 
    
    // Update Destination
    elseif (isset($_POST['update_destination'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        
        if (!empty($_FILES['image']['name'])) {
            $upload = uploadImage($_FILES['image']);
            if (isset($upload['success'])) {
                $imagePath = $upload['success'];
                // Delete old image if not a placeholder
                $stmt = $pdo->prepare("SELECT image FROM destinations WHERE id = ?");
                $stmt->execute([$id]);
                $oldImage = $stmt->fetchColumn();
                if ($oldImage && strpos($oldImage, 'placehold.co') === false) {
                    $fullOldPath = "../" . $oldImage;
                    if (file_exists($fullOldPath)) unlink($fullOldPath);
                }
                
                $stmt = $pdo->prepare("UPDATE destinations SET name=?, location=?, price=?, category=?, rating=?, description=?, image=? WHERE id=?");
                $stmt->execute([$name, $location, $price, $category, $_POST['rating'], $description, $imagePath, $id]);
            } else {
                $message = $upload['error'];
                $messageType = 'error';
            }
        } else {
            $stmt = $pdo->prepare("UPDATE destinations SET name=?, location=?, price=?, category=?, rating=?, description=? WHERE id=?");
            $stmt->execute([$name, $location, $price, $category, $_POST['rating'], $description, $id]);
        }
        
        if ($messageType != 'error') {
            $message = "Destinasi berhasil diperbarui!";
            $messageType = "success";
        }
    }
    
    // Delete Destination
    elseif (isset($_POST['delete_id'])) {
        // Delete image file first
        $stmt = $pdo->prepare("SELECT image FROM destinations WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $image = $stmt->fetchColumn();
        if ($image && strpos($image, 'placehold.co') === false) {
            $fullPath = "../" . $image;
            if (file_exists($fullPath)) unlink($fullPath);
        }
        
        $stmt = $pdo->prepare("DELETE FROM destinations WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $message = "Destinasi berhasil dihapus!";
        $messageType = "success";
    }
}

// Fetch Destinations
try {
    $stmt = $pdo->query("SELECT * FROM destinations ORDER BY id DESC");
    $destinations = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$title = "Kelola Destinasi";
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
    </style>
</head>
<body class="bg-primary font-sans antialiased text-slate-200" x-data="{ showAddModal: false, showEditModal: false, editData: {} }">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto bg-primary relative">
        <?php include 'includes/header.php'; ?>

        <main class="p-8 max-w-7xl mx-auto">
            <!-- Toast Message -->
            <?php if ($message): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="fixed top-24 right-8 z-50 flex items-center gap-3 px-6 py-4 rounded-2xl border backdrop-blur-xl transition-all duration-500 <?php echo $messageType == 'success' ? 'bg-green-500/10 border-green-500/20 text-green-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
                <i class="bi <?php echo $messageType == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> text-xl"></i>
                <span class="font-bold"><?php echo $message; ?></span>
            </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 gsap-reveal">
                <div>
                    <h2 class="text-3xl font-display font-bold text-white mb-2">Daftar Destinasi</h2>
                    <p class="text-slate-500 text-sm">Kelola semua tempat wisata yang tersedia untuk pelanggan.</p>
                </div>
                <button @click="showAddModal = true" class="px-8 py-4 bg-secondary text-primary rounded-2xl hover:bg-white transition-all duration-300 flex items-center gap-3 text-sm font-bold shadow-[0_0_15px_rgba(212,175,55,0.3)] hover:scale-105 transform group">
                    <i class="bi bi-plus-lg group-hover:rotate-90 transition-transform"></i> Tambah Destinasi
                </button>
            </div>

            <div class="glass-card rounded-[2.5rem] overflow-hidden gsap-reveal">
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-white/5 text-slate-200 uppercase font-bold text-xs tracking-widest">
                            <tr>
                                <th class="px-8 py-6">Destinasi</th>
                                <th class="px-8 py-6">Kategori</th>
                                <th class="px-8 py-6 text-center">Rating</th>
                                <th class="px-8 py-6 text-center">Harga</th>
                                <th class="px-8 py-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($destinations as $dest): ?>
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl overflow-hidden border border-white/10 shrink-0">
                                            <img src="<?php echo (strpos($dest['image'], 'http') === 0) ? '' : '../'; ?><?php echo htmlspecialchars($dest['image']); ?>" 
                                                 class="w-full h-full object-cover" 
                                                 alt="<?php echo $dest['name']; ?>"
                                                 onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                                        </div>
                                        <div>
                                            <div class="font-display font-bold text-white group-hover:text-secondary transition-colors"><?php echo htmlspecialchars($dest['name']); ?></div>
                                            <div class="text-slate-500 text-xs flex items-center gap-1 mt-1">
                                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($dest['location']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-wider text-slate-300">
                                        <?php echo $dest['category'] ?? 'Alam'; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <i class="bi bi-star-fill text-secondary text-xs"></i>
                                        <span class="text-white font-bold"><?php echo number_format($dest['rating'], 1); ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="font-bold text-secondary">Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?></div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center items-center gap-2">
                                        <button @click="editData = <?php echo htmlspecialchars(json_encode($dest)); ?>; showEditModal = true" 
                                                class="w-10 h-10 bg-white/5 text-slate-400 border border-white/10 rounded-xl hover:bg-secondary hover:text-black hover:border-secondary transition-all transform hover:scale-110">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" class="inline-block">
                                            <input type="hidden" name="delete_id" value="<?php echo $dest['id']; ?>">
                                            <button type="submit" class="w-10 h-10 bg-rose-500/10 text-rose-500 border border-rose-500/20 rounded-xl hover:bg-rose-500 hover:text-white transition-all transform hover:scale-110">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Scripts for GSAP -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.to(".gsap-reveal", {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.2,
            ease: "power4.out"
        });
    });
</script>

<!-- Add Modal -->
<div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-xl transition-opacity" @click="showAddModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-dark border border-white/10 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full p-8 md:p-12">
            <form method="POST" enctype="multipart/form-data">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-display font-bold text-white">Tambah <span class="text-secondary italic font-serif">Destinasi</span></h3>
                    <button type="button" @click="showAddModal = false" class="text-slate-500 hover:text-white transition-colors">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Nama Destinasi</label>
                        <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all" placeholder="Misal: Taman Nasional Kutai">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Kategori</label>
                        <select name="category" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all appearance-none">
                            <option value="Alam" class="bg-dark">Alam</option>
                            <option value="Budaya" class="bg-dark">Budaya</option>
                            <option value="Kuliner" class="bg-dark">Kuliner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Harga Tiket</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                            <input type="number" name="price" required class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Rating</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-bold"><i class="bi bi-star-fill"></i></span>
                            <input type="number" name="rating" step="0.1" min="0" max="5" value="0.0" required class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Lokasi</label>
                        <input type="text" name="location" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all" placeholder="Kecamatan, Kota/Kabupaten">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Unggah Gambar</label>
                        <div class="flex items-center gap-4 bg-white/5 border border-dashed border-white/20 rounded-2xl p-6 hover:border-secondary/50 transition-all cursor-pointer relative group">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                <i class="bi bi-cloud-arrow-up-fill text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white group-hover:text-secondary transition-colors">Pilih File... (Max 2MB)</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Format: JPG, PNG, WEBP</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all" placeholder="Jelaskan daya tarik, fasilitas, dan keunikan destinasi ini..."></textarea>
                    </div>
                </div>
                
                <div class="mt-10 flex gap-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 px-8 py-5 bg-white/5 text-slate-300 rounded-2xl font-bold hover:bg-white/10 transition-all border border-white/5">Batal</button>
                    <button type="submit" name="add_destination" class="flex-1 px-8 py-5 bg-secondary text-primary rounded-2xl font-bold hover:bg-white transition-all shadow-[0_10px_30px_rgba(212,175,55,0.3)]">Simpan Destinasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-xl transition-opacity" @click="showEditModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-dark border border-white/10 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full p-8 md:p-12">
            <form method="POST" enctype="multipart/form-data" x-ref="editForm">
                <input type="hidden" name="id" :value="editData.id">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-display font-bold text-white">Edit <span class="text-secondary italic font-serif" x-text="editData.name"></span></h3>
                    <button type="button" @click="showEditModal = false" class="text-slate-500 hover:text-white transition-colors">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Nama Destinasi</label>
                        <input type="text" name="name" :value="editData.name" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Kategori</label>
                        <select name="category" x-model="editData.category" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all appearance-none">
                            <option value="Alam" class="bg-dark">Alam</option>
                            <option value="Budaya" class="bg-dark">Budaya</option>
                            <option value="Kuliner" class="bg-dark">Kuliner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Harga Tiket</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                            <input type="number" name="price" :value="editData.price" required class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Rating</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-bold"><i class="bi bi-star-fill"></i></span>
                            <input type="number" name="rating" step="0.1" min="0" max="5" :value="editData.rating" required class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Lokasi</label>
                        <input type="text" name="location" :value="editData.location" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Ganti Gambar (Opsional)</label>
                        <div class="flex items-center gap-4 bg-white/5 border border-dashed border-white/20 rounded-2xl p-6 hover:border-secondary/50 transition-all cursor-pointer relative group">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                                <i class="bi bi-image text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white group-hover:text-secondary transition-colors">Pilih Gambar Baru...</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1 italic">Kosongkan jika tidak ingin mengubah gambar</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all" x-text="editData.description"></textarea>
                    </div>
                </div>
                
                <div class="mt-10 flex gap-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 px-8 py-5 bg-white/5 text-slate-300 rounded-2xl font-bold hover:bg-white/10 transition-all border border-white/5">Batal</button>
                    <button type="submit" name="update_destination" class="flex-1 px-8 py-5 bg-secondary text-primary rounded-2xl font-bold hover:bg-white transition-all shadow-[0_10px_30px_rgba(212,175,55,0.3)]">Perbarui Destinasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
