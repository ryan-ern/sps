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
                                <button class="btn btn-dark float-end" data-bs-toggle="modal"
                                    data-bs-target="#kunjunganModal">Isi Kunjungan</button>
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

                        {{-- Filter --}}
                        <form method="GET"
                            class="d-flex flex-wrap justify-content-center justify-content-md-evenly gap-lg-5 gap-2">
                            <a class="btn w-lg-15 w-100 btn-{{ !$jenis ? 'primary' : 'dark' }}"
                                href="{{ route('dashboard') }}">Semua Buku</a>

                            <button class="btn w-lg-15 w-100 btn-{{ $jenis === 'paket' ? 'primary' : 'dark' }}"
                                name="jenis" value="paket">Buku Paket</button>

                            <button class="btn w-lg-15 w-100 btn-{{ $jenis === 'referensi' ? 'primary' : 'dark' }}"
                                name="jenis" value="referensi">Buku Referensi</button>

                            <button class="btn w-lg-15 w-100 btn-{{ $jenis === 'digital' ? 'primary' : 'dark' }}"
                                name="jenis" value="digital">Konten Digital</button>
                        </form>

                        @if (!$search)
                            @if ($jenis !== 'digital')
                                <div class="border-5 border-bottom border-dark my-3"></div>
                                {{-- Buku Terfavorit --}}
                                <h6>Buku Terfavorit</h6>
                                <div
                                    class="d-flex flex-wrap justify-content-center gap-3 justify-content-md-evenly mb-4">
                                    @forelse ($bukuFavorit as $buku)
                                        <div class="bg-dark text-white p-3 text-center rounded buku-card"
                                            style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                            data-bs-target="#globalBukuModal" data-judul="{{ $buku->judul }}"
                                            data-no_regis="{{ $buku->no_regis }}"
                                            data-pengarang="{{ $buku->pengarang }}"
                                            data-penerbit="{{ $buku->penerbit }}" data-tahun="{{ $buku->tahun }}"
                                            data-stok="{{ $buku->stok }}"
                                            data-total_pinjam="{{ $buku->total_pinjam }}"
                                            data-keterangan="{{ $buku->keterangan }}"
                                            data-cover="{{ asset('storage/' . $buku->file_cover) }}"
                                            data-file="{{ asset('storage/' . $buku->file_buku) }}"
                                            data-rute="{{ route('peminjaman-siswa.post', $buku->no_regis) }}">
                                            <img src="{{ asset('storage/' . $buku->file_cover) }}"
                                                class="img-fluid mb-2" alt="{{ $buku->judul }}"
                                                style="height: 180px; object-fit: cover;">
                                            <div class="fs-5">{{ Str::limit($buku->judul, 20) }}</div>
                                            <div class="fs-5 mt-2">{{ $buku->total_pinjam }}x <br> Peminjaman</div>
                                        </div>
                                    @empty
                                        <div class="text-center fs-5">Belum Ada Buku Terfavorit</div>
                                    @endforelse
                                </div>
                            @endif

                            @if (!$jenis || $jenis === 'digital')
                                <div class="border-5 border-bottom border-dark my-3"></div>
                                {{-- Konten Digital Sering Dilihat --}}
                                <h6>Konten Digital Sering Dilihat</h6>
                                <div
                                    class="d-flex flex-wrap justify-content-center gap-3 justify-content-md-evenly mb-4">
                                    @foreach ($kontenSeringDilihat as $konten)
                                        <div class="bg-dark text-white p-3 text-center konten-card rounded"
                                            style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                            data-bs-target="#kontenModal" data-judul="{{ $konten->judul }}"
                                            data-jenis="{{ $konten->jenis }}" data-url="{{ $konten->url }}"
                                            data-file="{{ $konten->file_path }}" data-pembuat="{{ $konten->pembuat }}"
                                            data-dilihat="{{ $konten->dilihat }}">
                                            <img src="{{ asset('storage/' . $konten->cover ?? '') }}"
                                                class="img-fluid mb-2" style="height: 180px; object-fit: cover;">
                                            <div class="fs-5">{{ Str::limit($konten->judul, 20) }}</div>
                                            <div class="fs-5 mt-2">{{ $konten->dilihat }}x <br> Dilihat</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Wajib Dilihat --}}
                        <h6>Wajib Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-3 justify-content-md-evenly mb-4">
                            @forelse ($wajibDilihat as $item)
                                {{-- Jika item adalah Buku --}}
                                @if (isset($item->no_regis))
                                    <div class="bg-dark text-white p-3 text-center rounded buku-card"
                                        style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                        data-bs-target="#globalBukuModal" data-judul="{{ $item->judul }}"
                                        data-no_regis="{{ $item->no_regis }}" data-pengarang="{{ $item->pengarang }}"
                                        data-penerbit="{{ $item->penerbit }}" data-tahun="{{ $item->tahun }}"
                                        data-stok="{{ $item->stok }}" data-total_pinjam="{{ $item->total_pinjam }}"
                                        data-keterangan="{{ $item->keterangan }}"
                                        data-cover="{{ asset('storage/' . $item->file_cover) }}"
                                        data-file="{{ asset('storage/' . $item->file_buku) }}"
                                        data-rute="{{ route('peminjaman-siswa.post', $item->no_regis) }}">
                                        <img src="{{ asset('storage/' . $item->file_cover) }}" class="img-fluid mb-2"
                                            alt="{{ $item->judul }}" style="height: 180px; object-fit: cover;">
                                        <div class="fs-5">{{ Str::limit($item->judul, 20) }}</div>
                                        <div class="fs-5 mt-2">{{ $item->total_pinjam }}x <br> Peminjaman</div>
                                    </div>

                                    {{-- Jika item adalah Konten Digital --}}
                                @elseif (isset($item->jenis))
                                    <div class="bg-dark text-white p-3 text-center rounded konten-card"
                                        style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                        data-bs-target="#kontenModal" data-judul="{{ $item->judul }}"
                                        data-pembuat="{{ $item->pembuat }}"
                                        data-cover="{{ asset('storage/' . $item->cover) }}"
                                        data-url="{{ $item->url }}" data-file_path="{{ $item->file_path }}"
                                        data-dilihat="{{ $item->dilihat }}" data-jenis="{{ $item->jenis }}">
                                        <img src="{{ asset('storage/' . $item->cover) }}" class="img-fluid mb-2"
                                            alt="{{ $item->judul }}" style="height: 180px; object-fit: cover;">
                                        <div class="fs-5">{{ Str::limit($item->judul, 20) }}</div>
                                        <div class="fs-5 mt-2">{{ $item->dilihat }}x <br> Dilihat</div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center fs-5">Belum Ada Data Ditampilkan</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal isi kunjungan --}}
            <div class="modal fade" id="kunjunganModal" tabindex="-1" aria-labelledby="kunjunganModalLabel"
                data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('kunjungan.store') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <h5 class="text-center">Selamat Datang {{ auth()->user()->fullname }}, <br> Harap
                                    lengkapi
                                    form
                                    kunjungan <br> di bawah
                                    ini terlebih dahulu</h5>
                                <div class="container">
                                    <p>
                                        <label for="fullname" class="form-label">Nama Lengkap:</label>
                                        <input type="text" name="fullname" id="fullname"
                                            value="{{ auth()->user()->fullname }}" class="form-control" readonly>
                                    </p>
                                    <p>
                                        <label for="nisn" class="form-label">NISN:</label>
                                        <input type="text" name="nisn" id="nisn"
                                            value="{{ auth()->user()->nisn }}" class="form-control" readonly>
                                    </p>
                                    <p>
                                        <label for="kelas" class="form-label">Kelas:</label>
                                        <input type="text" name="kelas" id="kelas"
                                            value="{{ auth()->user()->kelas }}" class="form-control" readonly>
                                    </p>
                                    <p>
                                        <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control"
                                            placeholder="Keterangan" required></textarea>
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if ($kunjunganHariIni)
                                    <button class="btn btn-primary me-2" type="reset" id="closeModal"
                                        data-bs-dismiss="modal">Tutup</button>
                                @endif
                                <button class="btn btn-success" type="submit" id="closeModal"
                                    data-bs-dismiss="modal">Simpan</button>
                            </div>
                        </form>
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
                            <button class="btn btn-primary me-2" id="closeModal"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                        <div class="modal-body">
                            <h4 id="modalJudul" class="mb-3"></h4>
                            <p><strong>Jenis:</strong> <span id="modalJenis"></span></p>
                            <p><strong>Pembuat:</strong> <span id="modalPembuat"></span></p>
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
    @if (!$kunjunganHariIni)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const modal = new bootstrap.Modal(document.getElementById('kunjunganModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
            });
        </script>
    @endif

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
                });
            });

            // Modal Konten
            const kontenCards = document.querySelectorAll('.konten-card');

            kontenCards.forEach(card => {
                card.addEventListener('click', function() {
                    const judul = this.getAttribute('data-judul');
                    const jenis = this.getAttribute('data-jenis'); // 'video' atau 'buku digital'
                    const file = this.getAttribute('data-file'); // file_path (untuk buku digital)
                    const url = this.getAttribute('data-url'); // link (untuk video)
                    const pembuat = this.getAttribute('data-pembuat');
                    const dilihat = this.getAttribute('data-dilihat');

                    // Isi konten modal
                    document.getElementById('modalJudul').textContent = judul;
                    document.getElementById('modalJenis').textContent = jenis;
                    document.getElementById('modalPembuat').textContent = pembuat;
                    document.getElementById('modalDilihat').textContent = dilihat;

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
                                    <iframe src="${embedUrl}" frameborder="0" allowfullscreen></iframe>
                                </div>`;
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
