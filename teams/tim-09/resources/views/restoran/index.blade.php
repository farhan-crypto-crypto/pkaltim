<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Restoran | Kulkaltim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/resto.css') }}">
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('menu.menu') }}">Menu</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Restoran</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    {{-- Tidak ada style inline background-image lagi, jadi otomatis ikut CSS .hero-wrapper (Sama kayak Menu) --}}
    <header class="hero-wrapper">
        <div class="hero-overlay"></div>
        <div class="container position-relative text-center text-white z-2">
            <span class="badge-promo">Lokasi Pilihan</span>
            <h1 class="fw-bold display-5 mt-2">Daftar Restoran</h1>
            <p class="opacity-75">Temukan tempat makan ternyaman di kota Anda</p>
        </div>
        <div class="hero-fade-bottom"></div>
    </header>

    {{-- SEARCH SECTION --}}
    <section class="search-container px-3">
        <div class="container">
            <form method="GET" action="{{ route('restoran.index') }}">
                <div class="search-modern">
                    <div class="search-input-wrapper">
                        <span class="search-icon text-muted">📍</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="search-input"
                            placeholder="Cari nama tempat makan...">
                    </div>

                    <select name="id_kota" class="search-select text-muted">
                        <option value="">Semua Kota</option>
                        @foreach ($kotas as $kota)
                        <option value="{{ $kota->id_kota }}" {{ request('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                            {{ $kota->nama_kota }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="search-btn">Filter</button>
                </div>
            </form>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="container py-5">
        <div class="row g-4">
            @forelse ($restos as $resto)
            <div class="col-lg-4 col-md-6">
                
                {{-- CARD DIREKTORI (TANPA FOTO) --}}
                <div class="resto-card-minimal">
                    
                    {{-- Ikon Background Transparan --}}
                    <i class="bi bi-shop bg-pattern"></i>

                    {{-- Header: Avatar + Nama --}}
                    <div class="resto-header">
                        <div class="resto-avatar">
                            {{ substr($resto->nama_resto, 0, 1) }}
                        </div>
                        <div>
                            <div class="resto-name">{{ $resto->nama_resto }}</div>
                            <div class="city-badge">
                                <i class="bi bi-geo-alt me-1"></i>{{ $resto->kota->nama_kota ?? 'Kaltim' }}
                            </div>
                        </div>
                    </div>

                    {{-- Body: Alamat --}}
                    <div class="address-box">
                        <i class="bi bi-map-fill me-2 text-success"></i>
                        {{ $resto->alamat ?? 'Alamat belum tersedia.' }}
                    </div>

                    {{-- Footer: Tombol --}}
                    <div class="action-group">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $resto->latitude }},{{ $resto->longitude }}" 
                           target="_blank" 
                           class="btn-custom-outline">
                            <i class="bi bi-compass"></i> Rute
                        </a>
                        <a href="{{ route('menu.menu', ['search' => $resto->nama_resto]) }}" 
                           class="btn-custom-primary">
                            Lihat Menu <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>

                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3 display-1 text-muted opacity-25">🗺️</div>
                <h4 class="fw-bold text-secondary">Data Tidak Ditemukan</h4>
                <p class="text-muted">Coba ubah kata kunci pencarian atau filter kota.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $restos->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </main>

    @include('footer.layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>