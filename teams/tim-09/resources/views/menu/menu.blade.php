<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Kulkaltim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top main-nav">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img
                    src="{{ asset('storage/foto/kulkaltim.png') }}"
                    alt="Kulkaltim Logo"
                    height="32">
                Kulkaltim
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-lg-4 py-3 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('restoran.index') }}">Restoran</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-wrapper">
        <div class="hero-overlay"></div>
        <div class="container position-relative text-center text-white z-2">
            <span class="badge-promo">Eksplor Kuliner</span>
            <h1 class="fw-bold display-5 mt-2">Jelajah Menu</h1>
            <p class="opacity-75">Temukan cita rasa favoritmu dari tanah Borneo</p>
        </div>
        <div class="hero-fade-bottom"></div>
    </header>

    <section class="search-container px-3">
        <div class="container">
            <form method="GET" action="{{ route('menu.menu') }}">
                <div class="search-modern">
                    <div class="search-input-wrapper">
                        <span class="search-icon">🔍</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="search-input"
                            {{-- UPDATE PLACEHOLDER DI SINI --}}
                            placeholder="Cari Menu atau Restoran...">
                    </div>

                    <select name="id_kota" class="search-select">
                        <option value="">Semua Kota</option>
                        @foreach ($kotas as $kota)
                        <option value="{{ $kota->id_kota }}" {{ request('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                            {{ $kota->nama_kota }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="search-btn">Terapkan</button>
                </div>
            </form>
        </div>
    </section>

    <main class="container py-5">
        <div class="row g-4">
            @forelse ($menus as $menu)
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                <a href="{{ route('menu.menudetail', $menu->id_menu) }}" class="card-link w-100">
                    <article class="food-card w-100">
                        {{-- Bagian Gambar --}}
                        <div class="food-image-wrapper">
                            <img src="{{ $menu->foto ?? asset('images/no-image.png') }}" alt="{{ $menu->nama_menu }}">
                            <div class="location-tag">📍 {{ $menu->resto->kota->nama_kota ?? '-' }}</div>
                        </div>

                        {{-- Bagian Informasi --}}
                        <div class="food-info">
                            <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>
                            
                            {{-- Tampilkan nama Restoran agar user tau ini hasil search --}}
                            <div class="small text-muted mb-2 fw-medium">
                                <i class="bi bi-shop me-1"></i> {{ $menu->resto->nama_resto }}
                            </div>

                            {{-- LOGIKA RATING OTOMATIS --}}
                            <div class="mb-2 text-warning small">
                                @php
                                    $avgRating = $menu->reviews->avg('rating') ?? 0;
                                @endphp

                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avgRating)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - 0.5 <= $avgRating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                                
                                <span class="text-muted ms-1">({{ number_format($avgRating, 1) }})</span>
                            </div>
                            
                            {{-- Alamat --}}
                            <p class="text-muted small mb-3 text-truncate">
                                <i class="bi bi-geo-alt-fill text-danger"></i> {{ $menu->resto->alamat ?? 'Alamat tidak tersedia' }}
                            </p>

                            {{-- Harga --}}
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success fs-5">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </span>
                                <span class="btn btn-sm btn-outline-success border-0">Detail →</span>
                            </div>
                        </div>
                    </article>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3 fs-1">🥘</div>
                <h4 class="fw-bold text-secondary">Menu atau Restoran tidak ditemukan</h4>
                <p class="text-muted">Coba cari dengan kata kunci lain.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $menus->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </main>

    @include('footer.layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>