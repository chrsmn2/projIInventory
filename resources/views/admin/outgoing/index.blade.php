@extends('layouts.admin')

@section('title', 'Outgoing Items')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-orange-600 to-orange-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Outgoing Items</h2>
            <p class="text-sm text-orange-100">
                Record of outgoing items and automatic stock deduction
            </p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

            <!-- SEARCH -->
            <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Search..."
                       class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Search
                </button>
            </form>

            <!-- SHOW -->
            <form method="GET">
                <input type="hidden" name="search" value="{{ $search ?? '' }}">
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
            <a href="{{ route('admin.outgoing.create') }}"
               class="px-4 py-2 bg-emerald-500 text-black font-semibold rounded-lg">
                + Add Outgoing
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
                    <th class="px-4 py-3">Destination</th>
                    <th class="px-4 py-3">Item</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Notes</th>
                    <th class="px-4 py-3 text-center w-40">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
            @forelse ($outgoing as $index => $outgoingItem)

                @php $rowspan = $outgoingItem->details->count(); @endphp

                @foreach ($outgoingItem->details as $detailIndex => $detail)
                <tr class="hover:bg-gray-50">

                    {{-- NO --}}
                    @if ($detailIndex === 0)
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3 text-center font-semibold">
                        {{ $outgoing->firstItem() + $index }}
                    </td>

                    {{-- DATE --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($outgoingItem->outgoing_date)->format('d/m/Y') }}
                    </td>

                    {{-- ADMIN --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ $outgoingItem->admin->name ?? '-' }}
                    </td>

                    {{-- DESTINATION --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        {{ $outgoingItem->destination }}
                    </td>
                    @endif

                    {{-- ITEM NAME --}}
                    <td class="px-4 py-3">{{ $detail->item->item_name }}</td>

                    {{-- QUANTITY --}}
                    <td class="px-4 py-3 text-center">{{ $detail->quantity }}</td>

                    {{-- STATUS (only show once per row) --}}
                    @if ($detailIndex === 0)
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($outgoingItem->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($outgoingItem->status == 'completed') bg-green-100 text-green-700
                            @elseif($outgoingItem->status == 'cancelled') bg-red-100 text-red-700
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ ucfirst($outgoingItem->status) }}
                        </span>
                    </td>

                    {{-- NOTES --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3 truncate max-w-xs">
                        {{ $outgoingItem->notes ?? '-' }}
                    </td>

                    {{-- ACTION --}}
                    <td rowspan="{{ $rowspan }}" class="px-4 py-3 text-center space-x-2">
                        <a href="{{ route('admin.outgoing.show', $outgoingItem->id) }}"
                           class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                            View
                        </a>

                        @if($outgoingItem->status === 'pending')
                        <a href="{{ route('admin.outgoing.edit', $outgoingItem->id) }}"
                           class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">
                            Edit
                        </a>

                        <form action="{{ route('admin.outgoing.destroy', $outgoingItem->id) }}"
                              method="POST" style="display:inline;"
                              onclick="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                        @endif
                    </td>
                    @endif

                </tr>
                @endforeach

            @empty
            <tr>
                <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                    No outgoing items found
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t">
        {{ $outgoing->links('pagination::tailwind') }}
    </div>

</div>

@endsection
