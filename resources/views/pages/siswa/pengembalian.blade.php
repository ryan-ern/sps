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
                                            <th scope="col">Tanggal Pinjam</th>
                                            <th scope="col">Estimasi Kembali</th>
                                            <th scope="col">Tanggal Kembali</th>
                                            <th scope="col">Denda</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $dataPengembalian)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="truncate">{{ $dataPengembalian->judul }}</td>
                                                <td class="text-uppercase">
                                                    {{ $dataPengembalian->tgl_pinjam->format('d-m-Y h:i a') }}
                                                </td>
                                                <td class="text-uppercase">
                                                    {{ $dataPengembalian->est_kembali->format('d-m-Y h:i a') }}
                                                </td>
                                                <td class="text-uppercase">
                                                    {{ $dataPengembalian->tgl_kembali == null ? '-' : $dataPengembalian->tgl_kembali->format('d-m-Y h:i a') }}
                                                </td>
                                                <td class="truncate"> Rp.
                                                    {{ number_format($dataPengembalian->denda, 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($dataPengembalian->kembali == '-')
                                                        <form id="form-pinjam" method="POST"
                                                            action="{{ route('pengembalian-siswa.post', $dataPengembalian->id) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-primary">Kembalikan</button>
                                                        </form>
                                                    @elseif ($dataPengembalian->kembali == 'verifikasi')
                                                        <span class="btn btn-warning">Diproses</span>
                                                    @elseif ($dataPengembalian->kembali == 'selesai')
                                                        <span class="btn btn-success">Selesai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum Ada Data Pengembalian</td>
                                            </tr>
                                        @endforelse
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
                window.location.href = `{{ route('pengembalian-siswa.read') }}?${params.toString()}`;
            }, 500);
        });
    </script>
</x-app-layout>
