<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link href="{{ asset('assets/bsstyle.css') }}" rel="stylesheet">
      {{--   @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if(session()->has('successMessage'))
            <div class="container my-2"> 
                <div class="alert alert-success">
                  <strong>Success!</strong> {{ session()->get('successMessage') }}
                </div>
            </div>
            @endif
            @if(session()->has('errorMessage'))
            <div class="container my-2"> 
                <div class="alert alert-danger">
                  <strong>Error!</strong> {{ session()->get('errorMessage') }}
                </div>
            </div>
            @endif
            
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white">
                    <div class="mx-auto px-4 ">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    <script src="{{ asset('assets/bsscript.js') }}"></script>
    
    @if(session()->has('alertError'))
    <script>
        alert("{{ session()->get('alertError') }}");
    </script>
    @endif
    @if(session()->has('alertSuccess'))
    <script>
        alert("{{ session()->get('alertSuccess') }}");
    </script>
    @endif

</html>
