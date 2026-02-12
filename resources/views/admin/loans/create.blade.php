@extends('layouts.admin')

@section('title', 'Add Loan')

@section('content')
<!-- SELECT2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Add Loan</h2>
            <p class="text-sm text-indigo-100">Create a new loan request for borrowing items</p>
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

        @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.loans.store') }}" method="POST" class="space-y-6" id="loanForm">
            @csrf

            <!-- BASIC INFO -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Loan Date <span class="text-red-500">*</span></label>
                    <input type="date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Requester <span class="text-red-500">*</span></label>
                    <select name="requester_name" id="requester_select" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200" onchange="updateDepartment()" required>
                        <option value="">-- Select Requester --</option>
                        @foreach($requesters as $requester)
                            <option value="{{ $requester->requester_name }}"
                                    data-department="{{ $requester->department }}"
                                    {{ old('requester_name') == $requester->requester_name ? 'selected' : '' }}>
                                {{ $requester->requester_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Department <span class="text-red-500">*</span></label>
                    <input type="text" id="department_input" name="department" value="{{ old('department') }}"
                           placeholder="Department name"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200" required>
                </div>
            </div>

            <!-- PURPOSE -->
            <div>
                <label class="block text-sm font-medium mb-2">Purpose</label>
                <textarea name="purpose" rows="2" placeholder="Reason for borrowing..."
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">{{ old('purpose') }}</textarea>
            </div>

            <hr class="my-4">

            <!-- ITEMS -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Loaned Items</h3>
                    <button type="button" id="add-row-btn"
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
                            <tr class="loan-row hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <select name="items[0][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm"
                                            data-item-index="0" style="width: 100%;" required>
                                        <option value="">-- Select Item --</option>
                                    </select>
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    <span class="stock-available" data-item-index="0">-</span>
                                </td>

                                <td class="px-4 py-3">
                                    <input type="number" name="items[0][quantity]" min="1"
                                           class="quantity-input w-full px-3 py-2 border rounded-lg text-sm text-center"
                                           data-item-index="0" required>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span class="status-badge px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700"
                                          data-item-index="0">-</span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-row text-red-600 font-bold text-lg hover:text-red-800">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTION -->
            <div class="flex gap-3 pt-6 border-t">
                <button type="submit" id="submit-btn"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    Save Loan
                </button>

                <a href="{{ route('admin.loans.index') }}"
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- SELECT2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
let index = 1;
const apiUrl = '{{ route("admin.api.items.search") }}';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize SELECT2 untuk row pertama
    initializeSelect2(document.querySelector('.item-select'));

    // Setup event listener untuk row pertama
    setupRowEventListeners(document.querySelector('.loan-row'));

    // Trigger auto-fill department
    updateDepartment();
    validateForm();

    // Add row button
    document.getElementById('add-row-btn').addEventListener('click', function() {
        const table = document.getElementById('items-table');
        const row = document.createElement('tr');
        row.className = 'loan-row hover:bg-gray-50';

        row.innerHTML = `
            <td class="px-4 py-3">
                <select name="items[${index}][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm"
                        data-item-index="${index}" style="width: 100%;" required>
                    <option value="">-- Select Item --</option>
                </select>
            </td>

            <td class="px-4 py-3 text-center font-semibold">
                <span class="stock-available" data-item-index="${index}">-</span>
            </td>

            <td class="px-4 py-3">
                <input type="number" name="items[${index}][quantity]" min="1"
                       class="quantity-input w-full px-3 py-2 border rounded-lg text-sm text-center"
                       data-item-index="${index}" required>
            </td>

            <td class="px-4 py-3 text-center">
                <span class="status-badge px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700"
                      data-item-index="${index}">-</span>
            </td>

            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-row text-red-600 font-bold text-lg hover:text-red-800">
                    ✕
                </button>
            </td>
        `;

        table.appendChild(row);

        // Initialize SELECT2 untuk select baru
        const newSelect = row.querySelector('.item-select');
        initializeSelect2(newSelect);

        // Setup event listeners untuk row baru
        setupRowEventListeners(row);

        index++;
        validateForm();
    });

    // Event listeners untuk remove row
    document.getElementById('items-table').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            const select = row.querySelector('.item-select');
            $(select).select2('destroy');
            row.remove();
            validateForm();
        }
    });
});

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

function initializeSelect2(selectElement) {
    $(selectElement).select2({
        theme: 'bootstrap-5',
        ajax: {
            url: apiUrl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    exclude: getSelectedItems().join(',')
                };
            },
            processResults: function(data) {
                return {
                    results: data.results.map(item => ({
                        id: item.id,
                        text: item.text + ' (Stock: ' + item.stock + ')',
                        stock: item.stock,
                        unit: item.unit,
                        unit_id: item.unit_id
                    }))
                };
            },
            cache: true
        },
        minimumInputLength: 0,
        placeholder: 'Select Item',
        allowClear: true
    });
}

function getSelectedItems() {
    const selectedItems = [];
    document.querySelectorAll('.item-select').forEach(select => {
        if (select.value) selectedItems.push(select.value);
    });
    return selectedItems;
}

function setupRowEventListeners(row) {
    const itemSelect = row.querySelector('.item-select');
    const quantityInput = row.querySelector('.quantity-input');

    // Event untuk ubah item
    $(itemSelect).on('select2:select', function(e) {
        updateStockInfo(itemSelect);
    });

    // Event untuk ubah quantity
    quantityInput.addEventListener('change', function() {
        updateStockInfo(itemSelect);
    });
}

function updateStockInfo(itemSelect) {
    const row = itemSelect.closest('tr');
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

    const data = $(itemSelect).select2('data');
    if (!data || !data[0]) {
        stockSpan.textContent = '-';
        statusBadge.textContent = '-';
        statusBadge.className = 'status-badge px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700';
        validateForm();
        return;
    }

    const stock = parseInt(data[0].stock) || 0;
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

        const data = $(itemSelect).select2('data');
        if (!data || !data[0]) return;

        const stock = parseInt(data[0].stock) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const itemName = data[0].text;

        if (quantity > stock) {
            hasError = true;
            warnings.push(`<li>${itemName}: Requesting ${quantity}, stock only ${stock}</li>`);
        }
    });

    const alertDiv = document.getElementById('validation-alert');
    const alertContent = document.getElementById('alert-content');
    const submitBtn = document.getElementById('submit-btn');

    if (hasError) {
        alertContent.innerHTML = '<ul class="ml-4 list-disc">' + warnings.join('') + '</ul>';
        alertDiv.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        alertDiv.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}
</script>

@endsection

