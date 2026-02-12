@extends('layouts.admin')

@section('title', 'View Outgoing Item')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Outgoing Item Details</h2>
    </div>

    <div class="p-6 space-y-6">

        <!-- INFO SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Transaction Code</label>
                <p class="text-lg font-semibold text-blue-600 font-mono">
                    {{ $outgoing->code ?? '-' }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Outgoing Date</label>
                <p class="text-lg font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($outgoing->outgoing_date)->format('d-m-Y') }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Department</label>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->departement?->departement_name ?? '-' }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Admin</label>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $outgoing->admin?->name ?? '-' }}
                </p>
            </div>

            <!--<div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Supervisor</label>
                <p class="text-lg font-semibold text-gray-700">
                    {{ $outgoing->supervisor?->name ?? '-' }}
                </p>
            </div>-->

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Total Items</label>
                <p class="text-lg font-semibold text-emerald-600">
                    {{ $outgoing->details->sum('quantity') }} units
                </p>
            </div>
        </div>

        <!-- NOTES -->
        @if($outgoing->notes)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
            <p class="text-gray-700">{{ $outgoing->notes }}</p>
        </div>
        @endif

        <!-- ITEMS TABLE -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-4">Items Detail</h3>
            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-700">Item Name</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700">Category</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-700">Quantity</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700">Units</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700">Condition</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($outgoing->details as $index => $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">
                                {{ $detail->item?->item_name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium">
                                    {{ $detail->item?->category?->category_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-emerald-600">
                                {{ $detail->quantity }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">
                                    {{ $detail->item?->unit?->unit_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded text-xs font-bold
                                    @if($detail->condition == 'normal') bg-green-100 text-green-700
                                    @elseif($detail->condition == 'damaged') bg-red-100 text-red-700
                                    @else bg-gray-200 text-gray-700 @endif">
                                    {{ ucfirst($detail->condition) }}
                                </span>
                            </td> 
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No items available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BUTTONS -->
        <div class="flex gap-4 border-t pt-6">
            <a href="{{ route('admin.outgoing.edit', $outgoing->id) }}" 
               class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">
                Update
            </a>
            <a href="{{ route('admin.outgoing.index') }}" 
               class="px-6 py-2 bg-gray-200 rounded-lg font-bold hover:bg-gray-300">
                Back
            </a>
        </div>

    </div>

</div>
@endsection

