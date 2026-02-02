<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - {{ $destination->name }}</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Kelola Foto</h1>
                    <p class="text-sm text-gray-500">Wisata: <span class="font-bold text-green-600">{{ $destination->name }}</span></p>
                </div>

                <a href="{{ route('admin.gallery.selection') }}" 
                   class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-green-700 hover:border-green-500 hover:bg-green-50 px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Upload Foto Baru</h3>
                    
                    <form action="{{ route('admin.gallery.store', $destination->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4 items-end">
                        @csrf
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Gambar (Max 2MB)</label>
                            <input type="file" name="image" required class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-green-50 file:text-green-700
                                hover:file:bg-green-100 cursor-pointer border border-gray-300 rounded-lg
                              "/>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md transition-all flex items-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Upload
                        </button>
                    </form>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-regular fa-images text-gray-500"></i> Koleksi Foto
                </h3>

                @if($destination->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($destination->images as $img)
                            <div class="group relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 {{ $img->is_primary ? 'ring-2 ring-green-500' : '' }}">
                                
                                @if($img->is_primary)
                                    <div class="absolute top-2 left-2 z-10 bg-green-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md flex items-center gap-1">
                                        <i class="fa-solid fa-star"></i> THUMBNAIL
                                    </div>
                                @endif

                                <div class="aspect-square w-full overflow-hidden bg-gray-100 relative">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" 
                                         alt="Foto Wisata" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                        
                                        @if(!$img->is_primary)
                                            <form action="{{ route('admin.gallery.primary', $img->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="w-10 h-10 rounded-full bg-white text-yellow-500 hover:bg-yellow-400 hover:text-white transition shadow-lg flex items-center justify-center transform hover:scale-110" title="Jadikan Thumbnail">
                                                    <i class="fa-solid fa-star"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.gallery.destroy', $img->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-full bg-white text-red-500 hover:bg-red-500 hover:text-white transition shadow-lg flex items-center justify-center transform hover:scale-110" title="Hapus Foto">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>

                                    </div>
                                </div>

                                <div class="p-3 text-xs text-gray-400 text-center border-t border-gray-100 bg-gray-50">
                                    <i class="fa-regular fa-clock mr-1"></i> {{ $img->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-300">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-400 mb-4">
                            <i class="fa-regular fa-image text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada foto</h3>
                        <p class="text-gray-500 text-sm mt-1">Silakan upload foto pada form di atas.</p>
                    </div>
                @endif

            </div>
        </main>
    </div>
</body>
</html>