@if ($paginator->hasPages())
    <nav>
        <ul class="pagination pagination-sm mb-0">
            {{-- السابق --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="font-size: 12px; padding: 4px 10px;">« السابق</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="font-size: 12px; padding: 4px 10px;">« السابق</a>
                </li>
            @endif

            {{-- الأرقام --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link" style="font-size: 12px; padding: 4px 8px;">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link" style="font-size: 12px; padding: 4px 8px;">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}" style="font-size: 12px; padding: 4px 8px;">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- التالي --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="font-size: 12px; padding: 4px 10px;">التالي »</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="font-size: 12px; padding: 4px 10px;">التالي »</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
