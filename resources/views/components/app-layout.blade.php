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
    <body class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] antialiased dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <header class="mx-auto flex w-full max-w-4xl items-center justify-end gap-4 px-6 py-6 text-sm">
            <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ auth()->user()->full_name }}</span>
            @if (auth()->user()->system_admin)
                <a href="{{ route('admin.restaurants.index') }}" class="rounded-sm border border-[#19140035] px-5 py-1.5 dark:border-[#3E3E3A]">Admin</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-sm border border-[#19140035] px-5 py-1.5 dark:border-[#3E3E3A]">Log out</button>
            </form>
        </header>
        <main class="mx-auto w-full max-w-4xl px-6 pb-12">
            {{ $slot }}
        </main>
    </body>
</html>
