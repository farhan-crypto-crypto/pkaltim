<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Destinasi - Galeri Wisata</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-sidebar { background-color: #064e3b; } 
            .bg-active { background-color: #10B981; }
            .text-active { color: #059669; }
            .modal { transition: opacity 0.25s ease; }
        </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-sidebar text-white flex flex-col justify-between hidden md:flex shadow-xl z-20">
            <div>
                    <div class="h-20 flex items-center px-8 border-b border-gray-700/30">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </div>
                            <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Wisata<span class="text-brand-500">Kaltim</span></span>
                        </div>
                    </div>


                    <nav class="mt-8 px-4 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all">
                            <i class="fa-solid fa-chart-pie w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.destinations.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all">
                            <i class="fa-solid fa-map-location-dot w-5"></i>
                            <span class="font-medium">Destinasi</span>
                        </a>
                        <a href="{{ route('admin.category.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all">
                            <i class="fa-solid fa-tags w-5"></i>
                            <span class="font-medium">Kategori</span>
                        </a>
                        <a href="{{ route('admin.gallery.selection') }}" class="flex items-center gap-3 px-4 py-3 bg-active rounded-lg text-white shadow-lg shadow-green-900/50 transition-all">
                            <i class="fa-solid fa-images w-5"></i>
                            <span class="font-medium">Galeri Wisata</span>
                        </a>
                        <a href="{{ route('admin.facility.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all">
                            <i class="fa-solid fa-bell-concierge w-5"></i>
                            <span class="font-medium">Fasilitas</span>
                        </a>
                    </nav>
            </div>

            <div class="p-4 border-t border-gray-700/30">
                <div class="flex items-center gap-3 bg-gray-800/50 p-3 rounded-lg">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-green-500" 
                         src="https://ui-avatars.com/api/?name={{ Auth::user()->username ?? Auth::user()->name }}&background=00A651&color=fff" 
                         alt="Admin Profile">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-white">{{ Auth::user()->username ?? Auth::user()->name }}</span>
                        <span class="text-xs text-gray-400 truncate w-24">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs text-red-400 hover:text-red-300 py-2 transition-colors">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden bg-[#F9FAFB] relative">
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Galeri Wisata</h1>
                    <p class="text-sm text-gray-500">Pilih destinasi wisata yang ingin Anda kelola fotonya.</p>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-images text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Image</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalImage }}</h3>
                        </div>
                    </div>
            </div>
            
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nama Destinasi</th>
                                <th class="px-6 py-4 text-center">Jumlah Foto</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($destinations as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 text-lg">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-400">
                                        <i class="fa-regular fa-clock mr-1"></i> Update: {{ $item->updated_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                        {{ $item->images_count ?? 0 }} Foto
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- TOMBOL INI MEMBUKA PAGE MANAGE --}}
                                    <a href="{{ route('admin.gallery.index', $item->id) }}" 
                                       class="bg-active inline-flex items-center gap-2 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-0.5">
                                        <i class="fa-regular fa-images"></i> Kelola Foto
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada data destinasi. Silakan tambahkan destinasi terlebih dahulu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</body>
</html>