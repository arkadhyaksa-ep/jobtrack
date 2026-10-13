@if ($paginator->hasPages())
    <nav class="pagination-shell" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <p class="pagination-summary">
            @if ($paginator->firstItem())
                {{ __('Showing') }} <strong>{{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }}</strong> {{ __('of') }} <strong>{{ $paginator->total() }}</strong> {{ __('results') }}
            @else
                {{ __('Showing') }} <strong>0</strong> {{ __('results') }}
            @endif
        </p>

        <div class="pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="pagination-disabled pagination-link--direction" aria-disabled="true">Previous</span>
            @else
                <a class="pagination-link pagination-link--direction" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">Previous</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-current" aria-current="page" aria-label="{{ __('Current page, page :page', ['page' => $page]) }}">{{ $page }}</span>
                        @else
                            <a class="pagination-link" href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-link pagination-link--direction" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">Next</a>
            @else
                <span class="pagination-disabled pagination-link--direction" aria-disabled="true">Next</span>
            @endif
        </div>
    </nav>
@endif
