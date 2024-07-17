@props([
    'type' => '',
    'message' => '',
    'textColor' => 'text-white',
    'buttonColors' => 'text-black',
    'close' => true,
])
@php
    $bgColor = match ($type) {
        'success' => 'bg-green-300',
        'error' => 'bg-red-300',
        default => 'bg-blue-300',
    };

    // $textColor = match ($type) {
    //     'sucess' => 'text-white',
    //     'error' => 'text-white',
    //     default => 'text-white',
    // };

    $buttonColors = match ($type) {
        'sucess' => 'text-green',
        'error' => 'text-red',
        default => 'text-white',
    }
@endphp



<div {{ $attributes->merge(['class' => "rounded-md $bgColor p-4 mb-4"]) }} x-data="{ open: true }" x-cloak x-show="open">
    <div class="flex">
        <div class="flex-shrink-0">
            @switch ($type)
            @case('success')
            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            @break
            @case('warning')
            <!-- Heroicon name: solid/exclamation -->
            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            @break
            @case('error')
            <!-- Heroicon name: solid/x-circle -->
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            @break
            @case('notice')
            <x-button-icon icon="{{ $icon ?? 'notice' }}" theme="always-light" />
            @break
            @default
            <!-- Heroicon name: solid/information-circle -->
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            @endswitch
        </div>
        <div class="ml-3 flex items-center">
            @if($message)
            <p class="text-sm font-medium {{ $textColor }}">{{ $message }}</p>
            {{-- @else
            <p class="text-sm font-medium {{ $textColor }}">{!! \Stevebauman\Purify\Facades\Purify::clean($message) !!}</p> --}}
            @endif
        </div>
        @if($close)
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $buttonColors }}" @click="open = false">
                    <span class="sr-only">Dismiss</span>
                    <!-- Heroicon name: solid/x -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
