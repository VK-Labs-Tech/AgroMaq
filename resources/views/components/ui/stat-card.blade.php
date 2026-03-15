@props(['label', 'value'])

<div class="app-panel h-100">
    <div class="card-body px-4 py-4">
        <small class="app-muted d-block mb-1">{{ $label }}</small>
        <h3 class="mb-0 app-title" style="font-size: 1.65rem;">{{ $value }}</h3>
    </div>
</div>
