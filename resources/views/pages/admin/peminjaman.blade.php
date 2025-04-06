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
                            </ul>

                            {{-- End Nav Tabs --}}
                            <div class="tab-content mt-4" id="TabsContent">
                                <!-- Tab Buku Referensi -->
                                <div class="tab-pane fade show" id="referensi" role="tabpanel"
                                    aria-labelledby="referensi-tab">
                                    <div class="table-responsive text-center">
                                        {{-- Filter --}}
                                        <form method="GET" action="{{ route('peminjaman.read') }}" class="mb-3">
                                            <div class="row d-flex justify-content-between">
                                                <!-- Date Range Picker -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="text" name="dates" value="{{ request('dates') }}"
                                                        class="dates form-control mb-2" />
                                                </div>

                                                <!-- Search Input -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <input type="search" id="search" name="search"
                                                        class="form-control mb-2"
                                                        placeholder="Cari Peminjaman Buku Referensi"
                                                        value="{{ request('search') }}">
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                                    <a href="{{ route('peminjaman.read') }}"
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
                                                    <th scope="col">Judul Buku</th>
                                                    <th scope="col">No Regis</th>
                                                    <th scope="col">Tanggal Pengajuan</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($referensi as $BukuReferensi)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-capitalize">{{ $BukuReferensi->fullname }}</td>
                                                        <td class="truncate">{{ $BukuReferensi->judul }}</td>
                                                        <td class="truncate">{{ $BukuReferensi->no_regis }}</td>
                                                        <td class="text-uppercase">
                                                            {{ $BukuReferensi->tgl_pinjam->format('d-m-Y h:i a') }}
                                                        </td>
                                                        <td>
                                                            @if($BukuReferensi->pinjam == 'verifikasi')
                                                                <button class="mx-2 btn btn-success accBtn"
                                                                    data-id="{{ $BukuReferensi->id }}"
                                                                    data-no_regis="{{ $BukuReferensi->no_regis }}"
                                                                    data-fullname="{{ $BukuReferensi->fullname }}"
                                                                    data-pinjam="terima"
                                                                    data-bs-toggle="modal" data-bs-target="#dynamicModal"
                                                                    data-modal-type="accept">
                                                                    Terima
                                                                </button>

                                                                <button class="mx-2 btn btn-warning dclBtn"
                                                                    data-id="{{ $BukuReferensi->id }}"
                                                                    data-no_regis="{{ $BukuReferensi->no_regis }}"
                                                                    data-fullname="{{ $BukuReferensi->fullname }}"
                                                                    data-pinjam="tolak"
                                                                    data-modal-type="decline" data-bs-toggle="modal"
                                                                    data-bs-target="#dynamicModal">
                                                                    Tolak
                                                                </button>
                                                            @else
                                                                @if($BukuReferensi->pinjam == 'terima')
                                                                    <span class="btn btn-primary w-100">Diterima</span>
                                                                @else
                                                                    <span class="btn btn-danger w-100">Ditolak</span>
                                                                @endif
                                                            @endif
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
                                        <form method="GET" action="{{ route('peminjaman.read') }}" class="mb-3">
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
                                                        class="form-control mb-2"
                                                        placeholder="Cari Peminjaman Buku Paket"
                                                        value="{{ request('search') }}">
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                                    <a href="{{ route('peminjaman.read') }}"
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
                                                    <th scope="col">Judul Buku</th>
                                                    <th scope="col">No Regis</th>
                                                    <th scope="col">Tanggal Pengajuan</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paket as $BukuPaket)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-capitalize">{{ $BukuPaket->fullname }}</td>
                                                        <td class="truncate">{{ $BukuPaket->judul }}</td>
                                                        <td class="truncate">{{ $BukuPaket->no_regis }}</td>
                                                        <td class="text-uppercase">
                                                            {{ $BukuPaket->tgl_pinjam->format('d-m-Y h:i a') }}
                                                        </td>
                                                        <td>
                                                            @if($BukuPaket->pinjam == 'verifikasi')
                                                            <button class="mx-2 btn btn-success accBtn"
                                                                data-id="{{ $BukuPaket->id }}"
                                                                data-no_regis="{{ $BukuPaket->no_regis }}"
                                                                data-fullname="{{ $BukuPaket->fullname }}"
                                                                data-bs-toggle="modal" data-bs-target="#dynamicModal"
                                                                data-modal-type="accept">
                                                                Terima
                                                            </button>

                                                            <button class="mx-2 btn btn-warning dclBtn"
                                                                data-id="{{ $BukuPaket->id }}"
                                                                data-no_regis="{{ $BukuPaket->no_regis }}"
                                                                data-fullname="{{ $BukuPaket->fullname }}"
                                                                data-modal-type="decline" data-bs-toggle="modal"
                                                                data-bs-target="#dynamicModal">
                                                                Tolak
                                                            </button>
                                                        @else
                                                            @if($BukuPaket->pinjam == 'terima')
                                                                <span class="btn btn-primary w-100">Diterima</span>
                                                            @else
                                                                <span class="btn btn-danger w-100">Ditolak</span>
                                                            @endif
                                                        @endif
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
                <div class="modal modal-lg fade" id="dynamicModal" tabindex="-1"
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

            setTimeout(() => {
                    const tambahButtons = document.querySelectorAll('#tambahButton');
                    tambahButtons.forEach(button => {
                        button.classList.add('d-none');
                    });
                }, 1);
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
                const id = button.getAttribute('data-id');
                let modalBodyHTML = "";
                modalForm.enctype = "application/x-www-form-urlencoded";
                modalForm.method = "POST";
                if (modalType === 'accept') {
                    modalForm.enctype = 'multipart/form-data';
                    modalTitle.textContent = 'Konfirmasi Terima Pinjaman';
                    modalForm.action = `/apps/peminjaman/accept/${id}`;
                    var modal = document.getElementById('dynamicModal');

                    const bukuData = button.dataset;

                    modalBodyHTML = `
                         @csrf
                    @method('PUT')
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                    <p class="text-center fs-5 text-capitalize">Apakah Anda ingin menerima <strong>${button.getAttribute('data-fullname')}</strong> <br/> untuk meminjam buku dengan <br/><strong>nomor registrasi <br/> ${button.getAttribute('data-no_regis')}</strong>?</p>
                                    <div class="d-flex justify-content-end mt-3">
                                        <input type="hidden" name="fullname" value="${button.getAttribute('data-fullname')}">
                                        <input type="hidden" name="no_regis" value="${button.getAttribute('data-no_regis')}">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-success">Ya</button>
                                    </div>
                        `;
                } if (modalType === 'decline') {
                    modalForm.enctype = 'multipart/form-data';
                    modalTitle.textContent = 'Konfirmasi Penolakan Pinjaman';
                    modalForm.action = `/apps/peminjaman/decline/${id}`;
                    modalBodyHTML = `
                             @csrf
                    @method('PUT')
                        <p class="text-center fs-5 text-capitalize">Apakah Anda ingin menolak <strong>${button.getAttribute('data-fullname')}</strong> <br/> untuk meminjam buku dengan <br/><strong>nomor registrasi <br/> ${button.getAttribute('data-no_regis')}</strong>?</p>
                                    <div class="d-flex justify-content-end mt-3">
                                                 <input type="hidden" name="fullname" value="${button.getAttribute('data-fullname')}">
                                        <input type="hidden" name="no_regis" value="${button.getAttribute('data-no_regis')}">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-danger">Ya</button>
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
