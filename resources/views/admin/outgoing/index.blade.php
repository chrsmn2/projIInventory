@extends('layouts.admin')

@section('title', 'Outgoing Items')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">

    <!-- ===================== -->
    <!-- HEADER -->
    <!-- ===================== -->
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl bg-gradient-to-r from-gray-700 to-gray-800">
        <div>
            <h2 class="text-xl font-semibold text-black">Outgoing Item</h2>
            <p class="text-sm text-gray-500">Master data inventory outgoing items</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search item..."
                        class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-blue-200">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Search
                    </button>
                </form>

                <form method="GET" class="flex items-center">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border rounded-lg text-sm">
                        @foreach([5,10,25,50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <a href="{{ route('admin.outgoing.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500 text-white font-semibold text-sm rounded-lg hover:bg-emerald-600 transition ml-auto mt-2 sm:mt-0">
                + Add Outgoing Item
            </a>
        </div>
    </div>
    <!-- ===================== -->
    <!-- TABLE -->
    <!-- ===================== -->
    <div class="overflow-x-auto">
        @if ($outgoing->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Department</th>
                        <!--<th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Status</th>-->
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($outgoing as $index => $outgoingItem)
                        @php $rowspan = $outgoingItem->details->count(); @endphp

                        @foreach ($outgoingItem->details as $detailIndex => $detail)
                        <tr class="hover:bg-orange-50 transition duration-150">

                            {{-- NO --}}
                            @if ($detailIndex === 0)
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 font-semibold text-gray-900">
                                {{ ($outgoing->currentPage() - 1) * $outgoing->perPage() + $index + 1 }}
                            </td>

                            {{-- CODE --}}
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 font-mono font-bold text-gray-900">
                                {{ $outgoingItem->code }}
                            </td>

                            {{-- DATE --}}
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 text-gray-900 font-semibold">
                                {{ $outgoingItem->outgoing_date->format('d-m-Y') }}
                            </td>

                            {{-- ADMIN --}}
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 text-gray-700">
                                {{ $outgoingItem->admin?->name ?? '-' }}
                            </td>

                            {{-- DEPARTMENT --}}
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 font-semibold text-gray-700">
                                {{ $outgoingItem->departement?->departement_name ?? '-' }}
                            </td>
                            @endif

                            <!--{{-- ITEM --}}
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ $detail->item?->item_name ?? '-' }}
                            </td>-->

                            <!--{{-- QTY --}}
                            <td class="px-6 py-4 text-center font-bold text-red-600">
                                {{ $detail->quantity }}
                            </td>-->

                            {{-- STATUS --}}
                            <!--@if ($detailIndex === 0)
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold
                                    @if($outgoingItem->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($outgoingItem->status == 'completed') bg-green-100 text-green-800
                                    @elseif($outgoingItem->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($outgoingItem->status) }}
                                </span>
                            </td>-->

                            {{-- NOTES --}}
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                {{ $outgoingItem->notes ?? '-' }}
                            </td>

                            {{-- ACTION --}}
                            <td rowspan="{{ $rowspan }}" class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.outgoing.show', $outgoingItem->id) }}"
                                       class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        View
                                    </a>

                                    <a href="{{ route('admin.outgoing.edit', $outgoingItem->id) }}"
                                       class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Update
                                    </a>

                                    <form action="{{ route('admin.outgoing.destroy', $outgoingItem->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this outgoing record?')"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-semibold bg-red-600 text-white rounded hover:bg-red-700 transition">
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
                            <td colspan="10" class="text-center py-16 text-gray-500">
                                <p class="text-lg mb-4">No outgoing data available</p>
                                <a href="{{ route('admin.outgoing.create') }}" class="text-gray-700 hover:underline font-semibold">Add new outgoing item</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- ===================== -->
            <!-- PAGINATION -->
            <!-- ===================== -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center rounded-b-xl">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-bold">{{ ($outgoing->currentPage() - 1) * $outgoing->perPage() + 1 }}</span> 
                    to <span class="font-bold">{{ min($outgoing->currentPage() * $outgoing->perPage(), $outgoing->total()) }}</span> 
                    of <span class="font-bold">{{ $outgoing->total() }}</span> outgoing records
                </p>
                
                <div>
                    {{ $outgoing->links('pagination::tailwind') }}
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg mb-4">No outgoing data available</p>
                <a href="{{ route('admin.outgoing.create') }}" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                    + Add Outgoing Item
                </a>
            </div>
        @endif
    </div>

</div>

@endsection

