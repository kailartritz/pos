<x-guest-layout title="Create an account">
    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label for="first_name" class="mb-1 block text-sm">First name</label>
            <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('first_name')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="last_name" class="mb-1 block text-sm">Last name</label>
            <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required autocomplete="family-name" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('last_name')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="date_of_birth" class="mb-1 block text-sm">Date of birth</label>
            <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" required autocomplete="bday" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('date_of_birth')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="state" class="mb-1 block text-sm">State</label>
            <select id="state" name="state" required autocomplete="address-level1" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
                <option value="">Select a state</option>
                @foreach ($states as $code => $label)
                    <option value="{{ $code }}" @selected(old('state') === $code)>{{ $label }}</option>
                @endforeach
            </select>
            @error('state')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('email')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            @error('password')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
        </div>

        <button type="submit" class="rounded-sm bg-[#1b1b18] px-4 py-2 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Create account</button>
    </form>

    <p class="mt-6 text-sm">
        <a href="{{ route('login') }}" class="underline underline-offset-4">Already have an account? Log in</a>
    </p>
</x-guest-layout>
