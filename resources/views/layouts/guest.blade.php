<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Renatha Living') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="grid min-h-screen lg:grid-cols-2">
            <div class="hidden flex-col justify-between bg-teal-950 p-12 text-teal-50 lg:flex">
                <p class="flex items-center gap-3 text-2xl font-bold">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-600"><x-icon name="building" class="h-6 w-6" /></span>
                    Renatha Living
                </p>
                <div>
                    <p class="max-w-md text-3xl font-bold leading-snug">Every room, resident and supply in one place.</p>
                    <p class="mt-3 max-w-md text-teal-300">Boarding house management for the owner and the caretaker.</p>
                </div>
                <p class="text-xs text-teal-400">&copy; {{ date('Y') }} Renatha Living</p>
            </div>

            <div class="flex items-center justify-center bg-slate-50 px-6 py-12">
                <div class="w-full max-w-md">
                    <p class="mb-6 text-xl font-bold text-teal-900 lg:hidden">Renatha Living</p>
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">{{ $slot }}</div>
                </div>
            </div>
        </div>
    </body>
</html>
