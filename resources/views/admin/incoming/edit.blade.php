@extends('layouts.admin')

@section('title', 'Edit Incoming Item')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow border">

    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Update Incoming Item</h2>
    </div>

    {{-- GLOBAL ERROR --}}
    @if ($errors->any())
        <div class="m-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <strong>Error validation:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.incoming.update', $incoming->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <!-- DATE -->
        <div class="mb-4">
            <label class="font-bold">Incoming Date *</label>
            <input type="date" name="incoming_date"
                   value="{{ old('incoming_date', $incoming->incoming_date) }}"
                   class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- SUPPLIER -->
        <div class="mb-6">
            <label class="font-bold">Supplier *</label>
            <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg">
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}"
                        {{ old('supplier_id', $incoming->supplier_id) == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->supplier_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- ITEMS -->
        <label class="font-bold block mb-2">Items *</label>
        <table class="w-full border rounded-lg" id="itemsTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Item</th>
                    <th class="p-2 text-center">Qty</th>
                    <th class="p-2 text-center">Unit</th>
                    <th class="p-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="itemsTableBody">
                @foreach ($incoming->details as $i => $detail)
                <tr class="item-row">
                    <td class="p-2">
                        <select name="items[{{ $i }}][item_id]" class="item-select w-full border rounded px-2 py-1">
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}"
                                    data-unit="{{ $item->unit?->name ?? 'N/A' }}"
                                    data-unit-id="{{ $item->unit?->id ?? '' }}"
                                    {{ $detail->item_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="number" name="items[{{ $i }}][quantity]"
                               value="{{ $detail->quantity }}"
                               class="w-full border rounded px-2 py-1"
                               min="1">
                    </td>
                    <td class="p-2">
                        <input type="text" class="unit-display w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                        <input type="hidden" name="items[{{ $i }}][unit_id]" class="unit-id">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="btn-remove-item px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                            ✕
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ADD NEW ITEM BUTTON -->
        <button type="button" id="addItemBtn" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700">
            + Add Item
        </button>

        <!-- NOTES -->
        <div class="mb-6 mt-6">
            <label class="font-bold">Notes</label>
            <textarea name="notes" class="w-full px-4 py-2 border rounded-lg" rows="3">{{ old('notes', $incoming->notes) }}</textarea>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">
                Update
            </button>
            <a href="{{ route('admin.incoming.index') }}"
               class="px-6 py-2 bg-gray-200 rounded-lg font-bold hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    const items = @json($items);
    let itemCount = {{ count($incoming->details) }};
    const selectedItems = @json($incoming->details->pluck('item_id')->toArray());

    document.addEventListener('DOMContentLoaded', function () {
        const addItemBtn = document.getElementById('addItemBtn');
        const tableBody = document.getElementById('itemsTableBody');

        function updateUnitDisplay(selectElement) {
            const row = selectElement.closest('tr');
            const unitDisplay = row.querySelector('.unit-display');
            const unitIdInput = row.querySelector('.unit-id');

            const selectedOption = selectElement.options[selectElement.selectedIndex];

            if (selectedOption && selectedOption.value) {
                unitDisplay.value = selectedOption.getAttribute('data-unit') || '';
                unitIdInput.value = selectedOption.getAttribute('data-unit-id') || '';
            } else {
                unitDisplay.value = '';
                unitIdInput.value = '';
            }
        }

        function generateItemOptions() {
            return items.map(item => {
                const unitName = item.unit?.name || '';
                const unitId = item.unit?.id || '';
                const isSelected = selectedItems.includes(item.id) ? 'disabled' : '';
                return `
                    <option value="${item.id}" ${isSelected} data-unit="${unitName}" data-unit-id="${unitId}">
                        ${item.item_name}
                    </option>
                `;
            }).join('');
        }

        addItemBtn.addEventListener('click', function () {
            const newRow = document.createElement('tr');
            newRow.classList.add('item-row');

            newRow.innerHTML = `
                <td class="p-2">
                    <select name="items[${itemCount}][item_id]" class="item-select w-full border rounded px-2 py-1" required>
                        <option value="">Select Item</option>
                        ${generateItemOptions()}
                    </select>
                </td>
                <td class="p-2">
                    <input type="number" name="items[${itemCount}][quantity]" 
                           class="w-full border rounded px-2 py-1" 
                           min="1" value="1" required>
                </td>
                <td class="p-2">
                    <input type="text" class="unit-display w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                    <input type="hidden" name="items[${itemCount}][unit_id]" class="unit-id">
                </td>
                <td class="p-2 text-center">
                    <button type="button" class="btn-remove-item px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                        ✕
                    </button>
                </td>
            `;

            tableBody.appendChild(newRow);
            itemCount++;
            attachRemoveListeners();
            updateUnitDisplay(newRow.querySelector('.item-select'));
        });

        tableBody.addEventListener('change', function (e) {
            if (e.target.matches('.item-select')) {
                updateUnitDisplay(e.target);
            }
        });

        function attachRemoveListeners() {
            document.querySelectorAll('.btn-remove-item').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.closest('tr').remove();
                });
            });
        }

        attachRemoveListeners();
    });

    @if ($errors->any())
        window.scrollTo({ top: 0, behavior: 'smooth' });
    @endif
</script>

@if ($errors->any())
<script>
    window.scrollTo({ top: 0, behavior: 'smooth' });
</script>
@endif
@endsection

