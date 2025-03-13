<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-5">
                            {{-- Nav Tabs --}}
                            <ul class="nav nav-tabs" id="Tabs" role="tablist">
                                <li class="nav-item me-2 my-2" role="presentation">
                                    <button class="nav-link active" id="referensi-tab" data-bs-toggle="tab"
                                        data-bs-target="#referensi" type="button" role="tab"
                                        aria-controls="referensi" aria-selected="false">Buku Referensi</button>
                                </li>
                                <li class="nav-item me-2 my-2" role="presentation">
                                    <button class="nav-link" id="paket-tab" data-bs-toggle="tab" data-bs-target="#paket"
                                        type="button" role="tab" aria-controls="paket" aria-selected="false">Buku
                                        Paket</button>
                                </li>
                                <li class="nav-item me-2 my-2" role="presentation">
                                    <button class="nav-link" id="digital-tab" data-bs-toggle="tab"
                                        data-bs-target="#digital" type="button" role="tab" aria-controls="digital"
                                        aria-selected="false">Konten Digital</button>
                                </li>
                            </ul>
                            {{-- End Nav Tabs --}}
                            <div class="tab-content mt-4" id="TabsContent">
                                <!-- Tab Buku Referensi -->
                                <div class="tab-pane fade show active" id="referensi" role="tabpanel"
                                    aria-labelledby="referensi-tab">
                                    <div class="table-responsive text-center">
                                        <table class="table table-sm table-bordered dataTable" id="table">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">No Regis</th>
                                                    <th scope="col">Judul Buku</th>
                                                    <th scope="col">Penerbit</th>
                                                    <th scope="col">Tahun</th>
                                                    <th scope="col">Stok</th>
                                                    <th scope="col">Tanggal Masuk</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($referensi as $BukuReferensi)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-capitalize">{{ $BukuReferensi->no_regis }}</td>
                                                        <td class="truncate">{{ $BukuReferensi->judul }}</td>
                                                        <td class="truncate">{{ $BukuReferensi->penerbit }}</td>
                                                        <td>{{ $BukuReferensi->tahun }}</td>
                                                        <td>{{ $BukuReferensi->stok }}</td>
                                                        <td class="text-uppercase">
                                                            {{ $BukuReferensi->created_at->format('d-m-Y h:i a') }}</td>
                                                        <td>
                                                            <button class="mx-2 btn btn-primary">Edit</button>
                                                            <button class="mx-2 btn btn-danger">Hapus</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Tab Buku Referensi -->
                                {{-- Tab Buku Paket --}}
                                <div class="tab-pane fade show" id="paket" role="tabpanel"
                                    aria-labelledby="paket-tab">
                                    <div class="table-responsive text-center">
                                        <table class="table table-sm table-bordered dataTable" id="table">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">No Regis</th>
                                                    <th scope="col">Judul Buku</th>
                                                    <th scope="col">Penerbit</th>
                                                    <th scope="col">Tahun</th>
                                                    <th scope="col">Stok</th>
                                                    <th scope="col">Tanggal Masuk</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paket as $BukuPaket)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-capitalize">{{ $BukuPaket->no_regis }}</td>
                                                        <td class="truncate">{{ $BukuPaket->judul }}</td>
                                                        <td class="truncate">{{ $BukuPaket->penerbit }}</td>
                                                        <td>{{ $BukuPaket->tahun }}</td>
                                                        <td>{{ $BukuPaket->stok }}</td>
                                                        <td class="text-uppercase">
                                                            {{ $BukuPaket->created_at->format('d-m-Y h:i a') }}
                                                        </td>
                                                        <td>
                                                            <button class="mx-2 btn btn-primary">Edit</button>
                                                            <button class="mx-2 btn btn-danger">Hapus</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Tab Buku Paket -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="dynamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">Form Tambah Data</h5>
                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="dynamicModalForm" action="{{ route('tambah-buku') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-4">
                                            <select class="form-select mb-3" name="jenis" required>
                                                <option value="" selected disabled>Pilih Jenis</option>
                                                <option value="referensi">Buku Referensi</option>
                                                <option value="paket">Buku Paket</option>
                                            </select>
                                            <input type="number" class="form-control mb-3" name="no_regis"
                                                placeholder="No Regis" required>
                                            <input type="text" class="form-control mb-3" name="judul"
                                                placeholder="Judul" autofocus required>
                                            <input type="text" class="form-control mb-3" name="pengarang"
                                                placeholder="Pengarang" required>
                                            <input type="text" class="form-control mb-3" name="penerbit"
                                                placeholder="Penerbit" required>
                                        </div>

                                        <!-- Kolom Tengah -->
                                        <div class="col-md-4">
                                            <input type="number" class="form-control mb-2" name="stok"
                                                placeholder="Stok" required>
                                            <div class="form-floating">
                                                <textarea class="form-control" name="keterangan" required placeholder="Keterangan Tulis Disini"
                                                    id="floatingTextarea2" style="height: 215px"></textarea>
                                                <label for="floatingTextarea2">Keterangan</label>
                                            </div>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="fileBuku" class="form-control" name="file_buku"
                                                    id="fileBukuLabel">Pilih
                                                    File Buku</label>
                                                <input type="file" class="form-control d-none" id="fileBuku">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fileCover" class="form-control" name="file_cover"
                                                    id="fileCoverLabel">Pilih
                                                    File Cover</label>
                                                <input type="file" class="form-control d-none" id="fileCover">
                                            </div>

                                            <input type="number" class="form-control mb-3" name="tahun"
                                                placeholder="Tahun" required>
                                            <button type="button" class="btn btn-success w-100 mb-2">Hasil Scan
                                                Barcode</button>
                                        </div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <x-app.footer />
            </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById('dynamicModal');
            var inputAutofocus = modal.querySelector('input[autofocus]');

            modal.addEventListener('shown.bs.modal', function() {
                if (inputAutofocus) {
                    inputAutofocus.focus();
                }
            });

            document.getElementById('fileBuku').addEventListener('change', function() {
                document.getElementById('fileBukuLabel').textContent = this.files.length ? this.files[0]
                    .name : 'Pilih File';
            });
            document.getElementById('fileCover').addEventListener('change', function() {
                document.getElementById('fileCoverLabel').textContent = this.files.length ? this.files[0]
                    .name : 'Pilih File';
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('fileBuku').value = "";
                document.getElementById('fileCover').value = "";

                document.getElementById('fileBukuLabel').textContent = "Pilih File Buku";
                document.getElementById('fileCoverLabel').textContent = "Pilih File Cover";
            });

        });
    </script>
</x-app-layout>
