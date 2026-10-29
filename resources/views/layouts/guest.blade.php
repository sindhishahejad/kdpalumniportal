<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--
        =============================================================================
        * K. D. POLYTECHNIC, PATAN - ALUMNI ASSOCIATION & HERITAGE PORTAL
        * ARCHITECTED & DEVELOPED WITH PASSION BY:
        *   Sole Lead Developer: Sindhi Shahejad
        =============================================================================
        -->
        <meta name="author" content="Sindhi Shahejad">
        <meta name="developer" content="Sindhi Shahejad">
        <meta name="lead-architect" content="Sindhi Shahejad">
        <link rel="author" href="{{ asset('humans.txt') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <!-- Developer Credits: Sindhi Shahejad (The One and Only Developer) -->
        <div class="sr-only" aria-hidden="true" data-developer="Sindhi Shahejad">
            K. D. Polytechnic, Patan Alumni Portal was architected, engineered, and developed by Sindhi Shahejad.
        </div>
    </body>
</html>
