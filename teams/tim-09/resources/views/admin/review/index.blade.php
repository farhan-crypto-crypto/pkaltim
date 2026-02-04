<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Review | Admin Kulkaltim</title>

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
        <div class="sidebar-brand fw-bold"><img
                src="{{ asset('storage/foto/kulkaltim.png') }}"
                alt="Kulkaltim Logo"
                height="32">
            Admin Panel</div>
        <div class="sidebar-menu">
            <span class="menu-header">Utama</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>

            <span class="menu-header mt-3">Master Data</span>
            <a href="{{ route('admin.kota.dashboard') }}" class="nav-link"><i class="bi bi-map-fill"></i> Data Kota</a>
            <a href="{{ route('admin.resto.index') }}" class="nav-link"><i class="bi bi-shop-window"></i> Data Restoran</a>
            <a href="{{ route('admin.menu.index') }}" class="nav-link"><i class="bi bi-book-half"></i> Data Menu</a>

            <span class="menu-header mt-3">Interaksi</span>
            <a href="#" class="nav-link active"><i class="bi bi-chat-quote-fill"></i> Review User</a>
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
            <h5 class="m-0 fw-bold">Data Review</h5>
        </div>

        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1 d-none d-lg-block">Data Review Pengunjung</h3>
            <p class="text-muted small">Pantau ulasan yang masuk. Hapus ulasan yang mengandung kata kasar atau spam.</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card-modern">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Daftar Semua Review</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center py-3">No</th>
                                <th class="py-3" width="15%">Tanggal</th>
                                <th class="py-3" width="15%">Pengguna</th>
                                <th class="py-3" width="20%">Menu Ditinjau</th>
                                <th class="py-3" width="35%">Rating & Komentar</th>
                                <th width="10%" class="text-center py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $review->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $review->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td class="fw-medium">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-2 border" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ substr($review->nama_user, 0, 1) }}
                                        </div>
                                        {{ $review->nama_user }}
                                    </div>
                                </td>
                                <td>
                                    @if($review->menu)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ $review->menu->nama_menu }}
                                    </span>
                                    <div class="small text-muted mt-1">{{ $review->menu->resto->nama_resto ?? '-' }}</div>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger">Menu Terhapus</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="mb-1 text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $review->rating >= $i ? '-fill' : '' }}"></i>
                                            @endfor
                                            <span class="text-muted ms-1 fw-bold">({{ $review->rating }}.0)</span>
                                    </div>
                                    <span title="{{ $review->komentar }}" class="mb-0 small text-muted fst-italic">
                                        "{{ Str::limit($review->komentar, 50, '...') }}"
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini secara permanen?')"
                                        action="{{ route('admin.review.destroy', $review->id_review) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Review">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-text display-4 mb-3 d-block opacity-25"></i>
                                    Belum ada data review masuk.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="p-3 d-flex justify-content-end">
                    {{ $reviews->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </main>

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