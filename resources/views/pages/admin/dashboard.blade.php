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
@endphp

<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-4">
                        <h5>{{ $greeting }}, {{ auth()->user()->fullname }}</h5>
                        <p>Data Hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

                        {{-- Statistik Harian --}}
                        <div class="row mb-4">
                            <div class="col-12 col-md-4 mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div
                                            class="col-md-4 border border-white justify-content-center align-items-center d-flex">
                                            <div
                                                class="fs-3 fw-bold {{ $persenPengunjung >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $persenPengunjung >= 0 ? '+' : '' }}{{ number_format($persenPengunjung) }}%
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Data Pengunjung</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            <div class="row">
                                                <div class="col">
                                                    Hari Ini: {{ $dataPengunjung }} <br>
                                                    Kemarin: {{ $dataPengunjungKemarin }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div
                                            class="col-md-4 border border-white justify-content-center align-items-center d-flex">
                                            <div
                                                class="fs-3 fw-bold
                                                {{ $persenPeminjam >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $persenPeminjam >= 0 ? '+' : '' }}{{ number_format($persenPeminjam) }}%
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Data Pinjam</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            <div class="row">
                                                <div class="col">
                                                    Hari Ini: {{ $dataPeminjam }} <br>
                                                    Kemarin: {{ $dataPeminjamKemarin }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4  mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div
                                            class="col-md-4 border border-white justify-content-center align-items-center d-flex">
                                            <div
                                                class="fs-3 fw-bold
                                            {{ $persenKembali >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $persenKembali >= 0 ? '+' : '' }}{{ number_format($persenKembali) }}%
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Data Kembali</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            <div class="row">
                                                <div class="col">
                                                    Hari Ini: {{ $dataKembali }} <br>
                                                    Kemarin: {{ $dataKembaliKemarin }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>
                        <h5>
                            Rekapan Denda
                        </h5>
                        <div class="row mb-4">
                            <div class="col-12 col-md-4  mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Hari Ini</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            Rp {{ number_format($denda, 0, ',', '.') }}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Kemarin</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            Rp {{ number_format($dendaKemarin, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4  mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Bulan Ini</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            Rp {{ number_format($dendabulan, 0, ',', '.') }}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Bulan Kemarin</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            Rp {{ number_format($dendabulanKemarin, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4  mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Tahun Ini</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            Rp {{ number_format($dendatahun, 0, ',', '.') }}

                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Tahun Kemarin</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            Rp {{ number_format($dendatahunKemarin, 0, ',', '.') }}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Buku Terfavorit --}}
                        <h6>Buku Terfavorit</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2 justify-content-md-evenly mb-4">
                            @forelse ($bukuFavorit as $buku)
                                <div class="bg-dark text-white p-3 text-center rounded buku-card"
                                    style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#globalBukuModal" data-judul="{{ $buku->judul }}"
                                    data-no_regis="{{ $buku->no_regis }}" data-pengarang="{{ $buku->pengarang }}"
                                    data-penerbit="{{ $buku->penerbit }}" data-tahun="{{ $buku->tahun }}"
                                    data-stok="{{ $buku->stok }}" data-total_pinjam="{{ $buku->total_pinjam }}"
                                    data-keterangan="{{ $buku->keterangan }}"
                                    data-cover="{{ asset('storage/' . $buku->file_cover) }}"
                                    data-file="{{ asset('storage/' . $buku->file_buku) }}"
                                    data-rute="{{ route('peminjaman-siswa.post', $buku->no_regis) }}">
                                    <img src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-2"
                                        alt="{{ $buku->judul }}" style="height: 180px; object-fit: cover;">
                                    <div class="fs-5">{{ Str::limit($buku->judul, 20) }}</div>
                                    <div class="fs-5 mt-2">{{ $buku->total_pinjam }}x <br> Peminjaman</div>
                                </div>
                            @empty
                                <div class="text-center fs-5">Belum Ada Buku Terfavorit</div>
                            @endforelse
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Konten Sering Dilihat --}}
                        <h6>Konten Digital Sering Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2 justify-content-md-evenly mb-4">
                            @foreach ($kontenSeringDilihat as $konten)
                                <div class="bg-dark text-white p-3 text-center konten-card rounded"
                                    style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#kontenModal" data-judul="{{ $konten->judul }}"
                                    data-jenis="{{ $konten->jenis }}" data-url="{{ $konten->url }}"
                                    data-file="{{ $konten->file_path }}" data-pengarang="{{ $konten->pengarang }}"
                                    data-penerbit="{{ $konten->penerbit }}" data-dilihat="{{ $konten->dilihat }}">
                                    <img src="{{ asset('storage/' . $konten->cover ?? '') }}" class="img-fluid mb-2"
                                        style="height: 180px; object-fit: cover;">
                                    <div class="fs-5">{{ Str::limit($konten->judul, 20) }}</div>
                                    <div class="fs-5 mt-2">{{ $konten->dilihat }}x <br> Dilihat</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>


                        {{-- Wajib Dilihat --}}
                        <h6>Wajib Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2 justify-content-md-evenly mb-4">
                            @forelse ($wajibDilihat as $item)
                                {{-- Jika item adalah Buku --}}
                                @if (isset($item->no_regis))
                                    <div class="bg-dark text-white p-3 text-center rounded buku-card"
                                        style="width: 215px; cursor: pointer;" data-bs-toggle="modal"
                                        data-bs-target="#globalBukuModal" data-judul="{{ $item->judul }}"
                                        data-no_regis="{{ $item->no_regis }}"
                                        data-pengarang="{{ $item->pengarang }}"
                                        data-penerbit="{{ $item->penerbit }}" data-tahun="{{ $item->tahun }}"
                                        data-stok="{{ $item->stok }}"
                                        data-total_pinjam="{{ $item->total_pinjam }}"
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
                                        data-pengarang="{{ $item->pengarang }}"
                                        data-cover="{{ asset('storage/' . $item->cover) }}"
                                        data-penerbit="{{ $item->penerbit }}" data-url="{{ $item->url }}"
                                        data-file="{{ $item->file_path }}" data-dilihat="{{ $item->dilihat }}"
                                        data-jenis="{{ $item->jenis }}">
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
            <!-- Modal Global -->
            <div class="modal fade" id="globalBukuModal" tabindex="-1" aria-labelledby="globalBukuModalLabel">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="d-flex justify-content-between mb-3 px-3">
                                <a id="btn-download" href="#" class="btn btn-outline-dark"
                                    download>Download</a>
                                <a id="btn-baca" href="#" class="btn btn-outline-dark" target="_blank">Baca
                                    Online</a>
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
                            <p><strong>Pengarang:</strong> <span id="modalpengarang"></span></p>
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

            <x-app.footer />
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.buku-card');
            const modal = document.getElementById('globalBukuModal');

            cards.forEach(card => {
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

                    document.getElementById('btn-download').href = card.dataset.file;
                    document.getElementById('btn-download').setAttribute('download', card.dataset
                        .judul);
                    document.getElementById('btn-baca').href = card.dataset.file;
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
                    const pengarang = this.getAttribute('data-pengarang');
                    const penerbit = this.getAttribute('data-penerbit');
                    console.log(penerbit);
                    const dilihat = this.getAttribute('data-dilihat');
                    // Isi konten modal
                    document.getElementById('modalJudul').textContent = judul;
                    document.getElementById('modalJenis').textContent = jenis;
                    document.getElementById('modalpengarang').textContent = pengarang;
                    document.getElementById('modalPenerbit').textContent = penerbit;
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
