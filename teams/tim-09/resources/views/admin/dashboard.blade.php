<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Kulkaltim</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* VARIABLES */
        :root {
            --admin-dark: #064e3b;  /* Dark Sage */
            --admin-light: #10b981; /* Green Emerald */
            --sage-accent: #d1fae5; /* Light Sage for Active State */
            --bg-body: #f3f4f6;
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* ===========================
           SIDEBAR
        =========================== */
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

        /* Link Style */
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

        /* Hover & Active State */
        .nav-link:hover, .nav-link.active {
            background-color: var(--sage-accent);
            color: var(--admin-dark);
        }

        .nav-link:hover i, .nav-link.active i {
            color: var(--admin-dark);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #f3f4f6;
        }

        /* ===========================
           MAIN CONTENT
        =========================== */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: all 0.3s ease-in-out;
            min-height: 100vh;
        }

        /* ===========================
           COMPONENTS (Stats & Tables)
        =========================== */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #f3f4f6;
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-5px); }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-resto { background: #d1fae5; color: #059669; }
        .icon-menu { background: #ffedd5; color: #ea580c; }
        .icon-review { background: #e0e7ff; color: #4f46e5; }
        .icon-kota { background: #fee2e2; color: #dc2626; }

        .card-custom {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
        }

        .table thead th {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.85rem;
            text-transform: uppercase;
            background-color: #f9fafb;
            border-bottom: none;
            padding: 12px 16px;
        }
        
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        /* ===========================
           RESPONSIVE (MOBILE)
        =========================== */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); box-shadow: none; }
            .sidebar.show { transform: translateX(0); box-shadow: 0 0 20px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <nav class="sidebar">
        <div class="sidebar-brand fw-bold">
             Admin Panel
        </div>
        
        <div class="sidebar-menu">
            <span class="menu-header">Utama</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <span class="menu-header mt-3">Master Data</span>
            <a href="{{ route('admin.kota.dashboard') }}" class="nav-link">
                <i class="bi bi-map-fill"></i> Data Kota
            </a>
            <a href="{{ route('admin.resto.index') }}" class="nav-link">
                <i class="bi bi-shop-window"></i> Data Restoran
            </a>
            <a href="{{ route('admin.menu.index') }}" class="nav-link">
                <i class="bi bi-book-half"></i> Data Menu
            </a>

            <span class="menu-header mt-3">Interaksi</span>
            <a href="{{ route('admin.review.index') }}" class="nav-link">
                <i class="bi bi-chat-quote-fill"></i> Review User
            </a>
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
        
        {{-- HEADER MOBILE TOGGLE (BARU) --}}
        <div class="d-flex align-items-center d-lg-none mb-4">
            <button class="btn btn-light border me-3" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="m-0 fw-bold">Dashboard</h5>
        </div>

        {{-- DESKTOP HEADER --}}
        <div class="top-header">
            <div>
                <h3 class="fw-bold mb-0 text-dark d-none d-lg-block">Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}! 👋</h3>
                <p class="text-muted mb-0">Berikut ringkasan data kuliner hari ini.</p>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted mb-1">Total Restoran</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalResto) }}</h2>
                    </div>
                    <div class="stat-icon icon-resto"><i class="bi bi-shop"></i></div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted mb-1">Total Menu</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalMenu) }}</h2>
                    </div>
                    <div class="stat-icon icon-menu"><i class="bi bi-egg-fried"></i></div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted mb-1">Review Masuk</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalReview) }}</h2>
                    </div>
                    <div class="stat-icon icon-review"><i class="bi bi-star-half"></i></div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted mb-1">Wilayah Kota</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalKota) }}</h2>
                    </div>
                    <div class="stat-icon icon-kota"><i class="bi bi-map"></i></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- CHART SECTION --}}
            <div class="col-lg-12">
                <div class="card-custom">
                    <h5 class="fw-bold mb-4">Statistik Menu Per Kota</h5>
                    <div style="height: 350px;">
                        <canvas id="menuKotaChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- RECENT REVIEWS --}}
            <div class="col-lg-12">
                <div class="card-custom h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Review Terbaru</h5>
                        <a href="{{ route('admin.review.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Menu Ditinjau</th>
                                    <th>Rating</th>
                                    <th>Komentar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestReviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-light rounded-circle p-1">👤</div>
                                            <span class="fw-medium">{{ $review->nama_user }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $review->menu->nama_menu ?? 'Menu Terhapus' }}</div>
                                        <small class="text-muted">{{ $review->menu->resto->nama_resto ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $review->rating >= 4 ? 'success' : 'warning' }}">
                                            ⭐ {{ $review->rating }}
                                        </span>
                                    </td>
                                    <td class="text-muted text-truncate" style="max-width: 300px;">
                                        {{ $review->komentar }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada review masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // TOGGLE SIDEBAR MOBILE
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('#sidebarToggle');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Close Sidebar on click outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992) {
                const isClickInside = sidebar.contains(event.target) || toggleBtn.contains(event.target);
                if (!isClickInside && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // CHART CONFIGURATION
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('menuKotaChart').getContext('2d');
            
            var labels = {!! json_encode($chartLabels) !!};
            var dataValues = {!! json_encode($chartValues) !!};

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Menu',
                        data: dataValues,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(6, 78, 59, 0.7)',   
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(59, 130, 246, 0.7)', 
                            'rgba(239, 68, 68, 0.7)'   
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(6, 78, 59, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 2] }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>