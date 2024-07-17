@props([
'href' => null,
'icon',
'theme' => 'default',
'span' => false,
'size' => 'sm',
'round' => false,
])

@php
$classes = ' transition-colors inline-block  flex justify-center items-center ';

$classes .= match($theme){
'default' => 'bg-white text-black-darker dark:bg-dark-dark dark:text-white hover:bg-purple hover:text-white dark:hover:bg-purple dark:hover:text-white',
'purple' => 'bg-purple text-white',
'light' => 'bg-black-dark text-white dark:bg-white dark:text-black-darker hover:bg-purple hover:text-white dark:hover:bg-purple dark:hover:text-white',
'always-light' => 'bg-white text-black-darker hover:bg-purple hover:text-white',
'transparent' => '',
};

$classes .= match($size){
'sm' => ' w-8 h-8 ',
'md' => ' w-10 h-10 ',
'lg' => ' w-24 h-24 ',
};

$iconWidth = match($size){
'md' => 'w-6',
default => 'w-4',
};

$classes .= $round ? ' rounded-full ' : ' rounded-md ';

@endphp

@if ($span)
<span {{ $attributes->merge(['class' => $classes]) }}>
    <x-dynamic-component :component="'icon.' . $icon" :class="$iconWidth" />
</span>
@elseif ($href)
<a {{ $attributes->merge(['class' => $classes]) }} href="{{ $href }}">
    <x-dynamic-component :component="'icon.' . $icon" :class="$iconWidth" />
</a>
@else
<button type="submit" {{ $attributes->merge(['class' => $classes]) }}>
    <x-dynamic-component :component="'icon.' . $icon" :class="$iconWidth" />
</button>
@endif
