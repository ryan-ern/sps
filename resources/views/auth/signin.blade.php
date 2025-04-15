<x-guest-layout>
    <main class="main-content mt-0">
        <section>
            <style>
                #animated-text {
                    transition: opacity 0.5s ease-in-out;
                }
            </style>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-4">
                                <div class="card-header pb-0 text-left bg-transparent text-center">
                                    <h3 class="font-weight-black text-dark display-6">Selamat Datang Kutu Buku</h3>
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="w-30">
                                    <div class="text-muted mt-2">di Sistem Perpustakaan Sekolah</div>
                                </div>
                                <div class="card-body">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <label>Username</label>
                                        <div class="mb-3">
                                            <input type="text" id="username" name="username" class="form-control"
                                                placeholder="Masukkan username terdaftar"
                                                value="{{ old('username') ? old('username') : '' }}"
                                                aria-label="username" aria-describedby="username-addon">
                                        </div>
                                        <label>Password</label>
                                        <div class="mb-3">
                                            <input type="password" id="password" name="password"
                                                value="{{ old('password') ? old('password') : '' }}"
                                                class="form-control" placeholder="Masukkan password disini"
                                                aria-label="Password" aria-describedby="password-addon">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8"
                                    style="background-image:url('../assets/img/required/perpus.jpg')">
                                    <div
                                        class="blur mt-12 p-4 border border-white border-radius-md position-absolute fixed-bottom m-4">
                                        <h2 id="animated-text" class="mt-3 p-3 text-dark font-weight-bold text-justify">
                                            Lebih banyak anda membaca, lebih banyak hal yang anda ketahui. Lebih banyak
                                            hal yang anda pelajari, lebih banyak tempat yang anda kunjungi. - Dr.
                                            Seuss
                                        </h2>
                                        <h6 class="text-dark text-center text-sm mt-5">Copyright ©
                                            <script>
                                                document.write(new Date().getFullYear())
                                            </script>
                                            SPS - Sistem Perpustakaan Sekolah
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const textElement = document.getElementById("animated-text");
                    const texts = [
                        "Orang yang tidak banyak membaca pasti tidak banyak tahu. Orang yang tidak banyak tahu sangat dekat dengan kebodohan. Dan kebodohan akan sangat dekat dengan kemiskinan. - Helmy Yahya",
                        "Membaca adalah hal penting bagi mereka yang ingin melampaui kehidupan biasa. - Jim Rohn",
                        "Lebih banyak anda membaca, lebih banyak hal yang anda ketahui. Lebih banyak hal yang anda pelajari, lebih banyak tempat yang anda kunjungi. - Dr. Seuss"
                    ];

                    let index = 0;

                    setInterval(() => {
                        textElement.style.opacity = 0;

                        setTimeout(() => {
                            textElement.textContent = texts[index];
                            textElement.style.opacity = 1;

                            index = (index + 1) % texts.length;
                        }, 500);
                    }, 10000);
                });
            </script>
        </section>
    </main>

</x-guest-layout>
