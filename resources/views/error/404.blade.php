<x-guest-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="custom-bg text-dark">
            <div class="d-flex align-items-center justify-content-center min-vh-100 px-2">
                <div class="text-center">
                    <h1 class="display-1 fw-bold">Opps!</h1>
                    <p class="fs-2 fw-medium mt-4">Apa yang anda cari?</p>
                    <img src="{{ asset('assets/img/404.gif') }}" class="img-fluid" width="80%" alt="404">
                    <p class="mt-4 mb-5">Sepertinya kamu tersesat, Segera kembali yuk</p>
                    <a href="/" class="btn btn-light fw-semibold rounded-pill px-4 py-2 custom-btn">
                        Tekan disini
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
