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
                                <div class="bg-dark text-white p-3 text-center rounded" style="width: 215px; cursor: pointer;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailFav{{ $buku->no_regis }}">
                                    <img src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-2" alt="{{ $buku->judul }}" style="height: 180px; object-fit: cover;">
                                    <div class="fs-5">{{ Str::limit($buku->judul, 20) }}</div>
                                    <div class="fs-5 mt-2">{{ $buku->total_pinjam }}x <br> Peminjaman</div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="detailFav{{ $buku->no_regis }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $buku->no_regis }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body text-center">

                                        {{-- Tombol Aksi --}}
                                        <div class="d-flex justify-content-between mb-3 px-3">
                                            <a href="{{ asset('storage/' . $buku->file_buku) }}" download="{{ $buku->judul }}" id="btn-download" class="btn btn-outline-dark">Download</a>
                                            <a href="{{ asset('storage/' . $buku->file_buku) }}" target="_blank" id="btn-baca" class="btn btn-outline-dark">Baca Online</a>
                                        </div>

                                        {{-- Gambar Buku --}}
                                        <img id="modal-cover" src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-3" style="max-height: 300px; object-fit: contain;">

                                        {{-- Detail Buku --}}
                                        <div class="text-start px-3">
                                            <h6 class="fw-bold">Detail Buku</h6>
                                            <div class="row">
                                            <div class="col-6">
                                                <p>Judul : {{ $buku->judul }}</p>
                                                <p>Pengarang : {{ $buku->pengarang }}</p>
                                                <p>Penerbit : {{ $buku->penerbit }}</p>
                                                <p>Tahun : {{ $buku->tahun }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p>Stok Buku : {{ $buku->stok }}</p>
                                                <p>Jumlah dipinjam : {{ $buku->total_pinjam }}</span> Kali</p>
                                            </div>
                                            </div>

                                            {{-- Keterangan --}}
                                            <h6 class="fw-bold mt-3">Keterangan</h6>
                                            <p id="modal-keterangan" class="text-justify">
                                           {{ $buku->keterangan }}
                                            </p>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Konten Sering Dilihat --}}
                        <h6>Konten Sering Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-3 justify-content-md-between">
                            @foreach($kontenSeringDilihat as $konten)
                                <div class="bg-dark text-white p-3 text-center rounded" style="width: 170px;">
                                    <img src="{{ asset('storage/' . $konten->file_cover) }}" class="img-fluid" style="height: 140px; object-fit: cover;">
                                    <div class="fs-5">{{ Str::limit($buku->judul, 20) }}</div>
                                    <div class="fs-5 mt-2">1x <br> Dilihat</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-5 border-bottom border-dark my-3"></div>

                        {{-- Wajib Dilihat --}}
                        <h6>Wajib Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-4 mb-4">
                            @foreach ($wajibDilihat as $buku)
                                <div class="bg-dark text-white p-2 text-center rounded" style="width: 125px;">
                                    <img src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-2" alt="{{ $buku->judul }}" style="height: 100px; object-fit: cover;">
                                    <div class="small">{{ Str::limit($buku->judul, 20) }}</div>
                                    <div class="small mt-2">{{ $buku->total_pinjam }}x <br> Peminjaman</div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>
</x-app-layout>
