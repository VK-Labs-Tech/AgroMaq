@props(['title' => null, 'headerClass' => ''])

<div {{ $attributes->class(['app-panel']) }}>
    @if($title !== null)
        <div class="card-header bg-transparent border-0 pt-3 px-4 {{ $headerClass }}">
            <strong>{{ $title }}</strong>
        </div>
    @endif

    <div class="card-body px-4 pb-4 pt-{{ $title !== null ? '2' : '4' }}">
        {{ $slot }}
    </div>
</div>
