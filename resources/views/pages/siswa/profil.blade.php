<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('profil.update') }}" method="POST">
                        @csrf
                        <div class="card p-5">
                            <div class="row">
                                <div class="col">
                                    <label for="nisn">NISN/NIS</label>
                                    <input type="text" name="nisn" class="form-control mb-3"
                                        value="{{ $user->nisn }}" readonly>
                                </div>
                                <div class="col">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control mb-3"
                                        value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" name="fullname" class="form-control mb-3"
                                        value="{{ $user->fullname }}">
                                </div>
                                <div class="col">
                                    <label for="old_password">Password Lama</label>
                                    <input type="password" name="old_password" class="form-control mb-3">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="kelas">Kelas</label>
                                    <input type="text" name="kelas" class="form-control mb-3"
                                        value="{{ $user->kelas }}">
                                </div>
                                <div class="col">
                                    <label for="new_password">Password Baru</label>
                                    <input type="password" name="new_password" class="form-control mb-3">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>
