@extends('layouts.admin')

@section('title', 'Outgoing Item Details')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-black">Outgoing Item Details</h2>
            <p class="text-sm text-gray-500">
                View detailed information about this outgoing transaction
            </p>
        </div>

        <div class="flex gap-2">
           <!-- @if($outgoing->status === 'pending')-->
            <a href="{{ route('admin.outgoing.edit', $outgoing->id) }}"
               class="inline-flex items-center gap-2
                      px-4 py-2 bg-yellow-500 text-white
                      rounded-lg font-semibold text-sm
                      hover:bg-yellow-600 transition">
                ✏️ Edit
            </a>
            @endif

            <a href="{{ route('admin.outgoing.index') }}"
               class="inline-flex items-center gap-2
                      px-4 py-2 bg-gray-200 text-gray-700
                      rounded-lg font-semibold text-sm
                      hover:bg-gray-300 transition">
                &larr; Back
            </a>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="p-6">

        <!-- INFO GRID -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">

            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Transaction Code</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->code }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Outgoing Date</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->outgoing_date->format('d/m/Y') }}
                </p>
            </div>

            <!--<div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Status</p>
                <p>
                    <span class="px-4 py-1 rounded-full text-sm font-bold
                        @if($outgoing->status == 'pending') bg-yellow-100 text-yellow-700
                        @elseif($outgoing->status == 'completed') bg-green-100 text-green-700
                        @elseif($outgoing->status == 'cancelled') bg-red-100 text-red-700
                        @else bg-gray-200 text-gray-700 @endif">
                        {{ ucfirst($outgoing->status) }}
                    </span>
                </p>
            </div>-->

            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Department</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->departement?->departement_name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Admin</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->admin->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Supervisor</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->supervisor->name ?? '-' }}
                </p>
            </div>

            @if($outgoing->notes)
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Notes</p>
                <p class="text-gray-900">{{ $outgoing->notes }}</p>
            </div>
            @endif

        </div>

        <!-- ITEMS TABLE -->
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center w-10">No</th>
                        <th class="px-4 py-3">Item Name</th>
                        <th class="px-4 py-3 text-center">Item Code</th>
                        <th class="px-4 py-3 text-center">Unit</th>
                        <th class="px-4 py-3 text-center">Condition</th>
                        <th class="px-4 py-3 text-center">Quantity</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($outgoing->details as $index => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-900">
                            {{ $detail->item->item_name }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded">
                                {{ $detail->item->item_code }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold">
                            {{ $detail->unit ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                @if($detail->condition == 'normal') bg-green-100 text-green-700
                                @elseif($detail->condition == 'damaged') bg-red-100 text-red-700
                                @else bg-gray-200 text-gray-700 @endif">
                                {{ ucfirst($detail->condition) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold">{{ $detail->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No items found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection

