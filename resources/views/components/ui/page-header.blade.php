@props(['title', 'subtitle' => null])

<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
    <div>
        <h2 class="mb-1 app-title" style="font-size: 1.4rem;">{{ $title }}</h2>
        @if($subtitle)
            <p class="mb-0 app-muted">{{ $subtitle }}</p>
        @endif
    </div>

    <div>
        {{ $actions ?? '' }}
    </div>
</div>
