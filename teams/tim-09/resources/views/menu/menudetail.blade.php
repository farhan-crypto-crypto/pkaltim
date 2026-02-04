<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->nama_menu }} | Kulkaltim</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/menudetail.css') }}">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
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
                <ul class="navbar-nav ms-auto gap-lg-4">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('menu.menu') }}">Menu</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container my-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-5">
                    <div class="detail-card-img shadow-sm">
                        <img src="{{ $menu->foto ?? 'https://via.placeholder.com/500' }}" 
                             class="menu-img w-100 rounded-3" 
                             alt="{{ $menu->nama_menu }}"
                             onerror="this.src='https://via.placeholder.com/500?text=No+Image';">
                    </div>
                </div>

                <div class="col-md-7">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3 d-inline-block fw-bold">
                        📍 {{ $menu->resto->kota->nama_kota ?? '-' }}
                    </span>
                    <h1 class="fw-bold mb-2">{{ $menu->nama_menu }}</h1>
                    <p class="text-muted mb-4"><i class="bi bi-shop me-2 text-success"></i>{{ $menu->resto->nama_resto ?? '-' }}</p>

                    <div class="info-box p-4 shadow-sm border rounded-3">
                        <div class="row">
                            <div class="col-6 border-end">
                                <p class="text-muted small mb-1">HARGA</p>
                                <h4 class="price-text mb-0 fw-bold text-success">Rp {{ number_format($menu->harga, 0, ',', '.') }}</h4>
                            </div>
                            <div class="col-6 ps-4">
                                <p class="text-muted small mb-1">RATA-RATA RATING</p>
                                <div class="text-warning small d-flex align-items-center">
                                    @php $avgRating = $menu->reviews->avg('rating') ?? 0; @endphp
                                    
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $avgRating) <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $avgRating) <i class="bi bi-star-half"></i>
                                        @else <i class="bi bi-star"></i> @endif
                                    @endfor
                                    
                                    <span class="text-dark ms-2 fw-bold fs-5">{{ number_format($avgRating, 1) }}</span>
                                    <span class="text-muted small ms-1">({{ $menu->reviews->count() }} Ulasan)</span>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3 opacity-25">
                        <p class="mb-0 small text-muted">
                            <strong>Alamat:</strong> {{ $menu->resto->alamat ?? 'Alamat tidak tersedia' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-5">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                <h5 class="fw-bold mb-3 text-center">Tentang Menu</h5>
                <p class="text-muted mb-0 text-center lh-lg">
                    {{ $menu->deskripsi ?? 'Deskripsi menu belum tersedia.' }}
                </p>
            </div>
        </div>

        <div class="container my-5">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold mb-0">Review Pengunjung ({{ $menu->reviews->count() }})</h4>
                    <small class="text-muted">Apa kata mereka tentang menu ini?</small>
                </div>
                
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalReview">
                    <i class="bi bi-pencil-square me-2"></i> Tulis Review
                </button>
            </div>

            <div class="review-scroll-container d-flex gap-3 overflow-auto pb-3">
                @forelse ($menu->reviews as $review)
                <div class="review-card-wrapper" style="min-width: 300px;">
                    <div class="card review-card h-100 p-3 border shadow-sm rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                {{ substr($review->nama_user, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $review->nama_user }}</h6>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        
                        <div class="text-warning small mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $review->rating >= $i ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        
                        <p class="text-muted small mb-0">"{{ $review->komentar ?? 'Enak banget!' }}"</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 bg-light rounded-4 w-100">
                    <i class="bi bi-chat-square-dots display-4 text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">Belum ada review. Jadilah yang pertama memberikan ulasan!</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="container-fluid px-0 mb-5">
            <iframe 
                src="https://maps.google.com/maps?q={{ $menu->resto->latitude }},{{ $menu->resto->longitude }}&hl=id&z=15&output=embed" 
                class="map-frame w-100" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
            
            <div class="text-center my-4">
                <a href="https://www.google.com/maps/search/?api=1&query={{ $menu->resto->latitude }},{{ $menu->resto->longitude }}" 
                   target="_blank" 
                   class="btn btn-success px-4 rounded-pill fw-bold shadow-sm">
                    📍 Buka Google Maps
                </a>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalReview" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Tulis Review Kamu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('menu.review.store', $menu->id_menu) }}" method="POST">
                    @csrf
                    <div class="modal-body pt-4">
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">NAMA KAMU</label>
                            <input type="text" name="nama_user" class="form-control bg-light border-0" placeholder="Contoh: Budi Santoso" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold d-block">BERI RATING</label>
                            <div class="rate">
                                <input type="radio" id="star5" name="rating" value="5" required />
                                <label for="star5" title="5 stars">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star">1 star</label>
                            </div>
                            <div class="clearfix"></div> </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">KOMENTAR</label>
                            <textarea name="komentar" class="form-control bg-light border-0" rows="3" placeholder="Ceritakan pengalamanmu..." required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 pt-0 pb-4 px-3">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Kirim Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer.layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>