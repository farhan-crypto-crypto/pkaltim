@extends('layouts.app')

@section('title', 'Daftar Destinasi')

@section('content')

    <!-- Header / Banner -->
    <header class="relative pt-20 pb-20 md:pt-32 md:pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweqCfn2wgXu-qHU1M0N7a2P-QaHG6WCbttRp7K7qq4C5_Nr_hytuXgPT43ihTHsNljoaKSCXGAT1mJzHeBWbu4CVbGUjKpsRNTD20lH0B01WWW-CHV7k5mtOF3pXkOGrZMaICz1W=s1360-w1360-h1020-rw"
                alt="Pantai Melawai" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-[#F8F9FA] dark:to-gray-900">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight" data-aos="fade-down">
                Cari Destinasi
            </h1>


            <div class="max-w-3xl relative z-10 mx-auto bg-white/20 backdrop-blur-md p-2 rounded-full shadow-2xl flex flex-col md:flex-row items-center gap-2 border border-white/30"
                data-aos="fade-up" data-aos-delay="200">
                <div class="flex-1 flex items-center px-6 w-full h-12">
                    <i class="fa-solid fa-search text-white mr-3"></i>
                    <!-- Added ID search-input -->
                    <input type="text" id="search-input" placeholder="Mau kemana hari ini?"
                        class="w-full bg-transparent outline-none text-white placeholder-gray-200">
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 md:px-8 pb-20 -mt-10 relative z-20">
        <div class="flex flex-col lg:flex-row gap-8">

            <aside class="w-full lg:w-3/12 space-y-8" data-aos="fade-right" data-aos-delay="300">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">Filter</h3>
                        <button class="text-xs text-brand-500 font-semibold hover:underline"
                            onclick="window.location.reload()">Reset</button>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Kategori</h4>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" checked
                                    class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                <span class="group-hover:text-brand-500 text-gray-600 dark:text-gray-400">Semua
                                    Wisata</span>
                            </label>
                            @foreach(['Hutan Lindung', 'Sungai & Danau', 'Pantai & Kepulauan', 'Gua & Karst'] as $cat)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox"
                                        class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                    <span class="text-gray-600 dark:text-gray-400 group-hover:text-brand-500">{{ $cat }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Maksimal Harga</h4>
                            <span id="price-label"
                                class="text-xs font-bold text-brand-600 bg-brand-50 dark:bg-brand-900/30 px-2 py-1 rounded-md border border-brand-100 dark:border-brand-900">
                                IDR 500.000
                            </span>
                        </div>

                        <div class="relative w-full">
                            <input id="price-range" type="range" min="0" max="500000" step="50000" value="500000"
                                class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer accent-brand-500 hover:accent-brand-600 focus:outline-none">
                            <div class="flex justify-between text-[10px] text-gray-400 mt-2 font-medium">
                                <span>IDR 0</span>
                                <span>IDR 500.000</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 italic">*Geser untuk filter (kelipatan 50rb)</p>
                    </div>
                </div>
            </aside>

            <div class="w-full lg:w-9/12">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <p class="text-gray-500 mb-4 md:mb-0">Menampilkan <span id="count-display"
                            class="font-bold text-gray-900 dark:text-white">{{ $destinations->count() }}</span> Destinasi
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Urutkan:</span>
                        <select
                            class="bg-transparent font-bold text-gray-900 dark:text-white border-none focus:ring-0 cursor-pointer text-sm">
                            <option>Terpopuler</option>
                            <option>Harga Terendah</option>
                            <option>Rating Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="destination-grid">
                    @forelse($destinations as $item)
                        <div class="destination-card bg-white dark:bg-gray-800 rounded-2xl p-3 shadow-sm hover:shadow-xl group border border-gray-100 dark:border-gray-700 flex flex-col h-full relative"
                            data-price="{{ $item->price ?? 0 }}" data-name="{{ strtolower($item->name) }}" data-aos="zoom-in"
                            data-aos-delay="{{ $loop->index * 100 }}">

                            <!-- Stretched Link -->
                            <a href="{{ route('destination.show', $item->slug) }}" class="absolute inset-0 z-10"
                                aria-label="Lihat detail {{ $item->name }}"></a>

                            <div class="relative rounded-xl overflow-hidden h-48 mb-4">
                                <img src="{{ $item->thumbnail }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                                <span
                                    class="absolute top-3 left-3 bg-brand-500 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-md uppercase tracking-wide relative z-20">
                                    {{ $item->category->name ?? 'Wisata' }}
                                </span>

                                <div
                                    class="absolute top-3 right-3 bg-white dark:bg-gray-900 text-xs font-bold px-2 py-1 rounded-md flex items-center shadow-md dark:text-white relative z-20">
                                    <i class="fa-solid fa-star text-yellow-400 mr-1"></i>
                                    4.8
                                </div>
                            </div>

                            <div class="px-2 pb-2 flex flex-col flex-grow">
                                <h3
                                    class="font-bold text-lg text-gray-900 dark:text-white mb-1 line-clamp-1 group-hover:text-brand-500 destination-name">
                                    {{ $item->name }}</h3>

                                <div class="flex items-center text-gray-400 text-xs mb-4">
                                    <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i>
                                    {{ $item->location ?? 'Kalimantan Timur' }}
                                </div>

                                <div
                                    class="mt-auto flex items-end justify-between border-t border-gray-50 dark:border-gray-700 pt-3">
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-semibold">Mulai Dari</p>
                                        <p class="font-bold text-gray-900 dark:text-white">IDR
                                            {{ number_format($item->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 group-hover:bg-brand-500 group-hover:text-white transform group-hover:rotate-45 transition-all">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300">
                            <p class="text-gray-400">Belum ada data wisata.</p>
                        </div>
                    @endforelse
                </div>

                <div id="no-result-message"
                    class="hidden text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 mt-6">
                    <p class="text-gray-400">Tidak ada wisata yang sesuai kriteria.</p>
                </div>

                <div class="mt-10 flex justify-center">
                    <button
                        class="px-8 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-sm font-bold shadow hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-white">
                        Muat Lebih Banyak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Side Interaction Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const priceSlider = document.getElementById('price-range');
            const priceLabel = document.getElementById('price-label');
            const cards = document.querySelectorAll('.destination-card');
            const noResult = document.getElementById('no-result-message');
            const countDisplay = document.getElementById('count-display');

            // Format Rupiah
            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            };

            // Unified Filter Function
            const filterDestinations = () => {
                const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
                const maxPrice = priceSlider ? parseInt(priceSlider.value) : 500000;

                // Update Price Label
                if (priceLabel) priceLabel.innerText = formatRupiah(maxPrice);

                let visibleCount = 0;

                cards.forEach(card => {
                    const itemPrice = parseInt(card.getAttribute('data-price'));
                    // We added data-name, but fallback to h3 text if needed
                    const itemName = card.getAttribute('data-name') || card.querySelector('.destination-name').innerText.toLowerCase();

                    const matchesSearch = itemName.includes(searchValue);
                    const matchesPrice = itemPrice <= maxPrice;

                    if (matchesSearch && matchesPrice) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Update Count & No Result Message
                if (countDisplay) countDisplay.innerText = visibleCount;

                if (visibleCount === 0) {
                    noResult.classList.remove('hidden');
                } else {
                    noResult.classList.add('hidden');
                }
            };

            // Attach Listeners
            if (searchInput) {
                searchInput.addEventListener('input', filterDestinations);
            }

            if (priceSlider) {
                priceSlider.addEventListener('input', filterDestinations);
            }
        });
    </script>
@endsection