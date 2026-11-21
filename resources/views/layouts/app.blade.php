<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Rooms, residents, supplies and rent for Renatha Living.">
        <title>{{ config('app.name', 'Renatha Living') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ open: false }" @keydown.escape.window="open = false">
        <a href="#content" class="visually-hidden skip">Skip to content</a>

        <div class="shell">
            @include('layouts.sidebar')

            <div class="scrim" x-show="open" x-cloak x-transition.opacity @click="open = false" aria-hidden="true"></div>

            <div class="main">
                <header class="topbar">
                    <button type="button" class="menu-btn" @click="open = true" aria-label="Open navigation">
                        <x-icon name="bars" class="" />
                    </button>
                    <div class="topbar-title">{{ $header ?? config('app.name') }}</div>
                    <p class="topbar-date">{{ now()->format('l, j F Y') }}</p>
                </header>

                <main id="content" class="page">
                    @if (session('status'))
                        <div role="status" class="flash"><x-icon name="check" class="" /> {{ session('status') }}</div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
