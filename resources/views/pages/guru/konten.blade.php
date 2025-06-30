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
                                <form method="GET" action="{{ route('konten-digital.read') }}" class="mb-3">
                                    <div class="row d-flex justify-content-between">
                                        <!-- Date Range Picker -->
                                        <!-- <div class="col-lg-4 col-md-6 col-sm-12">
                                            <input type="text" name="dates" value="{{ request('dates') }}"
                                                class="dates form-control mb-2" />
                                        </div> -->

                                        <!-- Search Input -->
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <input type="search" id="search" name="search"
                                                class="form-control mb-2" placeholder="Cari Buku Referensi"
                                                value="{{ request('search') }}">
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                            <a href="{{ route('konten-digital.read') }}"
                                                class="btn btn-warning w-100">Reset</a>
                                            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                                        </div>
                                    </div>
                                    <hr>
                                </form>

                                <table class="table table-sm table-bordered dataTable tanpa-filter tanpa-aksi"
                                    id="table">
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
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-capitalize">{{ $data->pengarang }}</td>
                                                <td class="truncate">{{ $data->judul }}</td>
                                                <td class="truncate">{{ $data->jenis }}</td>
                                                <td class="text-uppercase">
                                                    {{ $data->created_at->format('d-m-Y h:i a') }}
                                                </td>
                                                <td>{{ $data->dilihat }}</td>
                                                <td>
                                                    <button class="mx-2 btn btn-primary editBtn"
                                                        data-id="{{ $data->id }}" data-judul="{{ $data->judul }}"
                                                        data-pengarang="{{ $data->pengarang }}"
                                                        data-penerbit="{{ $data->penerbit }}"
                                                        data-jenis="{{ $data->jenis }}"
                                                        data-url="{{ $data->url }}"
                                                        data-nuptk="{{ $data->nuptk }}"
                                                        data-cover="{{ $data->cover }}"
                                                        data-file_path="{{ $data->file_path }}" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal" data-modal-type="update"
                                                        data-cover="{{ $data->cover }}">
                                                        Edit
                                                    </button>

                                                    <button class="mx-2 btn btn-danger deleteBtn"
                                                        data-id="{{ $data->id }}" data-judul="{{ $data->judul }}"
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
                                    {{ $datas->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                                </div>
                            </div>

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
        const guruOptions = @json($guru);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
                    modalTitle.textContent = 'Form Edit Data';
                    modalForm.action = `/apps/konten-digital/update/${button.getAttribute('data-id')}`;
                    modalForm.enctype = 'multipart/form-data';

                    let jenis = button.getAttribute('data-jenis');
                    const judul = button.getAttribute('data-judul');
                    const pengarang = button.getAttribute('data-pengarang');
                    const penerbit = button.getAttribute('data-penerbit');
                    const nuptk = button.getAttribute('data-nuptk');
                    const url = button.getAttribute('data-url');
                    const file = button.getAttribute('data-file_path');
                    const cover = button.getAttribute('data-cover');

                    modalBodyHTML = `
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="jenis" id="jenisSelect" required class="form-select mb-3">
                                        <option value="" disabled>Pilih Jenis Konten</option>
                                        <option value="video" ${jenis === 'video' ? 'selected' : ''}>Video</option>
                                        <option value="buku digital" ${jenis === 'buku digital' ? 'selected' : ''}>Buku Digital</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="judul" placeholder="Judul" value="${judul}" required>
                                    <select name="nuptk" required class="form-select mb-3" id="guruSelect">
                                        <option value="" disabled>Pilih Guru (NUPTK)</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="pengarang" id="pengarang" placeholder="Pengarang" value="${pengarang}" required>
                                    <input type="text" class="form-control mb-3" name="penerbit" id="penerbit" placeholder="Penerbit" value="${penerbit}" required>
                                </div>
                                 <div class="col-md-6">
                                    <div id="coverGroup" class="mb-3>
                                        <label class="form-label">Cover Preview</label>
                                        <input type="file" class="form-control" name="cover" accept=".jpg, .jpeg, .png"  placeholder="File Cover">
                                        ${cover ? `<a href="/storage/${cover}" target="_blank" class="d-block mt-2 text-info">Lihat File Cover</a>` : ''}
                                    </div>
                                    <div id="urlGroup" class="mb-3 ${jenis === 'video' ? '' : 'd-none'}">
                                        <label class="form-label">Link URL Youtube</label>
                                        <input type="text" class="form-control" name="url" placeholder="Link URL Youtube" value="${url ?? ''}">
                                    </div>
                                    <div id="coverGroup" class="mb-3>
                                        <label class="form-label">Cover Preview</label>
                                        <input type="file" class="form-control" name="cover" accept=".jpg, .jpeg, .png"  placeholder="File Cover">
                                        ${cover ? `<a href="/storage/${cover}" target="_blank" class="d-block mt-2 text-info">Lihat File Cover</a>` : ''}
                                    </div>
                                    <div id="fileGroup" class="mb-3 ${jenis === 'buku digital' ? '' : 'd-none'}">
                                        <label class="form-label">Buku Digital</label>
                                        <input type="file" class="form-control" name="file_path" placeholder="Buku Digital">
                                        ${file ? `<a href="/storage/${file}" target="_blank" class="d-block mt-2 text-info">Lihat File Buku</a>` : ''}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="reset" class="btn btn-primary me-2" id="closeModal" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        `;
                    setTimeout(() => {
                        const guruSelect = document.getElementById('guruSelect');
                        const pengarangInput = document.getElementById('pengarang');
                        const jenisSelect = document.getElementById('jenisSelect');
                        const urlGroup = document.getElementById('urlGroup');
                        const fileGroup = document.getElementById('fileGroup');

                        if (guruSelect && guruOptions.length > 0) {
                            guruOptions.forEach(guru => {
                                const option = document.createElement('option');
                                option.value = guru.nisn;
                                option.textContent = `${guru.fullname} (${guru.nisn})`;
                                if (guru.nisn === nuptk) option.selected = true;
                                guruSelect.appendChild(option);
                            });

                            guruSelect.addEventListener('change', function() {
                                const selectedGuru = guruOptions.find(g => g.nisn ===
                                    this.value);
                                if (selectedGuru && pengarangInput) {
                                    pengarangInput.value = selectedGuru.fullname;
                                }
                            });
                        }


                        // Toggle field berdasarkan jenis konten
                        if (jenisSelect) {
                            jenisSelect.addEventListener('change', function() {
                                const isVideo = this.value === 'video';
                                const isBook = this.value === 'buku digital';

                                urlGroup.classList.toggle('d-none', !isVideo);
                                fileGroup.classList.toggle('d-none', !isBook);
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
                                    <select name="jenis" id="jenisSelect" required class="form-select mb-3">
                                        <option value="" disabled selected>Pilih Jenis Konten</option>
                                        <option value="video">Video</option>
                                        <option value="buku digital">Buku Digital</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="judul" placeholder="Judul" required>
                                    <select name="nuptk" required class="form-select mb-3" id="guruSelect">
                                        <option value="" disabled selected>Pilih Guru (NUPTK)</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    <input type="text" class="form-control mb-3" name="pengarang" id="pengarang" placeholder="Pengarang" value="" required>
                                    <input type="text" class="form-control mb-3" name="penerbit" id="penerbit" placeholder="Penerbit" value="" required>
                                </div>
                                <div class="col-md-6">
                                    <div id="urlGroup" class="mb-3 d-none">
                                        <label for="url" class="form-label">Link URL Youtube</label>
                                        <input type="text" class="form-control" name="url" placeholder="Link URL Youtube">
                                    </div>
                                    <div id="fileGroup" class="mb-3 d-none">
                                        <label for="file_path" class="form-label">Buku Digital</label>
                                        <input type="file" class="form-control" name="file_path" placeholder="Buku Digital">
                                    </div>
                                    <div id="coverGroup" class="mb-3>
                                        <label for="cover" class="form-label">Cover Preview</label>
                                        <input type="file" class="form-control" name="cover" accept=".jpg, .jpeg, .png"  placeholder="File Cover">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="reset" class="btn btn-primary me-2" id="closeModal" data-bs-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        `;
                    setTimeout(() => {
                        const guruSelect = document.getElementById('guruSelect');
                        const pengarangInput = document.getElementById('pengarang');
                        const jenisSelect = document.getElementById('jenisSelect');
                        const urlGroup = document.getElementById('urlGroup');
                        const fileGroup = document.getElementById('fileGroup');

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
                                if (selectedGuru && pengarangInput) {
                                    pengarangInput.value = selectedGuru.fullname;
                                }
                            });
                        }


                        if (jenisSelect) {
                            jenisSelect.addEventListener('change', function() {
                                if (this.value === 'video') {
                                    urlGroup.classList.remove('d-none');
                                    fileGroup.classList.add('d-none');
                                } else if (this.value === 'buku digital') {
                                    fileGroup.classList.remove('d-none');
                                    urlGroup.classList.add('d-none');
                                } else {
                                    urlGroup.classList.add('d-none');
                                    fileGroup.classList.add('d-none');
                                }
                            });
                        }
                    }, 100);
                }

                modalContent.innerHTML = modalBodyHTML;
            });

            dynamicModal.addEventListener('hidden.bs.modal', function() {
                modalContent.innerHTML = '';
            });
        });
    </script>
</x-app-layout>
