<x-guest-layout title="Choose a new password">
    <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-4">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="mb-1 block text-sm">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus autocomplete="username" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('email')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm">New password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('password')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
        </div>

        <button type="submit" class="rounded-sm bg-[#1b1b18] px-4 py-2 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Save password</button>
    </form>
</x-guest-layout>
