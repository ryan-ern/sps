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
                                <div class="w-20 d-flex justify-content-end">
                                    <input type="search" id="search" name="search" class="form-control mb-2"
                                        placeholder="Cari Data" value="{{ request('search') }}">
                                </div>
                                <hr>

                                <table class="table table-sm table-bordered dataTable" id="table">
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
                                        @foreach ($data as $dataPengembalian)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="truncate">{{ $dataPengembalian->judul }}</td>
                                                <td class="truncate">{{ $dataPengembalian->no_regis }}</td>
                                                <td class="text-uppercase">
                                                    {{ $dataPengembalian->tgl_pinjam->format('d-m-Y h:i a') }}
                                                </td>
                                                <td>
                                                    @if ($dataPengembalian->pinjam == 'terima')
                                                        <span class="btn btn-primary">Diterima</span>
                                                    @elseif ($dataPengembalian->pinjam == 'verifikasi')
                                                        <span class="btn btn-warning">Diproses</span>
                                                    @elseif ($dataPengembalian->pinjam == 'tolak')
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
        <!-- Dynamic Modal -->
        <div class="modal modal-lg fade" id="dynamicModal" tabindex="-1" aria-labelledby="ModalLabel">
            <div class="modal-dialog">
                <form id="dynamicModalForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel"></h5>
                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="modalContent">

                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </form>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                const tambahButtons = document.querySelectorAll('#tambahButton');
                tambahButtons.forEach(button => {
                    button.classList.add('d-none');
                });
            }, 1);
        });
    </script>
</x-app-layout>
