<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="table-responsive text-center">
                                {{-- Filter --}}
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="w-100" style="max-width: 300px;">
                                        <input type="search" id="search" name="search" class="form-control"
                                            placeholder="Cari Data" value="{{ request('search') }}">
                                    </div>
                                </div>

                                <hr>

                                <table class="table table-sm table-bordered" id="table">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Judul Buku</th>
                                            <th scope="col">No Regis</th>
                                            <th scope="col">Tanggal Pengajuan</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $dataPeminjaman)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="truncate">{{ $dataPeminjaman->judul }}</td>
                                                <td class="truncate">{{ $dataPeminjaman->no_regis }}</td>
                                                <td class="text-uppercase">
                                                    {{ $dataPeminjaman->tgl_pinjam->format('d-m-Y h:i a') }}
                                                </td>
                                                <td>
                                                    @if ($dataPeminjaman->pinjam == 'terima')
                                                        <span class="btn btn-primary">Diterima</span>
                                                    @elseif ($dataPeminjaman->pinjam == 'verifikasi')
                                                        <span class="btn btn-warning">Diproses</span>
                                                    @elseif ($dataPeminjaman->pinjam == 'tolak')
                                                        <span class="btn btn-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- Tambahkan Navigasi Pagination --}}
                                <div class="d-flex justify-content-between mt-3">
                                    {{ $data->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <x-app.footer />
        </div>
    </main>
    <script>
        let debounceTimer;
        const searchInput = document.getElementById('search');

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                const params = new URLSearchParams(window.location.search);
                params.set('search', searchInput.value);
                window.location.href = `{{ route('peminjaman-siswa.read') }}?${params.toString()}`;
            }, 500);
        });
    </script>
</x-app-layout>
