@if ($paginator->hasPages())
<ul class="wg-pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <li class="disabled" aria-disabled="true"><i class="icon-chevron-left"></i></li>
    @else
    <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="icon-chevron-left"></i></a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <li class="disabled" aria-disabled="true">{{ $element }}</li>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li class="active" aria-current="page"><a href="#">{{ $page }}</a></li>
    @else
    <li><a href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="icon-chevron-right"></i></a></li>
    @else
    <li class="disabled" aria-disabled="true"><i class="icon-chevron-right"></i></li>
    @endif
</ul>
@endif
