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
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Dipinjam</th>
                                            <th>Dikembalikan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}.</td>
                                                <td><strong>{{ $user->fullname }}</strong></td>
                                                <td>{{ $user->dipinjam }}</td>
                                                <td>{{ $user->dikembalikan }}</td>
                                                <td>{{ $user->status }}</td>
                                                <td>
                                                    @if ($user->status == 'Sesuai')
                                                        <a href="{{ route('bebas-pustaka', $user->nisn) }}"
                                                            class="btn btn-primary">Print</a>
                                                    @else
                                                        <button class="btn btn-danger" disabled>Ditahan</button>
                                                    @endif
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
            <x-app.footer />
        </div>
    </main>
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
