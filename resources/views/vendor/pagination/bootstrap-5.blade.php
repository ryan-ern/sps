@if ($paginator->hasPages())
    <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center">
        <p class="small text-muted">
            {!! __('Menampilkan') !!}
            <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
            {!! __('-') !!}
            <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
            {!! __('dari') !!}
            <span class="fw-semibold">{{ $paginator->total() }}</span>
            {!! __('data') !!}
        </p>
    </div>
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                {{-- Tombol "Awal" --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Awal</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">Awal</a></li>
                @endif

                {{-- Tombol "Previous" --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
                    </li>
                @endif

                {{-- Tombol "Next" --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                @endif

                {{-- Tombol "Akhir" --}}
                @if ($paginator->currentPage() == $paginator->lastPage())
                    <li class="page-item disabled"><span class="page-link">Akhir</span></li>
                @else
                    <li class="page-item"><a class="page-link"
                            href="{{ $paginator->url($paginator->lastPage()) }}">Akhir</a></li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center">
            <div>
                <ul class="pagination">
                    {{-- Tombol "Awal" --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Awal</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">Awal</a></li>
                    @endif

                    {{-- Tombol "Previous" --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link"
                                href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @php
                                $currentPage = $paginator->currentPage();
                                $lastPage = $paginator->lastPage();
                            @endphp

                            @foreach ($element as $page => $url)
                                {{-- Tampilkan halaman saat ini dan beberapa di sekitarnya --}}
                                @if ($page == $currentPage)
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @elseif ($page >= $currentPage - 1 && $page <= $currentPage + 1)
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol "Next" --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                    @endif

                    {{-- Tombol "Akhir" --}}
                    @if ($paginator->currentPage() == $paginator->lastPage())
                        <li class="page-item disabled"><span class="page-link">Akhir</span></li>
                    @else
                        <li class="page-item"><a class="page-link"
                                href="{{ $paginator->url($paginator->lastPage()) }}">Akhir</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
