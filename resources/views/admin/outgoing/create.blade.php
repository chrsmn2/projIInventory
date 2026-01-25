@extends('layouts.admin')

@section('title', 'Add Outgoing Items')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-black">Add Outgoing Items</h2>
            <p class="text-sm text-gray-500">
                Add new outgoing items and automatically deduct stock
            </p>
        </div>

        <a href="{{ route('admin.outgoing.index') }}"
           class="inline-flex items-center gap-2
                  px-4 py-2 bg-gray-200 text-gray-700
                  rounded-lg font-semibold text-sm
                  hover:bg-gray-300 transition">
            &larr; Back
        </a>

    </div>

    <!-- FORM -->
    <div class="p-6">
        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Error:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.outgoing.store') }}" method="POST" id="outgoingForm" onsubmit="return validateForm()">
            @csrf

            <!-- GENERAL INFO -->
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Transaction Code</label>
                    <input type="text" value="Auto-Generated" disabled
                           class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 text-gray-600 font-mono font-bold">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="outgoing_date" value="{{ old('outgoing_date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-orange-200 @error('outgoing_date') border-red-500 @enderror"
                           required>
                    @error('outgoing_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Department <span class="text-red-500">*</span></label>
                    <select name="departement_id" id="departement-select" 
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-orange-200 @error('departement_id') border-red-500 @enderror text-black"
                           required>
                        <option value="">Select Department</option>
                        @foreach ($departements as $dept)
                            <option value="{{ $dept->id }}" {{ (string)old('departement_id') == (string)$dept->id ? 'selected' : '' }}>
                                {{ $dept->departement_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('departement_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Notes</label>
                <input type="text" name="notes" value="{{ old('notes') }}"
                       placeholder="Optional notes"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-orange-200">
            </div>

            <!-- VALIDATION ALERT -->
            <div id="validation-alert" class="mb-4 p-3 bg-yellow-50 border border-yellow-300 text-yellow-700 rounded hidden">
                <strong>⚠️ Stock Warning:</strong>
                <div id="alert-content"></div>
            </div>

            <!-- ITEMS TABLE -->
            <div class="mt-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">Items <span class="text-red-500">*</span></label>
                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2 w-10 text-center">#</th>
                                <th class="px-4 py-2">Item</th>
                                <th class="px-4 py-2 w-20 text-center">Available</th>
                                <th class="px-4 py-2 w-20 text-center">Unit</th>
                                <th class="px-4 py-2 w-28 text-center">Qty Request</th>
                                <th class="px-4 py-2">Condition</th>
                                <th class="px-4 py-2 w-24 text-center">Status</th>
                                <th class="px-4 py-2 w-16 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body">
                            <tr class="outgoing-row">
                                <td class="px-4 py-2 text-center">1</td>
                                <td class="px-4 py-2">
                                    <select name="items[0][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black"
                                            data-item-index="0" onchange="updateStockInfo(this)" required>
                                        <option value="">Select Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-unit="{{ $item->unit?->name ?? 'N/A' }}">
                                                {{ $item->item_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-4 py-2 text-center font-semibold">
                                    <span class="stock-available" data-item-index="0">-</span>
                                </td>

                                <td class="px-4 py-2 text-center font-semibold">
                                    <span class="unit-display" data-item-index="0">-</span>
                                </td>

                                <td class="px-4 py-2">
                                    <input type="number" name="items[0][quantity]" min="1"
                                           class="quantity-input w-full px-3 py-2 border rounded-lg text-sm text-center"
                                           data-item-index="0" onchange="updateStockInfo(this)" required>
                                </td>

                                <td class="px-4 py-2">
                                    <select name="items[0][condition]" class="w-full px-3 py-2 border rounded-lg text-sm text-black" required>
                                        <option value="normal">Normal</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </td>

                                <td class="px-4 py-2 text-center">
                                    <span class="status-badge px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-700"
                                          data-item-index="0">-</span>
                                </td>

                                <td class="px-4 py-2 text-center">
                                    <button type="button" class="remove-row text-red-600 hover:text-red-800 font-semibold"
                                            onclick="removeRow(this)">✕</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" id="add-row"
                        class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                    + Add Item
                </button>
            </div>

            <!-- BUTTONS -->
            <div class="mt-6 flex gap-3">
                <button type="submit" id="submit-btn"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition">
                    Save
                </button>
                <a href="{{ route('admin.outgoing.index') }}"
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

<script>
let rowCount = 1;

const itemStocks = {
    @foreach ($items as $item)
        {{ $item->id }}: { stock: {{ $item->stock }}, unit: "{{ $item->unit?->name ?? 'N/A' }}" },
    @endforeach
};

function getSelectedItems() {
    const selectedItems = new Set();
    document.querySelectorAll('.item-select').forEach((select) => {
        if (select.value) {
            selectedItems.add(select.value);
        }
    });
    return selectedItems;
}

function updateItemSelectOptions() {
    const selectedItems = getSelectedItems();
    document.querySelectorAll('.item-select').forEach((select) => {
        const currentValue = select.value;
        
        select.querySelectorAll('option').forEach((option) => {
            if (option.value === '') {
                option.disabled = false;
            } else if (option.value === currentValue) {
                option.disabled = false;
            } else if (selectedItems.has(option.value)) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
}

document.getElementById('add-row').addEventListener('click', function() {
    rowCount++;
    const tbody = document.getElementById('items-table-body');
    const newRow = document.createElement('tr');
    newRow.className = 'outgoing-row';
    newRow.innerHTML = `
        <td class="px-4 py-2 text-center">${rowCount}</td>
        <td class="px-4 py-2">
            <select name="items[${rowCount - 1}][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black"
                    data-item-index="${rowCount - 1}" onchange="updateStockInfo(this); updateItemSelectOptions()" required>
                <option value="">Select Item</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-unit="{{ $item->unit?->name ?? 'N/A' }}">{{ $item->item_name }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-4 py-2 text-center font-semibold">
            <span class="stock-available" data-item-index="${rowCount - 1}">-</span>
        </td>
        <td class="px-4 py-2 text-center font-semibold">
            <span class="unit-display" data-item-index="${rowCount - 1}">-</span>
        </td>
        <td class="px-4 py-2">
            <input type="number" name="items[${rowCount - 1}][quantity]" min="1"
                   class="quantity-input w-full px-3 py-2 border rounded-lg text-sm text-center"
                   data-item-index="${rowCount - 1}" onchange="updateStockInfo(this)" required>
        </td>
        <td class="px-4 py-2">
            <select name="items[${rowCount - 1}][condition]" class="w-full px-3 py-2 border rounded-lg text-sm text-black" required>
                <option value="normal">Normal</option>
                <option value="damaged">Damaged</option>
            </select>
        </td>
        <td class="px-4 py-2 text-center">
            <span class="status-badge px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-700"
                  data-item-index="${rowCount - 1}">-</span>
        </td>
        <td class="px-4 py-2 text-center">
            <button type="button" class="remove-row text-red-600 hover:text-red-800 font-semibold"
                    onclick="removeRow(this)">✕</button>
        </td>
    `;
    tbody.appendChild(newRow);
    updateItemSelectOptions();
});

function removeRow(btn) {
    btn.closest('tr').remove();
    updateItemSelectOptions();
    validateForm();
}

function updateStockInfo(element) {
    const row = element.closest('tr');
    const itemIndex = element.dataset.itemIndex;
    const itemSelect = row.querySelector('.item-select');
    const quantityInput = row.querySelector('.quantity-input');
    const stockSpan = row.querySelector('.stock-available');
    const unitSpan = row.querySelector('.unit-display');
    const statusBadge = row.querySelector('.status-badge');

    if (!itemSelect.value) {
        stockSpan.textContent = '-';
        unitSpan.textContent = '-';
        statusBadge.textContent = '-';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-700';
        return;
    }

    const stock = parseInt(itemStocks[itemSelect.value].stock) || 0;
    const unit = itemStocks[itemSelect.value].unit || '-';
    const quantity = parseInt(quantityInput.value) || 0;

    stockSpan.textContent = stock;
    unitSpan.textContent = unit;

    if (quantity > stock) {
        statusBadge.textContent = '❌ Stok Kurang';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-700';
    } else if (quantity > 0) {
        statusBadge.textContent = '✓ OK';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-700';
    } else {
        statusBadge.textContent = '-';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-700';
    }

    validateForm();
}

function validateForm() {
    const rows = document.querySelectorAll('.outgoing-row');
    let warnings = [];
    let hasError = false;

    rows.forEach((row) => {
        const itemSelect = row.querySelector('.item-select');
        const quantityInput = row.querySelector('.quantity-input');

        if (!itemSelect.value || !quantityInput.value) return;

        const stock = parseInt(itemStocks[itemSelect.value].stock) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const itemName = itemSelect.options[itemSelect.selectedIndex].text;

        if (quantity > stock) {
            hasError = true;
            warnings.push(`<li>${itemName}: Permintaan ${quantity} unit, tetapi stok hanya ${stock} unit</li>`);
        }
    });

    const alertDiv = document.getElementById('validation-alert');
    const alertContent = document.getElementById('alert-content');
    const submitBtn = document.getElementById('submit-btn');

    if (hasError) {
        alertContent.innerHTML = '<ul>' + warnings.join('') + '</ul>';
        alertDiv.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        alertDiv.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    return !hasError;
}

// Initialize
updateItemSelectOptions();
</script>

@endsection

