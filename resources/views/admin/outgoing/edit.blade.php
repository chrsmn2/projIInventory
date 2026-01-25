@extends('layouts.admin')

@section('title', 'Edit Outgoing Item')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow border border-gray-200">

    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl flex justify-between items-center">
        <h2 class="text-xl font-bold text-black">Edit Outgoing Item</h2>
        <a href="{{ route('admin.outgoing.index') }}" class="text-gray-300 hover:text-white text-sm">&larr; Back to List</a>
    </div>

    @if ($errors->any())
        <div class="p-4 m-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Validation Errors:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.outgoing.update', $outgoing->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Transaction Code</label>
                    <input type="text" value="{{ $outgoing->code }}" disabled
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 font-mono font-bold">
                </div>

                <div>
                    <label for="outgoing_date" class="block text-sm font-bold text-gray-700 mb-2">Outgoing Date <span class="text-red-500">*</span></label>
                    <input type="date" id="outgoing_date" name="outgoing_date" value="{{ old('outgoing_date', $outgoing->outgoing_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('outgoing_date') border-red-500 @enderror" required>
                    @error('outgoing_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="departement_id" class="block text-sm font-bold text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                <select id="departement_id" name="departement_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('departement_id') border-red-500 @enderror" required>
                    <option value="" disabled>-- Select Department --</option>
                    @foreach($departements as $dept)
                        <option value="{{ $dept->id }}" {{ old('departement_id', $outgoing->departement_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->departement_name }}
                        </option>
                    @endforeach
                </select>
                @error('departement_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Items <span class="text-red-500">*</span></label>
                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-bold text-gray-700">Item Name</th>
                                <th class="px-4 py-2 text-left font-bold text-gray-700">Condition</th>
                                <th class="px-4 py-2 text-center font-bold text-gray-700">Quantity</th>
                                <th class="px-4 py-2 text-left font-bold text-gray-700">Unit</th>
                                <th class="px-4 py-2 text-center font-bold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-container" class="divide-y">
                            @foreach($outgoing->details as $index => $detail)
                                <tr class="item-row hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <select name="items[{{ $index }}][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                                            <option value="" disabled>-- Select Item --</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" data-unit="{{ $item->unit?->name ?? 'N/A' }}" {{ $detail->item_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->item_name }} (Stock: {{ $item->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <select name="items[{{ $index }}][condition]" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                                            <option value="normal" {{ $detail->condition == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="damaged" {{ $detail->condition == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $detail->quantity }}" min="1" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none text-center" required>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" class="unit-display w-full px-3 py-2 border rounded-lg text-sm bg-gray-50 text-gray-500" readonly placeholder="Unit">
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" onclick="removeItem(this)" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="addItem()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-bold">+ Add Item</button>
            </div>

            <div>
                <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Enter notes...">{{ old('notes', $outgoing->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex gap-4 border-t pt-6">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                ✓ Update Outgoing
            </button>
            <a href="{{ route('admin.outgoing.show', $outgoing->id) }}" class="px-6 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition">
                ✕ Cancel
            </a>
        </div>
    </form>
</div>

<script>
let itemCount = {{ $outgoing->details->count() }};

function updateUnitDisplay(selectElement) {
    const row = selectElement.closest('tr');
    const unitDisplay = row.querySelector('.unit-display');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        const unit = selectedOption.getAttribute('data-unit');
        unitDisplay.value = unit;
    } else {
        unitDisplay.value = '';
    }
}

function addItem() {
    const container = document.getElementById('items-container');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row hover:bg-gray-50';
    newRow.innerHTML = `
        <td class="px-4 py-2">
            <select name="items[${itemCount}][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                <option value="" disabled selected>-- Select Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-unit="{{ $item->unit?->name ?? 'N/A' }}">
                        {{ $item->item_name }} (Stock: {{ $item->stock }})
                    </option>
                @endforeach
            </select>
        </td>
        <td class="px-4 py-2">
            <select name="items[${itemCount}][condition]" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                <option value="normal">Normal</option>
                <option value="damaged">Damaged</option>
            </select>
        </td>
        <td class="px-4 py-2">
            <input type="number" name="items[${itemCount}][quantity]" min="1" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none text-center" required>
        </td>
        <td class="px-4 py-2">
            <input type="text" class="unit-display w-full px-3 py-2 border rounded-lg text-sm bg-gray-50 text-gray-500" readonly placeholder="Unit">
        </td>
        <td class="px-4 py-2 text-center">
            <button type="button" onclick="removeItem(this)" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">Remove</button>
        </td>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('select.item-select').addEventListener('change', function() {
        updateUnitDisplay(this);
    });
    
    itemCount++;
}

function removeItem(button) {
    if (document.querySelectorAll('.item-row').length > 1) {
        button.closest('tr').remove();
    } else {
        alert('At least one item is required.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select.item-select').forEach(select => {
        updateUnitDisplay(select);
        select.addEventListener('change', function() {
            updateUnitDisplay(this);
        });
    });
});
</script>
@endsection