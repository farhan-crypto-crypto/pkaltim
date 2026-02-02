<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Destinasi - Kaltim Nature</title>
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
                        <a href="{{ route('admin.destinations.index') }}" class="flex items-center gap-3 px-4 py-3 bg-active rounded-lg text-white shadow-lg shadow-green-900/50 transition-all">
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
                        <span class="text-sm font-semibold text-white">{{ Auth::user()->username ?? Auth::user()->name }}</span>
                        <span class="text-xs text-gray-400 truncate w-24">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs text-red-400 hover:text-red-300 py-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                         Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden bg-[#F9FAFB] relative">
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                <h1 class="text-2xl font-bold text-gray-800">Kelola Destinasi</h1>
                <button onclick="toggleModal('createModal')" class="bg-active hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-green-500/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Wisata
                </button>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center text-active">
                            <i class="fa-solid fa-map-location-dot text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Destinasi</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalDestinations }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase w-10">No</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Nama Wisata</th>
                                    <th class="px- py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Lokasi</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jam Buka</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($destinations as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div>
                                                <span class="block font-semibold text-gray-800">{{ $item->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->status == 'active')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                                                @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                            {{ $item->categories->name}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-[150px]" title="{{ $item->address ?? $item->location }}">{{ $item->address ?? $item->location }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->opening_hours ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-active">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick="openEditModal({{ $item }})" 
                                                class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            
                                            <form action="{{ route('admin.destinations.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada data destinasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100">{{ $destinations->links() }}</div>
                </div>
            </div>
        </main>
    </div>

    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl my-8">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-800">Tambah Destinasi</h3>
                <button onclick="toggleModal('createModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wisata</label>
                        <input type="text" name="name" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Tiket (Rp)</label>
                        <input type="number" name="price" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi / Alamat</label>
                        <input type="text" name="address" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                        <input type="text" name="opening_hours" placeholder="Contoh: 08:00 - 17:00" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                            <option value="active">Active (Tampil)</option>
                            <option value="inactive">Inactive (Sembunyi)</option>
                        </select>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Koordinat Peta (Maps)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="text" name="latitude" placeholder="-0.502xxx" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" placeholder="117.15xxx" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500"></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('createModal')" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-active text-white rounded-lg hover:bg-green-700">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl my-8">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-800">Edit Destinasi</h3>
                <button onclick="toggleModal('editModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wisata</label>
                        <input type="text" id="edit_name" name="name" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" id="edit_category_id" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Tiket</label>
                        <input type="number" id="edit_price" name="price" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi / Alamat</label>
                        <input type="text" id="edit_address" name="address" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                        <input type="text" id="edit_opening_hours" name="opening_hours" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Koordinat Peta (Maps)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="text" id="edit_latitude" name="latitude" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="text" id="edit_longitude" name="longitude" class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="edit_description" name="description" rows="3" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:border-green-500"></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('editModal')" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-active text-white rounded-lg hover:bg-green-700">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        function openEditModal(data) {
            // Isi form dengan data
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_price').value = data.price;
            
            // Perhatikan: di migration namanya 'address', tapi jika di controller lama pakai 'location'
            // kode di bawah menyesuaikan dengan field database 'address'
            // Jika di database data lama masih di kolom 'location', sesuaikan bagian ini
            document.getElementById('edit_address').value = data.address || data.location; 

            document.getElementById('edit_description').value = data.description;
            document.getElementById('edit_category_id').value = data.category_id;

            // NEW FIELDS
            document.getElementById('edit_opening_hours').value = data.opening_hours;
            document.getElementById('edit_status').value = data.status;
            document.getElementById('edit_latitude').value = data.latitude;
            document.getElementById('edit_longitude').value = data.longitude;

            // Atur URL form action
            let actionUrl = "{{ route('admin.destinations.index') }}/" + data.id;
            document.getElementById('editForm').action = actionUrl;

            // Buka modal
            toggleModal('editModal');
        }
    </script>
</body>
</html>