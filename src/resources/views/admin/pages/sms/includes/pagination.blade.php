<span class="mr-3"><b>{{ $paginator->currentPage() }}</b> of <b>{{ $paginator->lastPage() }}</b></span>

@if ($paginator->hasPages())
    <div class="btn-group">

        @if ($paginator->onFirstPage())
            <button type="button" class="btn btn-outline-primary" style="min-width:50px;" disabled>
                <span class="icon-arrow-left"></span>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-outline-primary" style="min-width:50px;">
                <span class="icon-arrow-left"></span>
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="prev" class="btn btn-outline-primary" style="min-width:50px;">
                <span class="icon-arrow-right"></span>
            </a>
        @else
            <button type="button" class="btn btn-outline-primary" style="min-width:50px;" disabled>
                <span class="icon-arrow-right"></span>
            </button>
        @endif

    </div>
@else

    <div class="btn-group">

        <a type="button" class="btn btn-outline-primary disabled" style="min-width:50px;" disabled>
            <span class="icon-arrow-left"></span>
        </a>
        <a type="button" class="btn btn-outline-primary disabled" style="min-width:50px;" disabled>
            <span class="icon-arrow-right"></span>
        </a>

    </div>

@endif
