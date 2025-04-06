<?php
$segments = request()->segments();
?>
<nav class="navbar navbar-main navbar-expand-lg mx-3 px-0 shadow-none rounded" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-2 d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb" class="d-flex flex-column">
            <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5 text-capitalize">
                @foreach ($segments as $index => $segment)
                    <li class="breadcrumb-item text-sm {{ $loop->last ? 'text-dark active' : '' }}" aria-current="page">
                        @if (!$loop->last)
                            <a class="opacity-5 text-dark"
                                href="/{{ implode('/', array_slice($segments, 0, $index + 1)) }}">
                                {{ ucfirst(str_replace('-', ' ', $segment)) }}
                            </a>
                        @else
                            {{ ucfirst(str_replace('-', ' ', $segment)) }}
                        @endif
                    </li>
                @endforeach
            </ol>
            <h6 class="font-weight-bold mb-0 text-capitalize">
                {{ ucfirst(str_replace('-', ' ', end($segments) ?? 'Dashboard')) }}
            </h6>
        </nav>

        <div class="d-flex align-items-center">
            <form method="POST" action="{{ route('logout') }}" class="me-3 mb-0">
                @csrf
                <button class="btn btn-sm btn-outline-dark mb-0 fs-6" type="submit">Logout</button>
            </form>
            <a href="javascript:;" class="nav-link text-body p-0 d-xl-none" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </div>
    </div>
</nav>
<!-- End Navbar -->
