<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="table-responsive text-center">
                                {{-- export & import --}}
                                <form action="{{ route('anggota.import') }}" method="POST" enctype="multipart/form-data"
                                    class="d-inline">
                                    @csrf
                                    <div class="row g-2 mb-3">
                                        <div class="col-12 col-lg-6 col-md-12">
                                            <div class="input-group">
                                                <input type="file" name="file" accept=".xls, .xlsx"
                                                    class="form-control mb-3" required>
                                                <button class="btn btn-primary" type="submit">Import</button>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-3 col-md-6">
                                            <a href="{{ route('anggota.exportSample') }}"
                                                class="btn btn-info w-100">Unduh Contoh Import</a>
                                        </div>

                                        <div class="col-12 col-lg-3 col-md-6">
                                            <a href="{{ route('kartu-anggota', ['search' => request('search'), 'dates' => request('dates')]) }}"
                                                class="btn btn-primary w-100">Unduh Kartu Anggota</a>
                                        </div>
                                    </div>
                                </form>

                                <hr>
                                {{-- Filter --}}
                                <form method="GET" action="{{ route('anggota.read') }}" class="mb-3">
                                    <div class="row d-flex justify-content-between">
                                        <!-- Date Range Picker -->
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <input type="text" name="dates" value="{{ request('dates') }}"
                                                class="dates form-control mb-2" />
                                        </div>

                                        <!-- Search Input -->
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <input type="search" id="search" name="search"
                                                class="form-control mb-2" placeholder="Cari Data Anggota"
                                                value="{{ request('search') }}">
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                            <a href="{{ route('anggota.read') }}"
                                                class="btn btn-warning w-100">Reset</a>
                                            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                                        </div>
                                    </div>
                                    <hr>
                                </form>
                                <table class="table table-sm table-bordered dataTable" id="table">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">NISN/NUPTK</th>
                                            <th scope="col">Kelas</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Tanggal Diperbarui</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $dataUser)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-capitalize">{{ $dataUser->fullname }}</td>
                                                <td class="truncate">{{ $dataUser->nisn }}</td>
                                                <td class="truncate">{{ $dataUser->kelas }}</td>
                                                <td>{{ $dataUser->role }}</td>
                                                <td>{{ $dataUser->status }}</td>
                                                <td class="text-uppercase">
                                                    {{ $dataUser->updated_at->format('d-m-Y h:i a') }}
                                                </td>
                                                <td>
                                                    <button class="mx-2 btn btn-primary editBtn"
                                                        data-nisn="{{ $dataUser->nisn }}"
                                                        data-fullname="{{ $dataUser->fullname }}"
                                                        data-kelas="{{ $dataUser->kelas }}"
                                                        data-role="{{ $dataUser->role }}"
                                                        data-username="{{ $dataUser->username }}"
                                                        data-email="{{ $dataUser->email }}"
                                                        data-status="{{ $dataUser->status }}" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal" data-modal-type="update">
                                                        Edit
                                                    </button>

                                                    <button class="mx-2 btn btn-danger deleteBtn"
                                                        data-nisn="{{ $dataUser->nisn }}"
                                                        data-fullname="{{ $dataUser->fullname }}"
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
                                    {{ $users->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
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
        document.addEventListener("DOMContentLoaded", function() {
            const dynamicModal = document.getElementById('dynamicModal');
            const modalTitle = dynamicModal.querySelector('.modal-title');
            const modalContent = document.getElementById('modalContent');
            const modalForm = document.getElementById('dynamicModalForm');

            dynamicModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const modalType = button.getAttribute('data-modal-type');
                const userId = button.getAttribute('data-nisn');

                let modalBodyHTML = "";
                modalForm.enctype = "application/x-www-form-urlencoded";
                modalForm.method = "POST";

                if (modalType === 'update') {
                    modalTitle.textContent = 'Form Edit Data';
                    modalForm.action = `/apps/anggota/update/${userId}`;
                    modalForm.enctype = 'multipart/form-data';
                    var modal = document.getElementById('dynamicModal');
                    var inputAutofocus = modal.querySelector('input[autofocus]');

                    modal.addEventListener('shown.bs.modal', function() {
                        if (inputAutofocus) {
                            inputAutofocus.focus();
                        }
                    });

                    const userData = button.dataset;

                    modalBodyHTML = `
                         @csrf
                    @method('PUT')
                     <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <label for="nisn" class="form-label">NISN/NUPTK:</label>
                                            <input type="text" class="form-control mb-3" name="nisn"
                                                placeholder="NISN/NUPTK" value="${userData.nisn}" readonly required>
                                            <label for="fullname" class="form-label">Nama Lengkap:</label>
                                            <input type="text" class="form-control mb-3" name="fullname"
                                                placeholder="Nama Lengkap" value="${userData.fullname}" required>
                                            <label for="kelas" class="form-label">Kelas:</label>
                                            <input type="text" class="form-control mb-3" name="kelas"
                                                placeholder="Kelas" value="${userData.kelas}"  required>
                                            <label for="role" class="form-label">Role:</label>
                                            <select class="form-select mb-3" name="role" required>
                                                <option value="admin" ${userData.role === 'admin' ? 'selected' : ''}>Admin</option>
                                                <option value="guru" ${userData.role === 'guru' ? 'selected' : ''}>Guru</option>
                                                <option value="siswa" ${userData.role === 'siswa' ? 'selected' : ''}>Siswa</option>
                                            </select>
                                            <label for="status" class="form-label">Status:</label>
                                            <select class="form-select mb-3" name="status" required>
                                                <option value="aktif" ${userData.status === 'aktif' ? 'selected' : ''} >Aktif</option>
                                                <option value="tidak aktif" ${userData.status === 'tidak aktif' ? 'selected' : ''}>Tidak aktif</option>
                                            </select>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <label for="Email" class="form-label">Email:</label>
                                            <input type="email" class="form-control mb-3" name="email"
                                                placeholder="Email" value="${userData.email || '-'}" required>
                                            <label for="username" class="form-label">Username:</label>
                                            <input type="text" class="form-control mb-3" name="username"
                                                placeholder="Username" value="${userData.username}" readonly required>
                                            <label for="password" class="form-label">Password:</label>
                                            <input type="password" class="form-control mb-3" name="password"
                                                placeholder="Password">
                                        </div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="reset" class="btn btn-primary me-2" id="closeModal"
                                            data-bs-dismiss="modal">Kembali</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                    `;
                } else if (modalType === 'delete') {
                    modalTitle.textContent = 'Konfirmasi Penghapusan Data';
                    modalForm.action = `/apps/anggota/delete/${userId}`;
                    modalForm.method = 'POST';
                    modalBodyHTML = `
                        @csrf
                        @method('DELETE')
                        <p class="text-center fs-5 text-capitalize">Apakah Anda yakin ingin menghapus <br/> data ${button.getAttribute('data-fullname')} dengan NISN/NUPTk <br/><strong> ${button.getAttribute('data-nisn')}</strong>?</p>
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
                    modalForm.action = '/apps/anggota/create';
                    modalForm.enctype = 'multipart/form-data';
                    modalForm.method = 'POST';
                    modalBodyHTML = `
                        @csrf
                    @method('POST')
                    <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <input type="text" class="form-control mb-3" name="nisn"
                                                placeholder="NISN/NUPTK" required>
                                            <input type="text" class="form-control mb-3" name="fullname"
                                                placeholder="Nama Lengkap" required>
                                            <input type="text" class="form-control mb-3" name="kelas"
                                                placeholder="Kelas"  required>
                                            <select class="form-select mb-3" name="role" required>
                                                <option value="" selected disabled>Pilih Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="guru">Guru</option>
                                                <option value="siswa">Siswa</option>
                                            </select>
                                            <select class="form-select mb-3" name="status" required>
                                                <option value="aktif" selected>Aktif</option>
                                                <option value="tidak aktif">tidak aktif</option>
                                            </select>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <input type="email" class="form-control mb-3" name="email"
                                                placeholder="Email">
                                            <input type="text" class="form-control mb-3" name="username"
                                                placeholder="Username" required>
                                            <input type="password" class="form-control mb-3" name="password"
                                                placeholder="Password" required>
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
                setInterval(() => {
                    const nisnInput = document.querySelector('input[name="nisn"]');
                    const fullnameInput = document.querySelector('input[name="fullname"]');
                    const usernameInput = document.querySelector('input[name="username"]');
                    const passwordInput = document.querySelector('input[name="password"]');

                    function updateUsernameAndPassword() {
                        const nisn = nisnInput.value.trim();
                        const fullname = fullnameInput.value.trim();

                        if (nisn.length >= 4 && fullname.length > 0) {
                            // Username = NISN langsung
                            usernameInput.value = nisn;

                            // Ambil 4 digit terakhir NISN
                            const last4Nisn = nisn.slice(-4);

                            // Ambil kata terakhir dari nama (belakang) atau tengah
                            const nameParts = fullname.split(' ');
                            const nameBase = nameParts.length > 1 ? nameParts[nameParts.length -
                                1] : nameParts[0];

                            // Password = nama belakang/tengah + 4 digit akhir nisn
                            passwordInput.value = nameBase.toLowerCase() + last4Nisn;
                        }
                    }

                    // Jalankan saat nama lengkap atau nisn diubah
                    nisnInput.addEventListener('input', updateUsernameAndPassword);
                    fullnameInput.addEventListener('input', updateUsernameAndPassword);
                }, 100);
            });

            dynamicModal.addEventListener('hidden.bs.modal', function() {
                modalContent.innerHTML = '';
            });
        });
    </script>
</x-app-layout>
