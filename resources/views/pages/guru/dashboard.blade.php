@php
    $hour = now()->format('H');

    if ($hour >= 5 && $hour < 11) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 11 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }

    $jenis = request('jenis');
    $search = request('search');
@endphp

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="row mb-3">
                            <div class="col">
                                <h5>{{ $greeting }}, {{ auth()->user()->fullname }}</h5>
                            </div>
                            <div class="col">
                            </div>
                        </div>

                        {{-- Filter --}}
                        <div class="row mb-3">
                            <div class="col-md-8 mx-auto">
                                <form method="GET">
                                    <div class="input-group border border-dark">
                                        <input type="text" class="form-control"
                                            placeholder="Ketik Judul Buku Disini, Untuk Pencarian"
                                            aria-label="Ketik Judul Buku Disini, Untuk Pencarian"
                                            aria-describedby="button-addon2" name="search">
                                        <button class="btn btn-success m-1 px-5">Cari</button>
                                    </div>
                                </form>
                            </div>
                        </div>



                        @if (!$jenis || $jenis === 'digital')
                            <div class="border-5 border-bottom border-dark my-3"></div>
                            {{-- Konten Digital Sering Dilihat --}}
                            <h6>Konten Digital Sering Dilihat</h6>
                            <div class="d-flex flex-wrap justify-content-center gap-2 justify-content-md-evenly mb-4">
                                @foreach ($kontenSeringDilihat as $konten)
                                    <div class="bg-dark text-white p-3 text-center konten-card rounded"
                                        style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                        data-bs-target="#kontenModal" data-judul="{{ $konten->judul }}"
                                        data-jenis="{{ $konten->jenis }}" data-url="{{ $konten->url }}"
                                        data-id="{{ $konten->id }}" data-file="{{ $konten->file_path }}"
                                        data-pengarang="{{ $konten->pengarang }}"
                                        data-penerbit="{{ $konten->penerbit }}" data-dilihat="{{ $konten->dilihat }}">
                                        <img src="{{ asset('storage/' . $konten->cover ?? '') }}"
                                            class="img-fluid mb-2" style="height: 180px; object-fit: cover;">
                                        <div class="fs-5">{{ Str::limit($konten->judul, 20) }}</div>
                                        <div class="fs-5 mt-2">{{ $konten->dilihat }}x <br> Dilihat</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Wajib Dilihat --}}
                        <h6>Wajib Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2 justify-content-md-evenly mb-4">
                            @forelse ($wajibDilihat as $item)
                                {{-- {{ dd($item->id) }} --}}
                                {{-- Jika item adalah Konten Digital --}}
                                <div class="bg-dark text-white p-3 text-center rounded konten-card"
                                    style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#kontenModal" data-judul="{{ $item->judul }}"
                                    data-pengarang="{{ $item->pengarang }}" data-id="{{ $item->id }}"
                                    data-penerbit="{{ $item->penerbit }}"
                                    data-cover="{{ asset('storage/' . $item->cover) }}" data-url="{{ $item->url }}"
                                    data-file="{{ $item->file_path }}" data-dilihat="{{ $item->dilihat }}"
                                    data-jenis="{{ $item->jenis }}">
                                    <img src="{{ asset('storage/' . $item->cover) }}" class="img-fluid mb-2"
                                        alt="{{ $item->judul }}" style="height: 180px; object-fit: cover;">
                                    <div class="fs-5">{{ Str::limit($item->judul, 20) }}</div>
                                    <div class="fs-5 mt-2">{{ $item->dilihat }}x <br> Dilihat</div>
                                </div>
                            @empty
                                <div class="text-center fs-5">Belum Ada Data Ditampilkan</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Konten Digital -->
            <div class="modal fade" id="kontenModal" tabindex="-1" aria-labelledby="kontenModalLabel"
                data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content bg-light">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kontenModalLabel">Detail Konten Digital</h5>
                            <button class="btn btn-primary me-2" id="closeModal" data-bs-dismiss="modal">Tutup</button>
                        </div>
                        <div class="modal-body">
                            <h4 id="modalJudul" class="mb-3"></h4>
                            <p><strong>Jenis:</strong> <span id="modalJenis"></span></p>
                            <p><strong>pengarang:</strong> <span id="modalpengarang"></span></p>
                            <p><strong>Penerbit:</strong> <span id="modalPenerbit"></span></p>
                            <p><strong>Jumlah Dilihat:</strong> <span id="modalDilihat"></span>x</p>
                            <p id="tab_baru"><strong>Buka di tab baru:</strong> <a id="modalBuka" href="#"
                                    target="_blank">Klik di sini</a></p>

                            <div id="kontenPreview" class="mt-2">
                                <!-- Preview Konten akan muncul di sini -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Buku -->
            <div class="modal fade" id="globalBukuModal" tabindex="-1" aria-labelledby="globalBukuModalLabel">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="d-flex justify-content-end mb-3 px-3">
                                <form id="form-pinjam" method="POST" action="">
                                    @csrf
                                    <button type="submit" class="btn btn-success  me-2">Pinjam</button>
                                    <button type="reset" class="btn btn-primary" id="closeModal"
                                        data-bs-dismiss="modal">Tutup</button>
                                </form>
                            </div>
                            <img id="modal-cover" src="" class="img-fluid mb-3"
                                style="max-height: 300px; object-fit: contain;">
                            <div class="text-start px-3">
                                <h6 class="fw-bold">Detail Buku</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <p>Judul : <span id="modal-judul"></span></p>
                                        <p>Pengarang : <span id="modal-pengarang"></span></p>
                                        <p>Penerbit : <span id="modal-penerbit"></span></p>
                                        <p>Tahun : <span id="modal-tahun"></span></p>
                                    </div>
                                    <div class="col-6">
                                        <p>Stok Buku : <span id="modal-stok"></span></p>
                                        <p>Jumlah dipinjam : <span id="modal-pinjam"></span> Kali</p>
                                    </div>
                                </div>
                                <h6 class="fw-bold mt-3">Keterangan</h6>
                                <p id="modal-keterangan" class="text-justify"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bukuCards = document.querySelectorAll('.buku-card');

            // Modal Buku
            bukuCards.forEach(card => {
                card.addEventListener('click', () => {
                    document.getElementById('modal-cover').src = card.dataset.cover;
                    document.getElementById('modal-judul').textContent = card.dataset.judul;
                    document.getElementById('modal-pengarang').textContent = card.dataset.pengarang;
                    document.getElementById('modal-penerbit').textContent = card.dataset.penerbit;
                    document.getElementById('modal-tahun').textContent = card.dataset.tahun;
                    document.getElementById('modal-stok').textContent = card.dataset.stok;
                    document.getElementById('modal-pinjam').textContent = card.dataset.total_pinjam;
                    document.getElementById('modal-keterangan').textContent = card.dataset
                        .keterangan;
                    document.getElementById('form-pinjam').action = card.dataset.rute;

                    const jenis = card.dataset.jenis;
                    const btnBaca = document.getElementById('btn-baca');
                    const file = card.dataset.file;
                    const check = card.dataset.check;
                    // Aktifkan "Baca Online" hanya jika jenis adalah 'paket'
                    if (check != "-") {
                        btnBaca.style.display = 'inline-block';
                        btnBaca.href = file;
                        btnBaca.target = '_blank';
                    } else {
                        btnBaca.style.display = 'none';
                    }

                });
            });

            // Modal Konten
            const kontenCards = document.querySelectorAll('.konten-card');
            const kontenModal = document.getElementById('kontenModal');

            kontenModal.addEventListener('hidden.bs.modal', function() {
                location.reload(); // Refresh halaman
            });

            // Fungsi untuk tambah dilihat
            function tambahDilihat(kontenId) {
                if (!kontenId) return;

                fetch(`/konten-digital/${kontenId}/tambah-dilihat`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('modalDilihat').textContent = data
                                .dilihat;
                        }
                    })
                    .catch(error => {
                        console.error('Gagal memperbarui jumlah dilihat:', error);
                    });
            }

            let ytPlayer = null;
            let ytPlayed = false;

            // Fungsi ini dipanggil otomatis oleh YouTube API saat iframe tersedia
            function onYouTubeIframeAPIReady() {
                const iframe = document.querySelector('#kontenPreview iframe');
                if (iframe) {
                    ytPlayer = new YT.Player(iframe, {
                        events: {
                            'onStateChange': onPlayerStateChange
                        }
                    });
                }
            }

            // Trigger tambah dilihat saat video mulai diputar
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING && !ytPlayed) {
                    ytPlayed = true;
                    const kontenId = document.querySelector('[data-id-konten-video]').getAttribute(
                        'data-id-konten-video');
                    tambahDilihat(kontenId);
                }
            }

            kontenCards.forEach(card => {
                card.addEventListener('click', function() {
                    const judul = this.getAttribute('data-judul');
                    const jenis = this.getAttribute('data-jenis'); // 'video' atau 'buku digital'
                    const file = this.getAttribute('data-file'); // file_path (untuk buku digital)
                    const url = this.getAttribute('data-url'); // link (untuk video)
                    const pengarang = this.getAttribute('data-pengarang');
                    const penerbit = this.getAttribute('data-penerbit');
                    let dilihat = this.getAttribute('data-dilihat');
                    const kontenId = this.getAttribute('data-id');
                    // Isi konten modal
                    document.getElementById('modalJudul').textContent = judul;
                    document.getElementById('modalJenis').textContent = jenis;
                    document.getElementById('modalpengarang').textContent = pengarang;
                    document.getElementById('modalPenerbit').textContent = penerbit;
                    document.getElementById('modalDilihat').textContent = dilihat;

                    const modalBuka = document.getElementById('modalBuka');
                    const kontenPreview = document.getElementById('kontenPreview');



                    // Event saat klik "Klik di sini"
                    modalBuka.addEventListener('click', function() {
                        tambahDilihat(kontenId);
                    });


                    const bukaLink = document.getElementById('modalBuka');
                    bukaLink.href = url || file || '#';
                    bukaLink.target = "_blank";
                    bukaLink.innerHTML = 'Klik di sini';

                    const preview = document.getElementById('kontenPreview');
                    const tabBaru = document.getElementById('tab_baru');
                    preview.innerHTML = '';

                    if (jenis === 'video' && url) {
                        const isYoutube = url.includes('youtube.com') || url.includes('youtu.be');

                        if (isYoutube) {
                            let embedUrl = url;
                            if (url.includes('watch?v=')) {
                                embedUrl = url.replace('watch?v=', 'embed/');
                            } else if (url.includes('youtu.be/')) {
                                const videoId = url.split('youtu.be/')[1];
                                embedUrl = `https://www.youtube.com/embed/${videoId}`;
                            }

                            preview.innerHTML = `
        <div class="ratio ratio-16x9">
            <iframe id="yt-player" src="${embedUrl}?enablejsapi=1" frameborder="0" allowfullscreen></iframe>
        </div>`;

                            preview.setAttribute('data-id-konten-video', kontenId);
                            ytPlayed = false; // Reset flag

                            // Delay sedikit agar iframe sempat masuk ke DOM sebelum buat YT.Player
                            setTimeout(() => {
                                const iframe = document.getElementById('yt-player');
                                if (iframe) {
                                    ytPlayer = new YT.Player(iframe, {
                                        events: {
                                            'onStateChange': onPlayerStateChange
                                        }
                                    });
                                }
                            }, 500);
                        }
                    } else if (jenis === 'buku digital' && file) {
                        preview.innerHTML = `
                            <iframe src="${file}" width="100%" height="500px" frameborder="0">
                                File tidak dapat ditampilkan.
                            </iframe>`;
                    } else {
                        preview.innerHTML =
                            `<p class="text-muted">Konten tidak tersedia untuk ditampilkan.</p>`;
                    }
                });
            });
        });
    </script>


</x-app-layout>
