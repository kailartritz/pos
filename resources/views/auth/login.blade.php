<x-guest-layout title="Log in">
    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('email')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('password')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1" class="rounded-sm border-[#e3e3e0] dark:border-[#3E3E3A]">
            Remember me
        </label>

        <button type="submit" class="rounded-sm bg-[#1b1b18] px-4 py-2 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Log in</button>
    </form>

    <p class="mt-6 flex flex-col gap-2 text-sm">
        <a href="{{ route('password.request') }}" class="underline underline-offset-4">Forgot your password?</a>
        <a href="{{ route('register') }}" class="underline underline-offset-4">Create an account</a>
    </p>
</x-guest-layout>
