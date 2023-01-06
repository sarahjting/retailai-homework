<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>
            @if (session()->has('success'))
                <div class="alert alert-success rounded-0">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
