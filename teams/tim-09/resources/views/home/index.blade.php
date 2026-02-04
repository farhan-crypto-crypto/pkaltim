<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kulkaltim - Jelajah Kuliner Kaltim</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=999">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top main-nav">
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
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('menu.menu') }}">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('restoran.index') }}">Restoran</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-wrapper">
        <div class="hero-overlay"></div>
        <div class="container position-relative text-center text-white z-2 px-4">
            <span class="badge-promo">Cita Rasa Borneo</span>
            <h1 class="display-4 fw-bold mb-3 hero-title">Kulkaltim</h1>
            <p class="lead opacity-75 fs-6 fs-md-4">
                Temukan kelezatan autentik Kalimantan Timur <br class="d-none d-md-block"> langsung dari sumbernya.
            </p>
        </div>
        <div class="hero-fade-bottom"></div>
    </header>

    <section class="search-container px-3">
        <div class="container">
            {{-- PERUBAHAN DI SINI: --}}
            {{-- Ubah action agar mengarah ke route 'menu.menu' --}}
            <form method="GET" action="{{ route('menu.menu') }}">
                <div class="search-modern">
                    <div class="search-input-wrapper">
                        <span class="search-icon">🔍</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="search-input"
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
                    <button type="submit" class="search-btn">Cari</button>
                </div>
            </form>
        </div>
    </section>

    <section class="container py-5" id="menu">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title">Menu Terbaru</h2>
            <div class="title-line mx-auto"></div>
        </div>

        <div class="row g-4">
            @foreach ($menus->take(3) as $menu)
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                <a href="{{ route('menu.menudetail', $menu->id_menu) }}" class="card-link w-100">
                    <article class="food-card w-100">
                        <div class="food-image-wrapper">
                            <img src="{{ $menu->foto ?? asset('images/no-image.png') }}" alt="{{ $menu->nama_menu }}">
                            <div class="location-tag">📍 {{ $menu->resto->kota->nama_kota ?? '-' }}</div>
                        </div>

                        <div class="food-info">
                            <h5 class="fw-bold mb-1 text-dark">{{ $menu->nama_menu }}</h5>

                            {{-- Nama Restoran (Ditambahkan) --}}
                            <div class="small text-muted mb-2 fw-medium">
                                <i class="bi bi-shop me-1"></i> {{ $menu->resto->nama_resto }}
                            </div>

                            {{-- Rating --}}
                            <div class="mb-2 text-warning small">
                                @php
                                $avgRating = $menu->reviews->avg('rating') ?? 0;
                                @endphp

                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$avgRating)
                                    <i class="bi bi-star-fill"></i>
                                    @elseif($i - 0.5 <= $avgRating)
                                        <i class="bi bi-star-half"></i>
                                        @else
                                        <i class="bi bi-star"></i>
                                        @endif
                                        @endfor

                                        <span class="text-muted ms-1">({{ number_format($avgRating, 1) }})</span>
                            </div>

                            <p class="text-muted small mb-3 text-truncate">
                                <i class="bi bi-geo-alt-fill text-danger"></i> {{ $menu->resto->alamat ?? 'Alamat tidak tersedia' }}
                            </p>
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
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('menu.menu') }}" class="btn-see-all">
                Lihat Menu Lainnya
                <span class="arrow-icon">→</span>
            </a>
        </div>
    </section>

    <section class="about-section py-5" id="tentang">
        <div class="container py-lg-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-image-stack">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80" alt="Kuliner Kaltim" class="img-fluid rounded-4 shadow-lg main-img">
                        <div class="experience-badge">
                            <h2 class="mb-0 fw-bold text-white">#1</h2>
                            <p class="mb-0 small">Kuliner Kaltim</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="text-success fw-bold text-uppercase tracking-wider">Tentang Kulkaltim</span>
                    <h2 class="display-6 fw-bold mb-4 mt-2">Jendela Kuliner Terbaik di Kalimantan Timur</h2>
                    <p class="text-secondary mb-4">
                        Kulkaltim hadir sebagai panduan digital bagi Anda yang ingin menjelajahi kekayaan rasa Nusantara di Tanah Borneo. Kami menghubungkan pecinta kuliner dengan restoran dan warung legendaris di seluruh Kalimantan Timur.
                    </p>
                    <div class="row g-4 mb-5">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box-small">
                                    <i class="bi bi-search text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold">Pencarian Mudah</h6>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box-small">
                                    <i class="bi bi-geo-alt text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold">Lokasi Akurat</h6>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box-small">
                                    <i class="bi bi-star text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold">Rekomendasi Terpilih</h6>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box-small">
                                    <i class="bi bi-shield-check text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold">Data Terpercaya</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('footer.layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>