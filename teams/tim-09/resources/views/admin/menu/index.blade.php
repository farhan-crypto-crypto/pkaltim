<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Menu | Admin Kulkaltim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS GLOBAL ADMIN */
        :root {
            --admin-dark: #064e3b;
            /* Dark Sage */
            --admin-light: #10b981;
            /* Green Emerald */
            --sage-accent: #d1fae5;
            /* Light Sage for Active State */
            --bg-body: #f3f4f6;
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            color: var(--admin-dark);
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid #f3f4f6;
        }

        .sidebar-menu {
            padding: 20px 15px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-header {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
            padding-left: 10px;
            font-weight: 600;
            display: block;
            margin-top: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            color: #4b5563;
            padding: 12px 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 5px;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            color: #9ca3af;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: var(--sage-accent);
            color: var(--admin-dark);
        }

        .nav-link:hover i,
        .nav-link.active i {
            color: var(--admin-dark);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #f3f4f6;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: all 0.3s ease-in-out;
            min-height: 100vh;
        }

        /* COMPONENTS */
        .card-modern {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            overflow: hidden;
        }

        .btn-sage {
            background-color: var(--admin-light);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-sage:hover {
            background-color: var(--admin-dark);
            color: white;
        }

        .img-thumb-custom {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR NAVIGATION --}}
    <nav class="sidebar">
        <div class="sidebar-brand fw-bold">
            <img
                src="{{ asset('storage/foto/kulkaltim.png') }}"
                alt="Kulkaltim Logo"
                height="32">
            Admin Panel
        </div>
        <div class="sidebar-menu">
            <span class="menu-header">Utama</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>

            <span class="menu-header mt-3">Master Data</span>
            <a href="{{ route('admin.kota.dashboard') }}" class="nav-link"><i class="bi bi-map-fill"></i> Data Kota</a>
            <a href="{{ route('admin.resto.index') }}" class="nav-link"><i class="bi bi-shop-window"></i> Data Restoran</a>
            <a href="#" class="nav-link active"><i class="bi bi-book-half"></i> Data Menu</a>

            <span class="menu-header mt-3">Interaksi</span>
            <a href="{{ route('admin.review.index') }}" class="nav-link"><i class="bi bi-chat-quote-fill"></i> Review User</a>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right text-danger"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        {{-- HEADER MOBILE TOGGLE --}}
        <div class="d-flex align-items-center d-lg-none mb-4">
            <button class="btn btn-light border me-3" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="m-0 fw-bold">Data Menu</h5>
        </div>

        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1 d-none d-lg-block">Manajemen Menu</h3>
            <p class="text-muted small">Kelola menu makanan, harga, dan rating via link gambar.</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card-modern">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daftar Menu</h5>
                <button type="button" class="btn btn-sage" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Menu
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center py-3">No</th>
                                <th class="py-3">Foto</th>
                                <th class="py-3">Info Menu</th>
                                <th class="py-3">Rating</th>
                                <th class="py-3">Harga</th>
                                <th width="15%" class="text-center py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menus as $menu)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $menu->foto ?? 'https://via.placeholder.com/60' }}"
                                        class="img-thumb-custom"
                                        alt="Menu"
                                        onerror="this.src='https://via.placeholder.com/60?text=No+Img';">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $menu->nama_menu }}</div>
                                    <div class="small text-muted mb-1">{{ $menu->resto->nama_resto ?? 'Resto Hapus' }}</div>
                                    <div class="small text-secondary fst-italic text-truncate" style="max-width: 200px;">
                                        {{ $menu->deskripsi }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-warning small text-nowrap" title="Rata-rata: {{ number_format($menu->reviews_avg_rating, 1) }}">
                                        @php
                                        $rating = $menu->reviews_avg_rating ?? 0;
                                        @endphp

                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <=$rating)
                                            <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $rating)
                                                <i class="bi bi-star-half"></i>
                                                @else
                                                <i class="bi bi-star"></i>
                                                @endif
                                                @endfor

                                                <span class="text-dark ms-1 fw-bold">
                                                    {{ number_format($rating, 1) }}
                                                </span>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit-{{ $menu->id_menu }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form onsubmit="return confirm('Yakin hapus menu ini?')"
                                            action="{{ route('admin.menu.destroy', $menu->id_menu) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT --}}
                            <div class="modal fade" id="modalEdit-{{ $menu->id_menu }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Menu</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <form action="{{ route('admin.menu.update', $menu->id_menu) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body pt-4">

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-muted small text-uppercase">Nama Menu</label>
                                                    <input type="text" class="form-control bg-light border-0" name="nama_menu" value="{{ $menu->nama_menu }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-muted small text-uppercase">Restoran</label>
                                                    <select name="id_resto" class="form-select bg-light border-0" required>
                                                        <option value="">Pilih Restoran</option>
                                                        @foreach ($restos as $resto)
                                                        <option value="{{ $resto->id_resto }}" {{ $menu->id_resto == $resto->id_resto ? 'selected' : '' }}>
                                                            {{ $resto->nama_resto }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-muted small text-uppercase">Harga (Rp)</label>
                                                    <input type="number" class="form-control bg-light border-0" name="harga" value="{{ $menu->harga }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-muted small text-uppercase">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control bg-light border-0" rows="3">{{ $menu->deskripsi }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-muted small text-uppercase">Link Foto (URL)</label>
                                                    <input type="text" class="form-control bg-light border-0" name="foto" value="{{ $menu->foto }}" required>

                                                    <div class="mt-2">
                                                        <small class="text-muted d-block mb-1">Preview:</small>
                                                        <img src="{{ $menu->foto ?? 'https://via.placeholder.com/60' }}"
                                                            class="img-thumbnail"
                                                            style="height: 60px;"
                                                            onerror="this.src='https://via.placeholder.com/60?text=Error';">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 pb-4 px-3">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sage rounded-pill px-4">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-egg-fried display-4 mb-3 d-block opacity-25"></i>
                                    Belum ada data menu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="p-3 d-flex justify-content-end">
                    {{ $menus->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Menu Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.menu.store') }}" method="POST">
                    @csrf
                    <div class="modal-body pt-4">

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small text-uppercase">Nama Menu</label>
                            <input type="text" class="form-control bg-light border-0" name="nama_menu" placeholder="Contoh: Nasi Kuning" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small text-uppercase">Restoran</label>
                            <select name="id_resto" class="form-select bg-light border-0" required>
                                <option value="">-- Pilih Restoran --</option>
                                @foreach ($restos as $resto)
                                <option value="{{ $resto->id_resto }}">{{ $resto->nama_resto }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small text-uppercase">Harga (Rp)</label>
                            <input type="number" class="form-control bg-light border-0" name="harga" placeholder="15000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small text-uppercase">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control bg-light border-0" rows="3" placeholder="Deskripsi singkat menu..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small text-uppercase">Link Foto (URL)</label>
                            <input type="text" class="form-control bg-light border-0" name="foto" placeholder="https://images.unsplash.com/..." required>
                        </div>

                    </div>
                    <div class="modal-footer border-top-0 pt-0 pb-4 px-3">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sage rounded-pill px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SCRIPT SIDEBAR MOBILE --}}
    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('#sidebarToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992) {
                const isClickInside = sidebar.contains(event.target) || toggleBtn.contains(event.target);
                if (!isClickInside && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>

</html>