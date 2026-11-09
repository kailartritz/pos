<x-app-layout title="Dashboard">
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-lg font-medium">Your restaurants</h1>
        @if ($restaurants->isNotEmpty())
            <a href="{{ route('restaurants.create') }}" class="rounded-sm bg-[#1b1b18] px-4 py-2 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Create a restaurant</a>
        @endif
    </div>

    @if ($restaurants->isEmpty())
        <section class="rounded-lg bg-white p-8 text-center shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
            <p class="mb-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">You have not created a restaurant yet.</p>
            <a href="{{ route('restaurants.create') }}" class="inline-block rounded-sm bg-[#1b1b18] px-5 py-2.5 text-sm font-medium text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Create a restaurant</a>
        </section>
    @else
        <ul class="divide-y divide-[#e3e3e0] rounded-lg bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:divide-[#3E3E3A] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
            @foreach ($restaurants as $restaurant)
                <li class="flex items-center justify-between gap-4 px-5 py-4">
                    <span>{{ $restaurant->name }}</span>
                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $restaurant->statusLabel() }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</x-app-layout>
