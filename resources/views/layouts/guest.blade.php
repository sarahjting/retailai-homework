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
        <nav x-data="{ open: false }" class="navbar navbar-expand-md bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">{{ __("RetailAI Homework") }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <x-nav-link :href="route('login', ['role' => App\Enums\RoleEnum::MERCHANT->value])">
                                {{ __('Merchant login') }}
                            </x-nav-link>
                        </li>
                        <li class="nav-item">
                            <x-nav-link :href="route('login', ['role' => App\Enums\RoleEnum::ADMIN->value])">
                                {{ __('Admin login') }}
                            </x-nav-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6 py-3 px-4">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
