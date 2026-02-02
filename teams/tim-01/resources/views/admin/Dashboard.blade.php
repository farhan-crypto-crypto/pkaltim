<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kaltim Nature</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Warna Sidebar Custom */
        .bg-sidebar {
            background-color: #064e3b;
        }

        .bg-active {
            background-color: #10B981;
        }

        .text-active {
            color: #059669;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-sidebar text-white flex flex-col justify-between hidden md:flex shadow-xl">
            <div>
                <div class="h-20 flex items-center px-8 border-b border-gray-700/30">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Wisata<span
                                class="text-brand-500">Kaltim</span></span>
                    </div>
                </div>

                <nav class="mt-8 px-4 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-active rounded-lg text-white shadow-lg shadow-green-900/50 transition-all">
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
                        <a href="{{ route('admin.gallery.selection') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.gallery.*') ? 'bg-green-600 text-white shadow-lg shadow-green-900/50' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
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
                        <span class="text-sm font-semibold text-white">
                            {{ Auth::user()->username ?? Auth::user()->name }}
                        </span>

                        <span class="text-xs text-gray-400"
                            style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ Auth::user()->email }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 text-xs text-red-400 hover:text-red-300 py-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden bg-[#F9FAFB]">
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Panel Dashboard Admin</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Selamat datang kembali,
                        <span class="text-active font-semibold">
                            {{ Auth::user()->username ?? Auth::user()->name }}
                        </span>!
                    </p>
                </div>

            </header>

            <div class="flex-1 overflow-y-auto p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                    <div
                        class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center text-active">
                            <i class="fa-solid fa-map-location-dot text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Destinasi</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalDestinations }}</h3>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-comments text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Ulasan</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalReviews }}</h3>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-tags text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Category</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalCategory }}</h3>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-images text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Image</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalImage }}</h3>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-bell text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Facility</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalFacility }}</h3>
                        </div>
                    </div>
                    
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <img src="https://illustrations.popsy.co/gray/creative-work.svg" alt="Empty State"
                        class="h-48 mx-auto opacity-50 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Belum ada aktivitas terbaru</h3>
                    <p class="text-gray-500">Data terbaru akan muncul di sini.</p>
                </div>

            </div>
        </main>
    </div>

</body>

</html>