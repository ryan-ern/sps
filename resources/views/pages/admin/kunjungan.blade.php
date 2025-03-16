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
                                <form method="GET" action="{{ route('kunjungan.read') }}" class="mb-3">
                                    <div class="row d-flex justify-content-between">
                                        <!-- Date Range Picker -->
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <input type="text" name="dates" value="{{ request('dates') }}"
                                                class="dates form-control mb-2" />
                                        </div>

                                        <!-- Search Input -->
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <input type="search" id="search" name="search"
                                                class="form-control mb-2" placeholder="Cari Data Kunjungan"
                                                value="{{ request('search') }}">
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-lg-4 d-inline-flex gap-2 col-md-4 col-sm-12">
                                            <a href="{{ route('kunjungan.read') }}"
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
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Tanggal Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kunjungans as $dataKunjungan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-capitalize">{{ $dataKunjungan->fullname }}</td>
                                                <td class="truncate">{{ $dataKunjungan->nisn }}</td>
                                                <td class="truncate">{{ $dataKunjungan->kelas }}</td>
                                                <td>{{ $dataKunjungan->keterangan }}</td>
                                                <td class="text-uppercase">
                                                    {{ $dataKunjungan->created_at->format('d-m-Y h:i a') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- Tambahkan Navigasi Pagination --}}
                                <div class="d-flex justify-content-between mt-3">
                                    {{ $kunjungans->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>
