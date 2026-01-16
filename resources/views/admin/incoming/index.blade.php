@extends('layouts.admin')

@section('title', 'Incoming Items')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Incoming Items</h2>
            <p class="text-sm text-indigo-100">
                Record of incoming items and automatic stock update
            </p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

            <!-- SEARCH -->
            <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Search..."
                       class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Search
                </button>
            </form>

            <!-- SHOW -->
            <form method="GET">
                <input type="hidden" name="search" value="{{ $search }}">
                <select name="per_page" onchange="this.form.submit()"
                        class="px-3 py-2 border rounded-lg text-sm">
                    @foreach([5,10,25,50] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                            Show {{ $size }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- ADD -->
            <a href="{{ route('admin.incoming.create') }}"
               class="px-4 py-2 bg-emerald-500 text-black font-semibold rounded-lg">
                + Add Incoming
            </a>
        </div>
    </div>

    <!-- TABLE -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm border border-gray-200 rounded-lg">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 w-10">No</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Admin</th>
                    <th class="px-4 py-3">Suppliers</th>
                    <th class="px-4 py-3">Item</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3 text-center w-40">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
            @forelse ($incoming as $index => $incomingItem)

                @php $rowspan = $incomingItem->details->count(); @endphp

                @foreach ($incomingItem->details as $detailIndex => $detail)
                <tr class="hover:bg-gray-50">

                    {{-- NO --}}
                    @if ($detailIndex === 0)
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3 text-center font-semibold">
                        {{ $incoming->firstItem() + $index }}
                    </td>

                    {{-- DATE --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($incomingItem->incoming_date)->format('d-m-Y') }}
                    </td>

                    {{-- ADMIN --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ $incomingItem->admin->name }}
                    </td>

                    {{-- Suppliers --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ $incomingItem->supplier }}
                    </td>
                    @endif

                    {{-- ITEM --}}
                    <td class="px-4 py-2 font-semibold">
                        {{ $detail->item->item_name }}
                    </td>

                    {{-- QTY --}}
                    <td class="px-4 py-2 text-center font-bold">
                        {{ $detail->quantity }}
                    </td>

                    {{-- DESCRIPTION --}}
                    @if ($detailIndex === 0)
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ $detail->note ?? '-' }}
                    </td>

                    {{-- ACTION --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2 flex-wrap">

                            <a href="{{ route('admin.incoming.edit', $incomingItem->id) }}"
                               class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded">
                                Update
                            </a>

                            <form action="{{ route('admin.incoming.destroy', $incomingItem->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1.5 text-xs font-semibold bg-red-600 text-white rounded">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>
                    @endif

                </tr>
                @endforeach

            @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 italic">
                        No incoming data found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 pb-6">

        <div class="text-sm text-gray-600">
            Showing
            <strong>{{ $incoming->firstItem() }}</strong>
            –
            <strong>{{ $incoming->lastItem() }}</strong>
            of
            <strong>{{ $incoming->total() }}</strong>
            records
        </div>

        <div class="flex gap-2">
            <a href="{{ $incoming->previousPageUrl() ?? '#' }}"
               class="px-4 py-2 rounded-lg text-sm
               {{ $incoming->onFirstPage()
                    ? 'bg-gray-200 text-gray-400 pointer-events-none'
                    : 'bg-white border hover:bg-gray-100' }}">
                <<
            </a>

            <a href="{{ $incoming->nextPageUrl() ?? '#' }}"
               class="px-4 py-2 rounded-lg text-sm
               {{ $incoming->hasMorePages()
                    ? 'bg-white border hover:bg-gray-100'
                    : 'bg-gray-200 text-gray-400 pointer-events-none' }}">
                >>
            </a>
        </div>
    </div>

</div>
@endsection
