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
                                    <button class="nav-link" id="referensi-tab" data-bs-toggle="tab"
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
                                <div class="tab-pane fade show" id="referensi" role="tabpanel"
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
                                                            {{ $BukuReferensi->created_at->format('d-m-Y h:i a') }}
                                                        </td>
                                                        <td>
                                                            <button class="mx-2 btn btn-primary editBtn"
                                                                data-no_regis="{{ $BukuReferensi->no_regis }}"
                                                                data-judul="{{ $BukuReferensi->judul }}"
                                                                data-penerbit="{{ $BukuReferensi->penerbit }}"
                                                                data-tahun="{{ $BukuReferensi->tahun }}"
                                                                data-stok="{{ $BukuReferensi->stok }}"
                                                                data-file_buku="{{ $BukuReferensi->file_buku }}"
                                                                data-file_cover="{{ $BukuReferensi->file_cover }}"
                                                                data-keterangan="{{ $BukuReferensi->keterangan }}"
                                                                data-bs-toggle="modal" data-bs-target="#dynamicModal"
                                                                data-modal-type="update">
                                                                Edit
                                                            </button>

                                                            <button class="mx-2 btn btn-danger deleteBtn"
                                                                data-no_regis="{{ $BukuReferensi->no_regis }}"
                                                                data-modal-type="delete" data-bs-toggle="modal"
                                                                data-bs-target="#dynamicModal">
                                                                Hapus
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{-- Tambahkan Navigasi Pagination --}}
                                        <div class="d-flex justify-content-between mt-3">
                                            {{ $referensi->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                                        </div>
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
                                                            <button class="mx-2 btn btn-primary editBtn"
                                                                data-no_regis="{{ $BukuPaket->no_regis }}"
                                                                data-judul="{{ $BukuPaket->judul }}"
                                                                data-penerbit="{{ $BukuPaket->penerbit }}"
                                                                data-tahun="{{ $BukuPaket->tahun }}"
                                                                data-stok="{{ $BukuPaket->stok }}"
                                                                data-file_buku="{{ $BukuPaket->file_buku }}"
                                                                data-file_cover="{{ $BukuPaket->file_cover }}"
                                                                data-keterangan="{{ $BukuPaket->keterangan }}"
                                                                data-bs-toggle="modal" data-bs-target="#dynamicModal"
                                                                data-modal-type="update">
                                                                Edit
                                                            </button>

                                                            <button class="mx-2 btn btn-danger deleteBtn"
                                                                data-no_regis="{{ $BukuPaket->no_regis }}"
                                                                data-modal-type="delete" data-bs-toggle="modal"
                                                                data-bs-target="#dynamicModal">
                                                                Hapus
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{-- Tambahkan Navigasi Pagination --}}
                                        <div class="d-flex justify-content-between mt-3">
                                            {{ $paket->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                                <!-- End Tab Buku Paket -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dynamic Modal -->
                <div class="modal modal-lg fade" id="dynamicModal" data-bs-backdrop="static" tabindex="-1"
                    aria-labelledby="ModalLabel">
                    <div class="modal-dialog">
                        <form id="dynamicModalForm" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById('dynamicModal');
            var inputAutofocus = modal.querySelector('input[autofocus]');

            modal.addEventListener('shown.bs.modal', function() {
                if (inputAutofocus) {
                    inputAutofocus.focus();
                }
            });

            let tabs = document.querySelectorAll("#Tabs .nav-link");

            let activeTab = localStorage.getItem("activeTab") || "#referensi";
            if (activeTab) {
                let activeButton = document.querySelector(`#Tabs .nav-link[data-bs-target="${activeTab}"]`);
                if (activeButton) {
                    tabs.forEach(tab => tab.classList.remove("active"));
                    activeButton.classList.add("active");

                    let activePane = document.querySelector(activeTab);
                    if (activePane) {
                        document.querySelectorAll(".tab-pane").forEach(pane => pane.classList.remove("show",
                            "active"));
                        activePane.classList.add("show", "active");
                    }
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    let target = this.getAttribute("data-bs-target");
                    localStorage.setItem("activeTab", target);
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dynamicModal = document.getElementById('dynamicModal');
            const modalTitle = dynamicModal.querySelector('.modal-title');
            const modalContent = document.getElementById('modalContent');
            const modalForm = document.getElementById('dynamicModalForm');

            dynamicModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const modalType = button.getAttribute('data-modal-type');
                const bukuId = button.getAttribute('data-no_regis');

                let modalBodyHTML = "";
                modalForm.enctype = "application/x-www-form-urlencoded";
                modalForm.method = "POST";

                if (modalType === 'update') {
                    modalTitle.textContent = 'Edit Data';
                    modalForm.action = `/pages/data-buku/update/${bukuId}`;
                    modalForm.enctype = 'multipart/form-data';

                    modalBodyHTML = `
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="${button.getAttribute('data-nama')}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tujuan" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" value="${button.getAttribute('data-tujuan')}" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <select class="form-select" id="jenis" name="jenis" required>
                                <option value="makanan" ${button.getAttribute('data-jenis') === 'makanan' ? 'selected' : ''}>Makanan</option>
                                <option value="barang" ${button.getAttribute('data-jenis') === 'barang' ? 'selected' : ''}>Barang</option>
                                <option value="lainnya" ${button.getAttribute('data-jenis') === 'lainnya' ? 'selected' : ''}>Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" required>${button.getAttribute('data-keterangan')}</textarea>
                        </div>
                    `;
                } else if (modalType === 'delete') {
                    modalTitle.textContent = 'Hapus Data';
                    modalForm.action = `/apps/data-buku/delete/${bukuId}`;
                    modalForm.method = 'POST';
                    modalBodyHTML = `
                        @csrf
                        @method('DELETE')
                        <p>Apakah Anda yakin ingin menghapus data buku dengan <strong>nomor registrasi ${button.getAttribute('data-no_regis')}</strong>?</p>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                        `;
                } else {
                    modalTitle.textContent = 'Tambah Data';
                    modalForm.action = '/apps/data-buku/create';
                    modalForm.enctype = 'multipart/form-data';
                    modalForm.method = 'POST';
                    modalBodyHTML = `
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
                                                <label for="fileBuku" class="form-control" id="fileBukuLabel">Pilih
                                                    File Buku</label>
                                                <input type="file" class="form-control d-none" id="fileBuku"
                                                    name="file_buku">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fileCover" class="form-control" id="fileCoverLabel">Pilih
                                                    File Cover</label>
                                                <input type="file" class="form-control d-none" id="fileCover"
                                                    name="file_cover">
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
                    `;
                }

                modalContent.innerHTML = modalBodyHTML;
            });

            dynamicModal.addEventListener('hidden.bs.modal', function() {
                modalContent.innerHTML = '';
            });
        });
    </script>
</x-app-layout>
