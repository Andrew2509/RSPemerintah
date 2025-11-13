<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'RS Pemerintah XYZ' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/dashboard-custom.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/patient-list.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/patient-detail.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/patient-edit.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/patient-registration.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/triage-form.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/qr-scanner.css') }}" as="style">
        <link rel="stylesheet" href="{{ asset('css/header-sidebar.css') }}" as="style">
        @stack('styles')
    </head>
    @livewireStyles
    <body class="app-body">
        @include('components.header')
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="app-main-content">
            {{ $slot }}
        </main>

        <!-- Help Button -->
        <div class="help-button-wrapper">
            <button class="help-button">
                <svg class="help-button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </div>
    @livewireScripts
    </body>
</html>
