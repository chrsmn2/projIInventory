@extends('layouts.admin')

@section('title', 'Edit Loan')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Edit Loan</h2>
            <p class="text-sm text-indigo-100">Update loan request information and add items</p>
        </div>

        <a href="{{ route('admin.loans.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700
                  rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
            &larr; Back
        </a>
    </div>

    <!-- FORM -->
    <div class="p-6">
        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <strong>Error:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.loans.update', $loan->id) }}"
              method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- BASIC INFO -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Loan Date <span class="text-red-500">*</span></label>
                    <input type="date" name="loan_date"
                           value="{{ old('loan_date', $loan->loan_date) }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Requester <span class="text-red-500">*</span></label>
                    <select name="requester_name" id="requester_select" 
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200"
                            onchange="updateDepartment()" required>
                        <option value="">-- Select Requester --</option>
                        @foreach($requesters as $requester)
                            <option value="{{ $requester->requester_name }}" 
                                    data-department="{{ $requester->department }}"
                                    {{ old('requester_name', $loan->requester_name) == $requester->requester_name ? 'selected' : '' }}>
                                {{ $requester->requester_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Department <span class="text-red-500">*</span></label>
                    <input type="text" id="department_input" name="department"
                           value="{{ old('department', $loan->department) }}"
                           placeholder="Department name"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200" required>
                </div>
            </div>

            <!-- PURPOSE -->
            <div>
                <label class="block text-sm font-medium mb-2">Purpose</label>
                <textarea name="purpose" rows="2" placeholder="Reason for borrowing..."
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">{{ old('purpose', $loan->purpose) }}</textarea>
            </div>

            <hr class="my-4">

            <!-- CURRENT ITEMS -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Current Loaned Items</h3>
                <div class="overflow-x-auto border rounded-lg mb-4">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Item</th>
                                <th class="px-4 py-3 text-center">Quantity</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($loan->details as $detail)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $detail->item->item_name }}</td>
                                <td class="px-4 py-3 text-center">{{ $detail->quantity }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">
                                        {{ ucfirst($detail->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                    No items in this loan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ADD NEW ITEMS -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Add New Items</h3>
                    <button type="button" onclick="addRow()"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        + Add Item
                    </button>
                </div>

                <!-- VALIDATION ALERT -->
                <div id="validation-alert" class="mb-4 p-4 bg-yellow-50 border border-yellow-300 text-yellow-700 rounded-lg hidden">
                    <strong>⚠️ Stock Warning:</strong>
                    <div id="alert-content"></div>
                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Item</th>
                                <th class="px-4 py-3 text-center w-24">Available</th>
                                <th class="px-4 py-3 text-center w-28">Qty Request</th>
                                <th class="px-4 py-3 text-center w-24">Status</th>
                                <th class="px-4 py-3 text-center w-16">Action</th>
                            </tr>
                        </thead>

                        <tbody id="items-table" class="divide-y">
                            <!-- Empty row akan ditambah dengan button + Add Item -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTION -->
            <div class="flex gap-3 pt-6 border-t">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    Update Loan
                </button>

                <a href="{{ route('admin.loans.index') }}"
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill department dari requester
function updateDepartment() {
    const select = document.getElementById('requester_select');
    const departmentInput = document.getElementById('department_input');
    const selectedOption = select.options[select.selectedIndex];
    const department = selectedOption.getAttribute('data-department');
    
    if (department) {
        departmentInput.value = department;
    } else {
        departmentInput.value = '';
    }
}

let index = 0;
const itemStocks = {
    @foreach($items as $item)
        {{ $item->id }}: {{ $item->stock }},
    @endforeach
};

function addRow() {
    const table = document.getElementById('items-table');
    const row = `
        <tr class="loan-row hover:bg-gray-50">
            <td class="px-4 py-3">
                <select name="items[${index}][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm"
                        data-item-index="${index}" onchange="updateStockInfo(this)">
                    <option value="">-- Select Item --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-stock="{{ $item->stock }}">
                            {{ $item->item_name }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td class="px-4 py-3 text-center font-semibold">
                <span class="stock-available" data-item-index="${index}">-</span>
            </td>

            <td class="px-4 py-3">
                <input type="number" name="items[${index}][quantity]" min="1"
                       class="quantity-input w-full px-3 py-2 border rounded-lg text-sm text-center"
                       data-item-index="${index}" onchange="updateStockInfo(this)">
            </td>

            <td class="px-4 py-3 text-center">
                <span class="status-badge px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700"
                      data-item-index="${index}">-</span>
            </td>

            <td class="px-4 py-3 text-center">
                <button type="button" onclick="removeRow(this)"
                        class="text-red-600 font-bold text-lg hover:text-red-800">
                    ✕
                </button>
            </td>
        </tr>
    `;
    table.insertAdjacentHTML('beforeend', row);
    index++;
}

function removeRow(btn) {
    btn.closest('tr').remove();
    validateForm();
}

function updateStockInfo(element) {
    const row = element.closest('tr');
    const itemSelect = row.querySelector('.item-select');
    const quantityInput = row.querySelector('.quantity-input');
    const stockSpan = row.querySelector('.stock-available');
    const statusBadge = row.querySelector('.status-badge');

    if (!itemSelect.value) {
        stockSpan.textContent = '-';
        statusBadge.textContent = '-';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700';
        validateForm();
        return;
    }

    const stock = parseInt(itemStocks[itemSelect.value]) || 0;
    const quantity = parseInt(quantityInput.value) || 0;

    stockSpan.textContent = stock;

    if (quantity > stock) {
        statusBadge.textContent = '❌ Insufficient';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700';
    } else if (quantity > 0) {
        statusBadge.textContent = '✓ OK';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700';
    } else {
        statusBadge.textContent = '-';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700';
    }

    validateForm();
}

function validateForm() {
    const rows = document.querySelectorAll('.loan-row');
    let warnings = [];
    let hasError = false;

    rows.forEach((row) => {
        const itemSelect = row.querySelector('.item-select');
        const quantityInput = row.querySelector('.quantity-input');

        if (!itemSelect.value || !quantityInput.value) return;

        const stock = parseInt(itemStocks[itemSelect.value]) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const itemName = itemSelect.options[itemSelect.selectedIndex].text;

        if (quantity > stock) {
            hasError = true;
            warnings.push(`<li>${itemName}: Requesting ${quantity}, stock only ${stock}</li>`);
        }
    });

    const alertDiv = document.getElementById('validation-alert');
    const alertContent = document.getElementById('alert-content');

    if (hasError) {
        alertContent.innerHTML = '<ul class="ml-4 list-disc">' + warnings.join('') + '</ul>';
        alertDiv.classList.remove('hidden');
    } else {
        alertDiv.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateDepartment();
    // Tambah 1 row kosong saat halaman load
    addRow();
});
</script>
@endsection

