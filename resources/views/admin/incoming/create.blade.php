@extends('layouts.admin')

@section('title', 'Add Incoming Items')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Add Incoming Items</h2>
            <p class="text-sm text-gray-300">
                Add new incoming items and automatically update stock
            </p>
        </div>

        <a href="{{ route('admin.incoming.index') }}"
           class="inline-flex items-center gap-2
                  px-4 py-2 bg-gray-200 text-gray-700
                  rounded-lg font-semibold text-sm
                  hover:bg-gray-300 transition">
            &larr; Back
        </a>

    </div>

     {{-- GLOBAL ERROR ALERT --}}
    @if ($errors->any())
        <div class="mx-6 mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <strong class="block mb-1">Terjadi kesalahan input:</strong>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <div class="p-6">
        <form action="{{ route('admin.incoming.store') }}" method="POST" id="incoming-form">
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
                    <input type="date" name="incoming_date" value="{{ old('incoming_date') }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200 @error('incoming_date') border-red-500 @enderror" required>
                    @error('incoming_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Vendors<span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200 @error('supplier_id') border-red-500 @enderror" required>
                        <option value="">Select Vendors</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ (string)old('supplier_id') == (string)$supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           placeholder="Optional notes"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">
                </div>
            </div>

            @error('items') 
                <div class="p-4 mb-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <strong>Error:</strong> {{ $message }}
                </div>
            @enderror

            <!-- ITEMS TABLE -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Items <span class="text-red-500">*</span></label>
                <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 w-10 text-center">#</th>
                            <th class="px-4 py-2">Item</th>
                            <th class="px-4 py-2 w-32 text-center">Quantity</th>
                            <th class="px-4 py-2 w-24 text-center">Unit</th>
                            <th class="px-4 py-2 w-24 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="items-table-body">
                        <tr class="item-row">
                            <td class="px-4 py-2 text-center">1</td>
                            <td class="px-4 py-2">
                                <select name="items[0][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black" required>
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}" data-unit="{{ $item->unit?->name ?? 'N/A' }}"
                                        data-unit-id="{{ $item->unit?->id }}">
                                            {{ $item->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <td class="px-4 py-2">
                                <input type="number" name="items[0][quantity]" min="1"
                                       class="w-full px-3 py-2 border rounded-lg text-sm" required>
                            </td>
                            <td class="px-4 py-2">
                                    <input type="text"
                                           class="unit-display w-full px-3 py-2 border rounded-lg bg-gray-50"
                                           readonly>

                                    <input type="hidden" name="items[0][unit_id]" class="unit-id">
                                </td>
                            <td class="px-4 py-2 text-center">
                                <button type="button" class="remove-row text-red-600 hover:text-red-800 font-semibold">✕</button>
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

            <!-- SUBMIT -->
            <div class="mt-8 flex gap-4 border-t pt-6">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    Save Incoming Items
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const items = @json($items);

document.addEventListener('DOMContentLoaded', function () {

    const addRowBtn = document.getElementById('add-row');
    const tableBody = document.getElementById('items-table-body');

    // =====================================================
    // LOGIKA ANTI-DUPLIKAT (TETAP – TIDAK DIUBAH)
    // =====================================================
    function getSelectedItems() {
        const selectedItems = new Set();
        document.querySelectorAll('.item-select').forEach(select => {
            if (select.value) selectedItems.add(select.value);
        });
        return selectedItems;
    }

    function updateItemSelectOptions() {
        const selectedItems = getSelectedItems();

        document.querySelectorAll('.item-select').forEach(select => {
            const currentValue = select.value;

            select.querySelectorAll('option').forEach(option => {
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

    // =====================================================
    // LOGIKA UNIT (DITAMBAHKAN – TANPA MERUSAK YANG LAMA)
    // =====================================================
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
            return `
                <option value="${item.id}"
                        data-unit="${unitName}"
                        data-unit-id="${unitId}">
                    ${item.item_name}
                </option>
            `;
        }).join('');
    }

    // =====================================================
    // ADD ROW (TETAP, DITAMBAH unit_id)
    // =====================================================
    addRowBtn.addEventListener('click', function () {
        const newIndex = tableBody.querySelectorAll('tr').length;
        const newRowNumber = newIndex + 1;

        const newRow = document.createElement('tr');
        newRow.classList.add('item-row');

        newRow.innerHTML = `
            <td class="px-4 py-2 text-center">${newRowNumber}</td>
            <td class="px-4 py-2">
                <select name="items[${newIndex}][item_id]"
                        class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black"
                        required>
                    <option value="">Select Item</option>
                    ${generateItemOptions()}
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number"
                       name="items[${newIndex}][quantity]"
                       min="1"
                       class="w-full px-3 py-2 border rounded-lg text-sm"
                       required>
            </td>
            <td class="px-4 py-2">
                <input type="text"
                       class="unit-display w-full px-3 py-2 border rounded-lg text-sm bg-gray-50"
                       readonly
                       placeholder="Unit">
                <input type="hidden"
                       name="items[${newIndex}][unit_id]"
                       class="unit-id">
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button"
                        class="remove-row text-red-600 hover:text-red-800 font-semibold">✕</button>
            </td>
        `;

        tableBody.appendChild(newRow);
        updateItemSelectOptions();
    });

    // =====================================================
    // CHANGE ITEM (DISPLAY UNIT + ANTI DUPLIKAT)
    // =====================================================
    tableBody.addEventListener('change', function (e) {
        if (e.target.matches('.item-select')) {
            updateUnitDisplay(e.target);
            updateItemSelectOptions();
        }
    });

    // =====================================================
    // REMOVE ROW (TETAP – TIDAK DIUBAH)
    // =====================================================
    tableBody.addEventListener('click', function (e) {
        if (e.target.matches('.remove-row')) {
            e.preventDefault();
            e.target.closest('tr').remove();

            tableBody.querySelectorAll('tr').forEach((tr, index) => {
                tr.querySelector('td:first-child').textContent = index + 1;
            });

            updateItemSelectOptions();
        }
    });

    // =====================================================
    // INIT
    // =====================================================
    updateItemSelectOptions();

    @if ($errors->any())
        window.scrollTo({ top: 0, behavior: 'smooth' });
    @endif
});
</script>

@endsection

