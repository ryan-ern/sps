<?php
    $segments = request()->segments();
?>
<nav class="navbar navbar-main navbar-expand-lg mx-5 px-0 shadow-none rounded" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
                @foreach($segments as $index => $segment)
                <li class="breadcrumb-item text-sm {{ $loop->last ? 'text-dark active' : '' }}" aria-current="page">
                    @if(!$loop->last)
                        <a class="opacity-5 text-dark" href="/{{ implode('/', array_slice($segments, 0, $index + 1)) }}">{{ ucfirst($segment) }}</a>
                    @else
                        {{ ucfirst($segment) }}
                    @endif
                </li>
            @endforeach
            </ol>
            <h6 class="font-weight-bold mb-0">
                {{ ucfirst(end($segments) ?? 'Dashboard') }}
            </h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                &nbsp;
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item px-3 d-flex align-items-center"></li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="login" onclick="event.preventDefault();
                    this.closest('form').submit();">
                            <button class="btn btn-sm  btn-outline-dark  mb-0 me-1" type="submit">Logout</button>
                        </a>
                    </form>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
