@props(['type' => 'info'])

@php
    $iconMap = [
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-exclamation-triangle-fill',
        'error'   => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info'    => 'bi-info-circle-fill',
    ];
    $bootstrapType = ($type === 'error') ? 'danger' : $type;
    $icon = $iconMap[$type] ?? 'bi-info-circle-fill';
@endphp

<div class="alert alert-{{ $bootstrapType }} alert-dismissible fade show d-flex align-items-center shadow-sm rounded-3" role="alert">
    <i class="bi {{ $icon }} fs-5 me-2"></i>
    <div class="flex-grow-1">
        {{ $slot }}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
