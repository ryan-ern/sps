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
                            <div class="col mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div class="col-md-3 border border-white justify-content-center align-items-center d-flex">
                                            <div class="fs-3">{{ $dataPengunjung }}</div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Data Pengunjung</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            <div class="row">
                                                <div class="col">
                                                     <h2 class="text-success">+20%</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div class="col-md-3 border border-white justify-content-center align-items-center d-flex">
                                            <div class="fs-3">{{ $dataPeminjam }}</div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Data Buku Pinjam</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            <div class="row">
                                                <div class="col">
                                                     <h2 class="text-success">+10%</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-md-0 mb-4">
                                <div class="bg-dark text-white px-4 py-2 rounded">
                                    <div class="row">
                                        <div class="col-md-3 border border-white justify-content-center align-items-center d-flex">
                                            <div class="fs-3">{{ $dataKembali }}</div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="fs-5">Data Buku Kembali</small>
                                                </div>
                                            </div>
                                            <div class="border-2 border-bottom border-light my-3"></div>
                                            <div class="row">
                                                <div class="col">
                                                     <h2 class="text-success">+15%</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Buku Terfavorit --}}
                        <h6>Buku Terfavorit</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-3 justify-content-md-between mb-4">
                            @foreach ($bukuFavorit as $buku)
                            <div class="bg-dark text-white p-3 text-center rounded buku-card"
                                style="width: 215px; cursor: pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#globalBukuModal"
                                data-judul="{{ $buku->judul }}"
                                data-pengarang="{{ $buku->pengarang }}"
                                data-penerbit="{{ $buku->penerbit }}"
                                data-tahun="{{ $buku->tahun }}"
                                data-stok="{{ $buku->stok }}"
                                data-total_pinjam="{{ $buku->total_pinjam }}"
                                data-keterangan="{{ $buku->keterangan }}"
                                data-cover="{{ asset('storage/' . $buku->file_cover) }}"
                                data-file="{{ asset('storage/' . $buku->file_buku) }}">
                                <img src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-2" alt="{{ $buku->judul }}" style="height: 180px; object-fit: cover;">
                                <div class="fs-5">{{ Str::limit($buku->judul, 20) }}</div>
                                <div class="fs-5 mt-2">{{ $buku->total_pinjam }}x <br> Peminjaman</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Konten Sering Dilihat --}}
                        <h6>Konten Sering Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-3 justify-content-md-between">
                            @foreach($kontenSeringDilihat as $konten)
                                <div class="bg-dark text-white p-3 text-center buku-card rounded" style="width: 170px cursor: pointer;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#globalBukuModal"
                                    data-judul="{{ $konten->judul }}"
                                    data-pengarang="{{ $konten->pengarang }}"
                                    data-penerbit="{{ $konten->penerbit }}"
                                    data-tahun="{{ $konten->tahun }}"
                                    data-stok="{{ $konten->stok }}"
                                    data-total_pinjam="{{ $konten->total_pinjam }}"
                                    data-keterangan="{{ $konten->keterangan }}"
                                    data-cover="{{ asset('storage/' . $konten->file_cover) }}"
                                    data-file="{{ asset('storage/' . $konten->file_buku) }}">
                                    <img src="{{ asset('storage/' . $konten->file_cover) }}" class="img-fluid" style="height: 140px; object-fit: cover;">
                                    <div class="fs-5">{{ Str::limit($konten->judul, 20) }}</div>
                                    <div class="fs-5 mt-2">1x <br> Dilihat</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Wajib Dilihat --}}
                        <h6>Wajib Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-4 mb-4">
                            @foreach ($wajibDilihat as $dataWajibDilihat)
                                <div class="bg-dark text-white p-2 text-center rounded buku-card" style="width: 125px; cursor: pointer;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#globalBukuModal"
                                    data-judul="{{ $dataWajibDilihat->judul }}"
                                    data-pengarang="{{ $dataWajibDilihat->pengarang }}"
                                    data-penerbit="{{ $dataWajibDilihat->penerbit }}"
                                    data-tahun="{{ $dataWajibDilihat->tahun }}"
                                    data-stok="{{ $dataWajibDilihat->stok }}"
                                    data-total_pinjam="{{ $dataWajibDilihat->total_pinjam }}"
                                    data-keterangan="{{ $dataWajibDilihat->keterangan }}"
                                    data-cover="{{ asset('storage/' . $dataWajibDilihat->file_cover) }}"
                                    data-file="{{ asset('storage/' . $dataWajibDilihat->file_buku) }}">
                                    <img src="{{ asset('storage/' . $dataWajibDilihat->file_cover) }}" class="img-fluid mb-2" alt="{{ $dataWajibDilihat->judul }}" style="height: 100px; object-fit: cover;">
                                    <div class="small">{{ Str::limit($dataWajibDilihat->judul, 20) }}</div>
                                    <div class="small mt-2">{{ $dataWajibDilihat->total_pinjam }}x <br> Peminjaman</div>
                                </div>
                            @endforeach
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
                                <a id="btn-download" href="#" class="btn btn-outline-dark" download>Download</a>
                                <a id="btn-baca" href="#" class="btn btn-outline-dark" target="_blank">Baca Online</a>
                            </div>
                            <img id="modal-cover" src="" class="img-fluid mb-3" style="max-height: 300px; object-fit: contain;">
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
                    document.getElementById('modal-keterangan').textContent = card.dataset.keterangan;

                    document.getElementById('btn-download').href = card.dataset.file;
                    document.getElementById('btn-download').setAttribute('download', card.dataset.judul);
                    document.getElementById('btn-baca').href = card.dataset.file;
                });
            });
        });
    </script>
</x-app-layout>
