<x-guest-layout title="Reset your password">
    <p class="mb-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">Enter your email and we will send you a link to choose a new password.</p>

    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('email')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-sm bg-[#1b1b18] px-4 py-2 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Email reset link</button>
    </form>

    <p class="mt-6 text-sm">
        <a href="{{ route('login') }}" class="underline underline-offset-4">Back to log in</a>
    </p>
</x-guest-layout>
