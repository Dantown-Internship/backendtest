@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link text-secondary'
            : 'nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
