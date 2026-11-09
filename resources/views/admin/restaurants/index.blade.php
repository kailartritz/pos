<x-app-layout title="Restaurants">
    <h1 class="mb-6 text-lg font-medium">Restaurants</h1>

    <form method="GET" action="{{ route('admin.restaurants.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <input name="name" value="{{ request('name') }}" placeholder="Name" class="rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
        <input name="city" value="{{ request('city') }}" placeholder="City" class="rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
        <select name="state" class="rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            <option value="">State</option>
            @foreach ($states as $code => $label)
                <option value="{{ $code }}" @selected(request('state') === $code)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
            <option value="">Status</option>
            @foreach ($statuses as $code => $label)
                <option value="{{ $code }}" @selected(request('status') === $code)>{{ $label }}</option>
            @endforeach
        </select>
        <input name="created_by_id" value="{{ request('created_by_id') }}" placeholder="Created by ID" class="rounded-sm border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
        <button type="submit" class="rounded-sm border border-[#19140035] px-4 py-2 text-sm dark:border-[#3E3E3A] lg:col-span-5">Search</button>
    </form>

    <div class="overflow-x-auto rounded-lg bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">City</th>
                    <th class="px-4 py-3 font-medium">State</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Created by</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($restaurants as $restaurant)
                    <tr class="border-b border-[#e3e3e0] last:border-0 dark:border-[#3E3E3A]">
                        <td class="px-4 py-3">{{ $restaurant->name }}</td>
                        <td class="px-4 py-3">{{ $restaurant->city }}</td>
                        <td class="px-4 py-3">{{ $restaurant->state }}</td>
                        <td class="px-4 py-3">{{ $restaurant->statusLabel() }}</td>
                        <td class="px-4 py-3">{{ $restaurant->createdBy->full_name }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($restaurant->status === array_key_first($statuses))
                                <form method="POST" action="{{ route('admin.restaurants.approve', $restaurant) }}">
                                    @csrf
                                    <button type="submit" class="rounded-sm bg-[#1b1b18] px-3 py-1.5 text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]">Approve</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
