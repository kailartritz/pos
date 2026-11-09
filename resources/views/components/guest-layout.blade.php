@props(['title'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }} — {{ config('app.name') }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18] antialiased dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <main class="mx-auto flex min-h-screen w-full max-w-[335px] flex-col justify-center px-6 py-12">
            <h1 class="mb-6 text-lg font-medium">{{ $title }}</h1>

            @if (session('status'))
                <p class="mb-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ session('status') }}</p>
            @endif

            {{ $slot }}
        </main>
    </body>
</html>
