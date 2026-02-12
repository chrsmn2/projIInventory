@extends('layouts.admin')

@section('title', 'Add Incoming Items')

@section('content')
<!-- SELECT2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="incoming_date" value="{{ old('incoming_date') }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200 @error('incoming_date') border-red-500 @enderror" required>
                    @error('incoming_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select id="supplier_id" name="supplier_id" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200 @error('supplier_id') border-red-500 @enderror" style="width: 100%;" required>
                        <option value="">Select Supplier</option>
                    </select>
                    @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
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
                                <select name="items[0][item_id]" class="item-select w-full px-3 py-2 border rounded-lg text-sm text-black" required style="width: 100%;">
                                    <option value="">Select Item</option>
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

<!-- SELECT2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addRowBtn = document.getElementById('add-row');
    const tableBody = document.getElementById('items-table-body');
    const apiUrl = '{{ route("admin.api.items.search") }}';
    const supplierApiUrl = '{{ route("admin.api.suppliers.search") }}';

    // =====================================================
    // INITIALIZE SELECT2 untuk Supplier
    // =====================================================
    $('#supplier_id').select2({
        theme: 'bootstrap-5',
        ajax: {
            url: supplierApiUrl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        minimumInputLength: 0,
        placeholder: 'Select Supplier',
        allowClear: true
    });

    // Set old value if exists
    @if(old('supplier_id'))
        $('#supplier_id').val({{ old('supplier_id') }}).trigger('change');
    @endif

    // =====================================================
    // INITIALIZE SELECT2 untuk select yang sudah ada
    // =====================================================
    function initializeSelect2(selectElement) {
    $(selectElement).select2({
        theme: 'bootstrap-5',
        ajax: {
            url: apiUrl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    exclude: getSelectedItems(selectElement).join(',')
                };
            },
            processResults: function (data) {
                return {
                    results: data.results.map(item => ({
                        id: item.id,
                        text: item.text,
                        unit: item.unit,
                        unit_id: item.unit_id
                    }))
                };
            }
        },
        placeholder: 'Select Item',
        allowClear: true
    });
}
    // Handle Select2 selection to auto-update unit 
    $(tableBody).on('select2:select', '.item-select', function (e) {
        const data = e.params.data;
        const row = this.closest('tr');

        row.querySelector('.unit-display').value = data.unit;
        row.querySelector('.unit-id').value = data.unit_id;
    });


    // =====================================================
    // LOGIKA ANTI-DUPLIKAT (TETAP)
    // =====================================================
    function getSelectedItems(excludeElement = null) {
        const selectedItems = [];
        document.querySelectorAll('.item-select').forEach(select => {
            if (excludeElement && select === excludeElement) return;
            const val = select.value;
            if (val && val.trim()) {
                selectedItems.push(val);
            }
        });
        return selectedItems;
    }

    function updateItemSelectOptions() {
        document.querySelectorAll('.item-select').forEach(select => {
            const currentValue = select.value;
            const data = $(select).select2('data');
            const selectedData = data && data[0] ? data[0] : null;

            // Reinitialize SELECT2 untuk update exclude list
            $(select).select2('destroy');
            initializeSelect2(select);

            if (selectedData && selectedData.id) {
                const option = new Option(selectedData.text, selectedData.id, true, true);
                if (selectedData.unit) {
                    option.dataset.unit = selectedData.unit;
                }
                if (selectedData.unit_id) {
                    option.dataset.unitId = selectedData.unit_id;
                }
                select.appendChild(option);
                $(select).trigger('change');
            } else if (currentValue) {
                $(select).val(currentValue).trigger('change');
            }
        });
    }

    // =====================================================
    // LOGIKA UNIT (UPDATE UNIT DISPLAY)
    // =====================================================
    function updateUnitDisplay(selectElement) {
        const row = selectElement.closest('tr');
        const unitDisplay = row.querySelector('.unit-display');
        const unitIdInput = row.querySelector('.unit-id');

        // Prefer Select2 data, fallback to option data attributes
        const data = $(selectElement).select2('data');
        let unit = '';
        let unit_id = '';

        if (data && data[0] && (data[0].unit || data[0].unit_id)) {
            unit = data[0].unit || '';
            unit_id = data[0].unit_id || '';
        } else {
            const selectedOption = selectElement.querySelector('option:checked');
            if (selectedOption) {
                unit = selectedOption.dataset.unit || '';
                unit_id = selectedOption.dataset.unitId || selectedOption.dataset.unit_id || '';
            }
        }

        unitDisplay.value = unit;
        unitIdInput.value = unit_id;
    }

    // =====================================================
    // HANDLE CHANGE & SELECT2:SELECT EVENT
    // =====================================================
    tableBody.addEventListener('change', function (e) {
        if (e.target.matches('.item-select')) {
            updateUnitDisplay(e.target);
            // Delay sedikit sebelum update options untuk memastikan SELECT2 sudah ready
            setTimeout(() => updateItemSelectOptions(), 100);
        }
    });

    // Handle Select2 selection to auto-update unit
    $(tableBody).on('select2:select', '.item-select', function(e) {
        updateUnitDisplay(this);
        setTimeout(() => updateItemSelectOptions(), 100);
    });

    // =====================================================
    // ADD ROW (DENGAN SELECT2)
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
                        required
                        style="width: 100%;">
                    <option value="">Select Item</option>
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

        // Initialize SELECT2 untuk select yang baru ditambah
        const newSelect = newRow.querySelector('.item-select');
        initializeSelect2(newSelect);

        updateItemSelectOptions();
    });

    // =====================================================
    // REMOVE ROW (TETAP)
    // =====================================================
    tableBody.addEventListener('click', function (e) {
        if (e.target.matches('.remove-row')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            $(row.querySelector('.item-select')).select2('destroy');
            row.remove();

            tableBody.querySelectorAll('tr').forEach((tr, index) => {
                tr.querySelector('td:first-child').textContent = index + 1;
            });

            updateItemSelectOptions();
        }
    });

    // =====================================================
    // INIT
    // =====================================================
    const initialSelect = document.querySelector('.item-select');
    if (initialSelect) {
        initializeSelect2(initialSelect);
    }

    @if ($errors->any())
        window.scrollTo({ top: 0, behavior: 'smooth' });
    @endif
});
</script>

@endsection
