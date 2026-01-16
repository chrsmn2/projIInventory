@extends('layouts.admin')

@section('title', 'Add Incoming Items')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Add Incoming Items</h2>
            <p class="text-sm text-indigo-100">
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

    <!-- FORM -->
    <div class="p-6">
        <form action="{{ route('admin.incoming.store') }}" method="POST">
            @csrf

            <!-- GENERAL INFO -->
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input type="date" name="incoming_date" value="{{ old('incoming_date') }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Suppliers</label>
                    <select name="suppliers" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ (string)old('suppliers') == (string)$supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Notes</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           placeholder="Optional notes"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- ITEMS TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 w-10 text-center">#</th>
                            <th class="px-4 py-2">Item</th>
                            <th class="px-4 py-2 w-32 text-center">Quantity</th>
                            <th class="px-4 py-2">Note</th>
                            <th class="px-4 py-2 w-24 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="items-table-body">
                        <tr class="item-row">
                            <td class="px-4 py-2 text-center">1</td>
                            <td class="px-4 py-2">
                                <select name="item_id[]" class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black">
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" name="quantity[]" min="1"
                                       class="w-full px-3 py-2 border rounded-lg text-sm">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="note[]" placeholder="Optional"
                                       class="w-full px-3 py-2 border rounded-lg text-sm">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button type="button" class="remove-row text-red-600 hover:text-red-800 font-semibold">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="add-row"
                        class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                    + Add Item
                </button>
            </div>

            <!-- SUBMIT -->
            <div class="mt-6">
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
</script>

<!-- DYNAMIC ROW SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addRowBtn = document.getElementById('add-row');
    const tableBody = document.getElementById('items-table-body');

    // Fungsi untuk update disabled items
    function updateItemStates() {
        const selectedItems = new Set();
        
        // Kumpulkan semua item yang sudah dipilih
        tableBody.querySelectorAll('select.item-select').forEach(select => {
            if (select.value) {
                selectedItems.add(select.value);
            }
        });

        // Update status disabled untuk semua option
        tableBody.querySelectorAll('select.item-select').forEach(select => {
            const currentValue = select.value;
            
            select.querySelectorAll('option').forEach(option => {
                if (option.value) {
                    if (selectedItems.has(option.value) && option.value !== currentValue) {
                        option.disabled = true;
                        option.textContent = option.textContent.replace(' (Dipilih)', '') + ' (Dipilih)';
                    } else {
                        option.disabled = false;
                        option.textContent = option.textContent.replace(' (Dipilih)', '');
                    }
                }
            });
        });
    }

    addRowBtn.addEventListener('click', function () {
        const rowCount = tableBody.querySelectorAll('tr').length + 1;

        const newRow = document.createElement('tr');
        newRow.classList.add('item-row');
        newRow.innerHTML = `
            <td class="px-4 py-2 text-center">${rowCount}</td>
            <td class="px-4 py-2">
                <select name="item_id[]" class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black">
                    <option value="">Select Item</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="quantity[]" min="1" class="w-full px-3 py-2 border rounded-lg text-sm">
            </td>
            <td class="px-4 py-2">
                <input type="text" name="note[]" placeholder="Optional" class="w-full px-3 py-2 border rounded-lg text-sm">
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button" class="remove-row text-red-600 hover:text-red-800 font-semibold">✕</button>
            </td>
        `;
        tableBody.appendChild(newRow);
        
        // Tambah event listener untuk select baru
        newRow.querySelector('select.item-select').addEventListener('change', updateItemStates);
        updateItemStates();
    });

    tableBody.addEventListener('click', function(e) {
        if(e.target && e.target.matches('button.remove-row')) {
            const row = e.target.closest('tr');
            row.remove();

            // Re-number rows
            tableBody.querySelectorAll('tr').forEach((tr, index) => {
                tr.querySelector('td:first-child').textContent = index + 1;
            });
            
            updateItemStates();
        }
    });

    // Event listener untuk perubahan item
    tableBody.addEventListener('change', function(e) {
        if (e.target.matches('select.item-select')) {
            updateItemStates();
        }
    });

    // Initial state
    updateItemStates();
});
</script>
@endsection
