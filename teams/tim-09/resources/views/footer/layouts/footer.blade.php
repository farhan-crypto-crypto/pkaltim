@yield('footer-modern')
<footer class="footer-modern">
    <div class="container text-center text-md-start">
        <div class="row gy-4">
            <div class="col-md-4">
                <h5 class="fw-bold text-white mb-3">
                    <img
                        src="{{ asset('storage/foto/kulkaltim.png') }}"
                        alt="Kulkaltim Logo"
                        height="32">
                         Kulkaltim
                </h5>
                <p class="text-white-50 small">Gerbang informasi kuliner terbaik di Kalimantan Timur. Temukan kelezatan autentik langsung dari sumbernya.</p>
            </div>

            <div class="col-md-4 text-md-center">
                <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none small">Home</a></li>
                    <li class="mb-2"><a href="{{ route('menu.menu') }}" class="text-white-50 text-decoration-none small">Menu</a></li>
                    <li class="mb-2"><a href="{{ route('restoran.index') }}" class="text-white-50 text-decoration-none small">Restoran</a></li>
                </ul>
            </div>

            <div class="col-md-4 text-md-end">
                <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                <p class="text-white-50 small mb-3">kulkaltim@gmail.com</p>
                <div class="d-flex justify-content-center justify-content-md-end gap-3">
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-facebook"></i></a>
                </div>
            </div>
        </div>
        <hr class="mt-5 border-secondary opacity-25">
        <p class="text-center text-white-50 small mb-0">© 2026 Kulkaltim</p>
    </div>
</footer>