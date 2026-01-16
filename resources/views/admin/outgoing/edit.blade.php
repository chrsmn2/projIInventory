@extends('layouts.admin')

@section('title', 'Edit Outgoing Item')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-orange-600 to-orange-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Edit Outgoing Item</h2>
            <p class="text-sm text-orange-100">
                Update outgoing item information
            </p>
        </div>

        <a href="{{ route('admin.outgoing.show', $outgoing->id) }}"
           class="inline-flex items-center gap-2
                  px-4 py-2 bg-gray-200 text-gray-700
                  rounded-lg font-semibold text-sm
                  hover:bg-gray-300 transition">
            &larr; Back
        </a>

    </div>

    <!-- FORM -->
    <div class="p-6">
        <form action="{{ route('admin.outgoing.update', $outgoing->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- GENERAL INFO -->
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input type="date" name="outgoing_date" value="{{ old('outgoing_date', $outgoing->outgoing_date) }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-orange-200"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Destination</label>
                    <input type="text" name="destination" value="{{ old('destination', $outgoing->destination) }}"
                           placeholder="Department / Location"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-orange-200"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm text-black"
                            required>
                        <option value="pending" {{ $outgoing->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $outgoing->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $outgoing->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- NOTES -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3"
                          placeholder="Optional notes"
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-orange-200">{{ old('notes', $outgoing->notes) }}</textarea>
            </div>

            <!-- ITEMS TABLE -->
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>

            <div class="overflow-x-auto border border-gray-200 rounded-lg mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-center w-10">No</th>
                            <th class="px-4 py-3">Item Name</th>
                            <th class="px-4 py-3 text-center">Item Code</th>
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
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No items found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- BUTTONS -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700">
                    Update
                </button>
                <a href="{{ route('admin.outgoing.show', $outgoing->id) }}"
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

@endsection
