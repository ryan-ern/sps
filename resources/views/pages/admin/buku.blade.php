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
                                        {{-- Filter --}}
                                        <form method="GET" action="{{ route('data-buku.read') }}" class="mb-3">
                                            <div class="row d-flex justify-content-between">
                                                <!-- Date Range Picker -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="text" name="dates" value="{{ request('dates') }}"
                                                        class="dates form-control mb-2" />
                                                </div>

                                                <!-- Search Input -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="search" id="search" name="search"
                                                        class="form-control mb-2" placeholder="Cari Buku Referensi"
                                                        value="{{ request('search') }}">
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                                    <a href="{{ route('data-buku.read') }}"
                                                        class="btn btn-warning w-100">Reset</a>
                                                    <button type="submit"
                                                        class="btn btn-primary w-100">Terapkan</button>
                                                </div>
                                            </div>
                                            <hr>
                                        </form>

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
                                                                data-jenis="{{ $BukuReferensi->jenis }}"
                                                                data-judul="{{ $BukuReferensi->judul }}"
                                                                data-penerbit="{{ $BukuReferensi->penerbit }}"
                                                                data-pengarang="{{ $BukuReferensi->pengarang }}"
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
                                                                data-judul="{{ $BukuReferensi->judul }}"
                                                                data-stok="{{ $BukuReferensi->stok }}"
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
                                        {{-- Filter --}}
                                        <form method="GET" action="{{ route('data-buku.read') }}" class="mb-3">
                                            <div class="row d-flex justify-content-between">
                                                <!-- Date Range Picker -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="text" name="dates"
                                                        value="{{ request('dates') }}"
                                                        class="dates form-control mb-2" />
                                                </div>

                                                <!-- Search Input -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="search" id="search" name="search"
                                                        class="form-control mb-2" placeholder="Cari Buku Paket"
                                                        value="{{ request('search') }}">
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                                    <a href="{{ route('data-buku.read') }}"
                                                        class="btn btn-warning w-100">Reset</a>
                                                    <button type="submit"
                                                        class="btn btn-primary w-100">Terapkan</button>
                                                </div>
                                            </div>
                                            <hr>
                                        </form>
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
                                                                data-jenis="{{ $BukuPaket->jenis }}"
                                                                data-judul="{{ $BukuPaket->judul }}"
                                                                data-penerbit="{{ $BukuPaket->penerbit }}"
                                                                data-pengarang="{{ $BukuPaket->pengarang }}"
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
                                                                data-judul="{{ $BukuPaket->judul }}"
                                                                data-stok="{{ $BukuPaket->stok }}"
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
                                {{-- Tab Konten Digital --}}
                                <div class="tab-pane fade show" id="digital" role="tabpanel"
                                    aria-labelledby="digital-tab">
                                    <div class="table-responsive text-center">
                                        {{-- Filter --}}
                                        <form method="GET" action="{{ route('data-buku.read') }}" class="mb-3">
                                            <div class="row d-flex justify-content-between">
                                                <!-- Date Range Picker -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="text" name="dates"
                                                        value="{{ request('dates') }}"
                                                        class="dates form-control mb-2" />
                                                </div>

                                                <!-- Search Input -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="search" id="search" name="search"
                                                        class="form-control mb-2" placeholder="Cari Konten Digital"
                                                        value="{{ request('search') }}">
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                                    <a href="{{ route('data-buku.read') }}"
                                                        class="btn btn-warning w-100">Reset</a>
                                                    <button type="submit"
                                                        class="btn btn-primary w-100">Terapkan</button>
                                                </div>
                                            </div>
                                            <hr>
                                        </form>
                                        <table class="table table-sm table-bordered dataTable" id="table">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama</th>
                                                    <th scope="col">Nama Konten</th>
                                                    <th scope="col">Jenis Konten</th>
                                                    <th scope="col">Tanggal</th>
                                                    <th scope="col">Dilihat</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($konten as $data)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-capitalize">{{ $data->pembuat }}</td>
                                                        <td class="truncate">{{ $data->judul }}</td>
                                                        <td class="truncate text-capitalize">{{ $data->jenis }}</td>
                                                        <td class="text-uppercase">
                                                            {{ $data->created_at->format('d-m-Y h:i a') }}
                                                        </td>
                                                        <td>{{ $data->dilihat }}</td>
                                                        <td>
                                                            <button class="mx-2 btn btn-primary editBtn"
                                                                data-id="{{ $data->id }}"
                                                                data-judul="{{ $data->judul }}"
                                                                data-pembuat="{{ $data->pembuat }}"
                                                                data-jenis="{{ $data->jenis }}"
                                                                data-url="{{ $data->url }}"
                                                                data-nuptk="{{ $data->nuptk }}"
                                                                data-file_path="{{ $data->file_path }}"
                                                                data-bs-toggle="modal" data-bs-target="#dynamicModal"
                                                                data-modal-type="update">
                                                                Edit
                                                            </button>

                                                            <button class="mx-2 btn btn-danger deleteBtn"
                                                                data-id="{{ $data->id }}"
                                                                data-judul="{{ $data->judul }}"
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
                                <!-- End Tab Konten Digital -->
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
        document.addEventListener("DOMContentLoaded", function() {
            let tabs = document.querySelectorAll("#Tabs .nav-link");

            let bukuTab = localStorage.getItem("bukuTab") || "#referensi";
            if (bukuTab) {
                let activeButton = document.querySelector(`#Tabs .nav-link[data-bs-target="${bukuTab}"]`);
                if (activeButton) {
                    tabs.forEach(tab => tab.classList.remove("active"));
                    activeButton.classList.add("active");

                    let activePane = document.querySelector(bukuTab);
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
                    localStorage.setItem("bukuTab", target);
                });
            });
        });
    </script>
    <script>
        const guruOptions = @json($guru);
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

                if (localStorage.getItem('bukuTab') == '#digital') {
                    if (modalType === 'update') {
                        modalTitle.textContent = 'Form Edit Data';
                        modalForm.action = `/apps/konten-digital/update/${button.getAttribute('data-id')}`;
                        modalForm.enctype = 'multipart/form-data';

                        const jenis = button.getAttribute('data-jenis');
                        const judul = button.getAttribute('data-judul');
                        const pembuat = button.getAttribute('data-pembuat');
                        const nuptk = button.getAttribute('data-nuptk');
                        const url = button.getAttribute('data-url');

                        modalBodyHTML = `
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="jenis" required class="form-select mb-3">
                                        <option value="" disabled>Pilih Jenis Konten</option>
                                        <option value="video" ${jenis === 'video' ? 'selected' : ''}>Video</option>
                                        <option value="buku digital" ${jenis === 'buku digital' ? 'selected' : ''}>Buku Digital</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="judul" placeholder="Judul" value="${judul}" required>
                                    <select name="nuptk" required class="form-select mb-3" id="guruSelect">
                                        <option value="" disabled>Pilih Guru (NUPTK)</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="pembuat" id="pembuat" placeholder="Pembuat" value="${pembuat}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Link URL Youtube</label>
                                    <input type="text" class="form-control mb-2" name="url" placeholder="Link URL Youtube" value="${url ?? ''}">
                                    <label class="form-label">Buku Digital</label>
                                    <input type="file" class="form-control" name="file_path" placeholder="Buku Digital">
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="reset" class="btn btn-primary me-2" id="closeModal" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        `;

                        setTimeout(() => {
                            const guruSelect = document.getElementById('guruSelect');
                            const pembuatInput = document.getElementById('pembuat');

                            if (guruSelect && guruOptions.length > 0) {
                                guruOptions.forEach(guru => {
                                    const option = document.createElement('option');
                                    option.value = guru.nisn;
                                    option.textContent = `${guru.fullname} (${guru.nisn})`;
                                    if (guru.nisn === nuptk) {
                                        option.selected = true;
                                        pembuatInput.value = guru.fullname;
                                    }
                                    guruSelect.appendChild(option);
                                });

                                guruSelect.addEventListener('change', function() {
                                    const selectedGuru = guruOptions.find(g => g.nisn ===
                                        this.value);
                                    if (selectedGuru) {
                                        pembuatInput.value = selectedGuru.fullname;
                                    }
                                });
                            }
                        }, 100);
                    } else if (modalType === 'delete') {

                        modalTitle.textContent = 'Form Hapus Data';
                        modalForm.action = `/apps/konten-digital/delete/${button.getAttribute('data-id')}`;
                        modalForm.enctype = 'multipart/form-data';
                        var modal = document.getElementById('dynamicModal');
                        var inputAutofocus = modal.querySelector('input[autofocus]');

                        modal.addEventListener('shown.bs.modal', function() {
                            if (inputAutofocus) {
                                inputAutofocus.focus();
                            }
                        });
                        modalBodyHTML = `
                            @csrf
                        @method('DELETE')
                           <p class="text-center fs-5 text-capitalize">Apakah Anda ingin menghapus <br/> data konten dengan <br/><strong>judul ${button.getAttribute('data-judul')}</strong></p>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                        `;
                    } else {
                        var modal = document.getElementById('dynamicModal');
                        var inputAutofocus = modal.querySelector('input[autofocus]');

                        modal.addEventListener('shown.bs.modal', function() {
                            if (inputAutofocus) {
                                inputAutofocus.focus();
                            }
                        });
                        modalTitle.textContent = 'Form Tambah Data';
                        modalForm.action = '/apps/konten-digital/create';
                        modalForm.enctype = 'multipart/form-data';
                        modalBodyHTML = `
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="jenis" required class="form-select mb-3">
                                        <option value="" disabled selected>Pilih Jenis Konten</option>
                                        <option value="video">Video</option>
                                        <option value="buku digital">Buku Digital</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="judul" placeholder="Judul" required>
                                    <select name="nuptk" required class="form-select mb-3" id="guruSelect">
                                        <option value="" disabled selected>Pilih Guru (NUPTK)</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="pembuat" id="pembuat" placeholder="Pembuat" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="url" class="form-label">Link URL Youtube</label>
                                    <input type="text" class="form-control mb-2" name="url" placeholder="Link URL Youtube">
                                    <label for="file_path" class="form-label">Buku Digital</label>
                                    <input type="file" class="form-control" name="file_path" placeholder="Buku Digital">
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="reset" class="btn btn-primary me-2" id="closeModal" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        `;

                        setTimeout(() => {
                            const guruSelect = document.getElementById('guruSelect');
                            const pembuatInput = document.getElementById('pembuat');

                            if (guruSelect && guruOptions.length > 0) {
                                guruOptions.forEach(guru => {
                                    const option = document.createElement('option');
                                    option.value = guru.nisn;
                                    option.textContent = `${guru.fullname} (${guru.nisn})`;
                                    guruSelect.appendChild(option);
                                });

                                guruSelect.addEventListener('change', function() {
                                    const selectedGuru = guruOptions.find(g => g.nisn ===
                                        this.value);
                                    if (selectedGuru && pembuatInput) {
                                        pembuatInput.value = selectedGuru.fullname;
                                    }
                                });
                            }
                        }, 100);
                    }
                } else if (modalType === 'update') {
                    modalTitle.textContent = 'Form Edit Data';
                    modalForm.action = `/apps/data-buku/update/${bukuId}`;
                    modalForm.enctype = 'multipart/form-data';
                    var modal = document.getElementById('dynamicModal');
                    var inputAutofocus = modal.querySelector('input[autofocus]');

                    modal.addEventListener('shown.bs.modal', function() {
                        if (inputAutofocus) {
                            inputAutofocus.focus();
                        }
                    });

                    const bukuData = button.dataset;

                    modalBodyHTML = `
                         @csrf
                    @method('PUT')
                     <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-4">
                <label for="jenis" class="form-label">Jenis</label>
                <select class="form-select mb-3" name="jenis" required>
                    <option value="" disabled>Pilih Jenis</option>
                    <option value="referensi" ${bukuData.jenis === 'referensi' ? 'selected' : ''}>Buku Referensi</option>
                    <option value="paket" ${bukuData.jenis === 'paket' ? 'selected' : ''}>Buku Paket</option>
                </select>
                <label for="no_regis" class="form-label">No Regis</label>
                <input type="number" class="form-control mb-3" name="no_regis" placeholder="No Regis" value="${bukuData.no_regis}" readonly required>
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control mb-3" name="judul" placeholder="Judul" value="${bukuData.judul}" readonly required>
                <label for="pengarang" class="form-label">Pengarang</label>
                <input type="text" class="form-control mb-3" name="pengarang" placeholder="Pengarang" value="${bukuData.pengarang}" required>
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" class="form-control mb-3" name="penerbit" placeholder="Penerbit" value="${bukuData.penerbit}" required>
            </div>

            <!-- Kolom Tengah -->
            <div class="col-md-4">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control mb-2" name="stok" placeholder="Stok" value="${bukuData.stok}" readonly required>
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" class="form-control mb-3" name="tahun" placeholder="Tahun" value="${bukuData.tahun}" required>
                <div class="form-floating">
                    <textarea class="form-control" name="keterangan" placeholder="Keterangan Tulis Disini" id="floatingTextarea2" style="height: 215px">${bukuData.keterangan}</textarea>
                    <label for="floatingTextarea2">Keterangan</label>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="fileBuku" class="form-label" id="fileBukuLabel">Pilih File Buku</label>
                    <input type="file" class="form-control" accept=".pdf"  id="fileBuku" name="file_buku">
                    ${bukuData.file_buku != '-' ? `<a href="/storage/${bukuData.file_buku}" target="_blank" class="d-block mt-2 text-info">Lihat File Buku</a>` : ''}
                    </div>
                    <div class="mb-3">
                    <label for="fileCover" class="form-label" id="fileCoverLabel">Pilih File Cover</label>
                    <input type="file" class="form-control" accept=".jpg, .jpeg, .png"  id="fileCover" name="file_cover">
                    </div>
                    ${bukuData.file_cover != '-' ?
                        `<a href="/storage/${bukuData.file_cover}" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <img src="/storage/${bukuData.file_cover}" class="d-block mt-2 text-info" style="max-height: 150px; max-width: auto; cursor: pointer;" alt="Cover Buku">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </a>`
                    : ''}
            </div>
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-end mt-3">
            <button type="reset" class="btn btn-primary me-2" id="closeModal" data-bs-dismiss="modal">Kembali</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
                    `;
                } else if (modalType === 'delete') {
                    modalTitle.textContent = 'Konfirmasi Hapus Data';
                    modalForm.action = `/apps/data-buku/delete/`;
                    modalForm.method = 'POST';
                    modalBodyHTML = `
                        @csrf
                        @method('DELETE')
                        <p class="text-center fs-5 text-capitalize">Apakah Anda ingin menghapus <br/> data buku dengan <br/><strong>nomor registrasi <br/>${button.getAttribute('data-no_regis')}</strong></p>
                                    <div class="d-flex justify-content-end mt-3">
                                        <input type="hidden" name="judul" value="${button.getAttribute('data-judul')}">
                                        <input type="hidden" name="stok" value="${button.getAttribute('data-stok')}">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                        `;
                } else {
                    var modal = document.getElementById('dynamicModal');
                    var inputAutofocus = modal.querySelector('input[autofocus]');

                    modal.addEventListener('shown.bs.modal', function() {
                        if (inputAutofocus) {
                            inputAutofocus.focus();
                        }
                    });
                    modalTitle.textContent = 'Form Tambah Data';
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
                                                <label for="fileBuku" class="form-label" id="fileBukuLabel">Pilih
                                                    File Buku (PDF)</label>
                                                <input type="file" class="form-control" accept=".pdf" id="fileBuku"
                                                    name="file_buku">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fileCover" class="form-label" id="fileCoverLabel">Pilih
                                                    File Cover (JPG, JPEG, PNG)</label>
                                                    <input type="file" class="form-control" accept=".jpg, .jpeg, .png"  id="fileCover"
                                                    name="file_cover">
                                            </div>

                                            <input type="number" class="form-control mb-3" name="tahun"
                                                placeholder="Tahun" required>
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
