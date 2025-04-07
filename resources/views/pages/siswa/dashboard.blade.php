<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-4">
                        <h5>Selamat Siang, Admin</h5>
                        <p>Data Hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

                        {{-- Statistik Harian --}}
                        <div class="row mb-4">
                            <div class="col">
                                <div class="bg-dark text-white p-3 rounded text-center">
                                    <div class="fs-4">{{ $dataPengunjung }}</div>
                                    <small>Data Pengunjung</small><br>
                                    <small class="text-success">+20%</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="bg-dark text-white p-3 rounded text-center">
                                    <div class="fs-4">{{ $dataPeminjam }}</div>
                                    <small>Data Peminjam</small><br>
                                    <small class="text-success">+10%</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="bg-dark text-white p-3 rounded text-center">
                                    <div class="fs-4">{{ $dataKembali }}</div>
                                    <small>Buku Kembali</small><br>
                                    <small class="text-success">+15%</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Buku Terfavorit --}}
                        <h6>Buku Terfavorit</h6>
                        <div class="d-flex flex-wrap justify-content-between mb-4">
                            @foreach ($bukuFavorit as $buku)
                                <div class="bg-dark text-white p-3 text-center rounded" style="width: 200px;">
                                    <img src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-2" alt="{{ $buku->judul }}" style="height: 180px; object-fit: cover;">
                                    <div class="small">{{ Str::limit($buku->judul, 20) }}</div>
                                    <div class="small text-muted">{{ $buku->total_pinjam }}x Peminjaman</div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        {{-- Konten Sering Dilihat --}}
                        <h6>Konten Sering Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-between ">
                            @foreach($kontenSeringDilihat as $konten)
                                <div class="bg-dark text-white p-3 text-center rounded" style="width: 170px;">
                                    <img src="{{ asset('storage/' . $konten->file_cover) }}" class="img-fluid" style="height: 140px; object-fit: cover;">
                                    <div class="small mt-2">{{ Str::limit($konten->judul, 20) }}</div>
                                    <div class="small text-muted">1x Dilihat</div> {{-- Ubah jika punya field view_count --}}
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        {{-- Wajib Dilihat --}}
                        <h6>Wajib Dilihat</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                            @foreach ($wajibDilihat as $buku)
                                <div class="bg-dark text-white p-2 text-center rounded" style="width: 120px;">
                                    <img src="{{ asset('storage/' . $buku->file_cover) }}" class="img-fluid mb-2" alt="{{ $buku->judul }}" style="height: 100px; object-fit: cover;">
                                    <div class="small">{{ Str::limit($buku->judul, 20) }}</div>
                                    <div class="small text-muted">{{ $buku->total_pinjam }}x Peminjaman</div>
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
