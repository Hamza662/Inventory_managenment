@php
    $title = $title ?? 'No data found';
    $text = $text ?? 'There is nothing to show here yet.';
    $action = $action ?? null;
    $actionLabel = $actionLabel ?? 'Create new';
@endphp

<div class="empty-state">
    <div class="empty-icon">
        <i class="bx bx-folder-open"></i>
    </div>
    <h4>{{ $title }}</h4>
    <p>{{ $text }}</p>
    @if ($action)
        <a href="{{ $action }}" class="btn btn-primary">{{ $actionLabel }}</a>
    @endif
</div>
