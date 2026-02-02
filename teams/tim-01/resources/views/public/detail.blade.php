@extends('layouts.app')

@section('title', $destination->name . ' - Wisata Alam')

@section('content')
    <!-- 
                                          DESIGN IMPLEMENTATION:
                                          - Primary Dark: #0B3B2D
                                          - Accent: #22c55e
                                          - Font: Inter (via Layout)
                                        -->

    <!-- 1. Hero Section (Edge-to-Edge) -->
    <div class="relative w-full h-[85vh] min-h-[600px] bg-gray-900 overflow-hidden">
        <!-- Background Image -->
        @php
            $heroImage = $destination->thumbnail;

            // Calculate dynamic rating for Hero
            $avgRating = $destination->approvedReviews->avg('rating') ?? 0;
            $totalReviews = $destination->approvedReviews->count();
        @endphp
        <img src="{{ $heroImage }}" alt="{{ $destination->name }}"
            class="absolute inset-0 w-full h-full object-cover opacity-90">

        <!-- Dark Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

        <!-- Content -->
        <div class="absolute inset-0 flex flex-col justify-end pb-20">
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 relative" data-aos="fade-up" data-aos-delay="200">

                <!-- Breadcrumbs/Tag -->
                <div class="mb-6 flex items-center space-x-3 text-sm font-medium text-white/80">
                    <span class="bg-white/10 backdrop-blur-md border border-white/20 px-3 py-1 rounded-full text-white">
                        <i class="fa-solid fa-location-dot mr-2 text-[#22c55e]"></i>
                        {{ $destination->address ?? 'Kalimantan Timur' }}
                    </span>
                    <span class="hidden md:inline">/</span>
                    <span class="hidden md:inline hover:text-white transition">Destinasi</span>
                    <span class="hidden md:inline">/</span>
                    <span class="text-[#22c55e] hidden md:inline">{{ $destination->category->name ?? 'Wisata' }}</span>
                </div>

                <!-- Title -->
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-4 tracking-tight leading-tight">
                    {{ $destination->name }}
                </h1>

                <!-- Subtitle & Rating -->
                <div class="flex flex-col md:flex-row md:items-center gap-6 text-white/90">
                    <p class="text-lg md:text-xl font-light max-w-2xl opacity-90">
                        {{ \Illuminate\Support\Str::limit($destination->description, 100) }}
                    </p>

                    <div
                        class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-2xl border border-white/10">
                        <i class="fa-solid fa-star text-yellow-400 text-xl"></i>
                        <div>
                            <span class="text-xl font-bold text-white">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-xs text-gray-300 block leading-none">dari {{ $totalReviews }} ulasan</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Arrows (Bottom Left) -->
                <!-- Navigation Arrows (Bottom Left) -->
                <div class="hidden md:flex absolute bottom-0 right-4 lg:right-8 gap-4 translate-y-1/2 z-10">
                    @if($prevDestination)
                        <a href="{{ route('destination.show', $prevDestination->slug) }}"
                            class="w-14 h-14 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-[#22c55e] hover:border-[#22c55e] transition-all duration-300 group"
                            title="Sebelumnya: {{ $prevDestination->name }}">
                            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        </a>
                    @endif

                    @if($nextDestination)
                        <a href="{{ route('destination.show', $nextDestination->slug) }}"
                            class="w-14 h-14 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-[#22c55e] hover:border-[#22c55e] transition-all duration-300 group"
                            title="Selanjutnya: {{ $nextDestination->name }}">
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Main Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 bg-[#F8F9FA]">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

            <!-- Left Column (Main Content) -->
            <div class="lg:col-span-2 space-y-12">

                <!-- Description -->
                <section data-aos="fade-up" data-aos-delay="300">
                    <h2 class="text-2xl font-bold text-[#0B3B2D] mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-[#22c55e] rounded-full"></span>
                        Tentang Destinasi
                    </h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed text-justify">
                        {{ $destination->description }}
                    </div>
                </section>

                <!-- Facilities Grid -->
                <section data-aos="fade-up" data-aos-delay="400">
                    <h2 class="text-2xl font-bold text-[#0B3B2D] mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-[#22c55e] rounded-full"></span>
                        Fasilitas
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($destination->facilities as $facility)
                            <div
                                class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center gap-3 hover:shadow-md transition-shadow group">
                                <div
                                    class="w-12 h-12 rounded-full bg-[#ecfdf5] flex items-center justify-center text-[#105e43] group-hover:bg-[#0B3B2D] group-hover:text-white transition-colors duration-300">
                                    <i class="fa-solid {{ $facility->icon_class }} text-lg"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 text-center">{{ $facility->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Photo Gallery Grid -->
                @if($destination->images->count() > 0)
                    <section data-aos="fade-up" data-aos-delay="450">
                        <h2 class="text-2xl font-bold text-[#0B3B2D] mb-6 flex items-center gap-3">
                            <span class="w-8 h-1 bg-[#22c55e] rounded-full"></span>
                            Galeri {{ $destination->name }}
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($destination->images as $image)
                                <div
                                    class="rounded-xl overflow-hidden h-40 md:h-52 shadow-sm border border-gray-100 group relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Galeri {{ $destination->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Map Section -->
                <section data-aos="fade-up" data-aos-delay="500">
                    <h2 class="text-2xl font-bold text-[#0B3B2D] mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-[#22c55e] rounded-full"></span>
                        Lokasi Maps
                    </h2>
                    <div class="w-full h-80 bg-gray-200 rounded-2xl overflow-hidden relative shadow-inner">
                        @php
                            $mapQuery = $destination->address ?? 'Kalimantan Timur';
                            if ($destination->latitude && $destination->longitude) {
                                $mapQuery = $destination->latitude . ',' . $destination->longitude;
                            }
                        @endphp
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                        </iframe>
                        <div
                            class="absolute bottom-4 right-4 bg-white px-4 py-2 rounded-lg shadow-lg text-xs font-bold text-gray-700">
                            {{ $destination->address }}
                        </div>
                    </div>
                </section>

                <!-- Reviews -->
                <section data-aos="fade-up" data-aos-delay="600">
                    <h2 class="text-2xl font-bold text-[#0B3B2D] mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-[#22c55e] rounded-full"></span>
                        Tulis Ulasan
                    </h2>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(!session('success'))
                        <!-- Review Form -->
                        <form action="{{ route('review.store', $destination->id) }}" method="POST"
                            class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-12">
                            @csrf
                            <div class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label for="visitor_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                        Anda</label>
                                    <input type="text" name="visitor_name" id="visitor_name" required
                                        class="w-full rounded-xl border-gray-300 focus:border-[#22c55e] focus:ring focus:ring-[#22c55e]/20 transition-colors"
                                        placeholder="Masukkan nama lengkap">
                                </div>

                                <!-- Rating -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                    <div class="flex gap-2">
                                        <select name="rating" required
                                            class="w-full sm:w-auto rounded-xl border-gray-300 focus:border-[#22c55e] focus:ring focus:ring-[#22c55e]/20 transition-colors">
                                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                            <option value="3">⭐⭐⭐ (Cukup)</option>
                                            <option value="2">⭐⭐ (Kurang)</option>
                                            <option value="1">⭐ (Sangat Kurang)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                                    <textarea name="comment" id="comment" rows="4" required
                                        class="w-full rounded-xl border-gray-300 focus:border-[#22c55e] focus:ring focus:ring-[#22c55e]/20 transition-colors"
                                        placeholder="Bagikan pengalaman Anda..."></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-[#0B3B2D] text-white font-bold py-3 px-6 rounded-xl hover:bg-[#072d22] transition-colors flex items-center justify-center gap-2">
                                    <i class="fa-regular fa-paper-plane"></i>
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    @endif

                    <h2 class="text-2xl font-bold text-[#0B3B2D] mb-8 flex items-center gap-3">
                        <span class="w-8 h-1 bg-[#22c55e] rounded-full"></span>
                        Ulasan Pengunjung
                    </h2>

                    <!-- Review Summary -->
                    <div
                        class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row gap-8 items-center">
                        <div class="text-center md:text-left min-w-[150px]">
                            <div class="text-6xl font-black text-[#0B3B2D]">{{ number_format($avgRating, 1) }}</div>
                            <div class="flex justify-center md:justify-start text-yellow-400 text-sm my-2 gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <i class="fa-solid fa-star"></i>
                                    @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) > 0)
                                        <i class="fa-solid fa-star-half-stroke"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-sm text-gray-400 tracking-wide font-medium">{{ number_format($totalReviews) }}
                                Reviews</p>
                        </div>

                        <div class="flex-1 w-full space-y-3">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $count = $destination->approvedReviews->where('rating', $star)->count();
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold text-gray-400 w-3">{{ $star }}</span>
                                    <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#22c55e] rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span No new commits yet. Enjoy your day!
                                        class="text-xs text-gray-400 w-6 text-right">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Review Cards List -->
                    <div class="space-y-6">
                        @foreach($destination->approvedReviews as $review)
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->visitor_name) }}&background=0B3B2D&color=fff"
                                    class="w-12 h-12 rounded-full object-cover shadow-sm">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $review->visitor_name }}</h4>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                        <span class="flex text-yellow-400">
                                            @for($i = 0; $i < $review->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                                        </span>
                                        <span>• {{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">"{{ $review->comment }}"</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

            </div>

            <!-- Right Column (Sidebar) -->
            <div class="lg:col-span-1">
                <aside class="sticky top-6 space-y-6" data-aos="fade-left" data-aos-delay="700">





                </aside>
            </div>

        </div>
    </div>
@endsection