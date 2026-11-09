<x-app-layout title="Create a restaurant">
    <h1 class="mb-6 text-lg font-medium">Create a restaurant</h1>

    <form method="POST" action="{{ route('restaurants.store') }}" class="flex max-w-[335px] flex-col gap-4">
        @csrf

        @foreach ([
            'name' => 'Name',
            'address' => 'Address',
            'city' => 'City',
            'zip' => 'ZIP',
            'logo_url' => 'Logo URL',
            'website_url' => 'Website URL',
        ] as $field => $label)
            <div>
                <label for="{{ $field }}" class="mb-1 block text-sm">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="{{ str_ends_with($field, '_url') ? 'url' : 'text' }}" value="{{ old($field) }}" @required(! str_ends_with($field, '_url')) class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
                @error($field)
                    <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <div>
            <label for="state" class="mb-1 block text-sm">State</label>
            <select id="state" name="state" required class="w-full rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
                <option value="">Select a state</option>
                @foreach ($states as $code => $label)
                    <option value="{{ $code }}" @selected(old('state') === $code)>{{ $label }}</option>
                @endforeach
            </select>
            @error('state')
                <p class="mt-1 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-sm bg-[#1b1b18] px-4 py-2 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Submit restaurant</button>
    </form>
</x-app-layout>
