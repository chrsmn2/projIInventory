@extends('layouts.admin')

@section('title', 'Edit Outgoing Item')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
    
    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-2xl font-bold text-white">Update Outgoing Item</h2>
        <p class="text-blue-100 text-sm mt-1">Transaction Code: <span class="font-mono font-semibold">{{ $outgoing->code }}</span></p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-red-800">Validasi gagal</h3>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-700">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Content -->
    <div class="p-6 space-y-6">
        <form action="{{ route('admin.outgoing.update', $outgoing->id) }}" method="POST" id="outgoingForm">
            @csrf
            @method('PUT')

            <!-- Form Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Outgoing Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="outgoing_date" 
                           value="{{ old('outgoing_date', $outgoing->outgoing_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none" 
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Department <span class="text-red-500">*</span>
                    </label>
                    <select id="departement_id" name="departement_id" class="w-full" required>
                        @if($outgoing->departement)
                            <option value="{{ $outgoing->departement_id }}" selected>{{ $outgoing->departement->departement_name }}</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <input type="text" name="notes" 
                           value="{{ old('notes', $outgoing->notes) }}" 
                           placeholder="Optional notes"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>
            </div>

            <!-- Validation Alert -->
            <div id="validation-alert" class="p-4 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg hidden">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="font-semibold">Attention:</p>
                        <p id="alert-content" class="text-sm mt-1"></p>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Items Selection <span class="text-red-500">*</span></label>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 w-10">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Item Name</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-24">Stock</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-28">Qty</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-20">Unit</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-28">Condition</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-28">Status</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body" class="divide-y divide-gray-200">
                            @foreach($outgoing->details as $index => $detail)
                            <tr class="outgoing-row hover:bg-gray-50">
                                <td class="px-4 py-3 text-center count text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <select name="items[{{ $index }}][item_id]" class="item-select w-full" required>
                                        <option value="{{ $detail->item_id }}" selected>{{ $detail->item->item_name }}</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold stock-display text-blue-600" data-val="{{ $detail->item->stock }}">
                                    {{ $detail->item->stock }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[{{ $index }}][quantity]" 
                                           value="{{ $detail->quantity }}" 
                                           min="1" 
                                           class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-blue-500 outline-none" 
                                           required>
                                </td>
                                <td class="px-4 py-3 text-center unit-display text-gray-600">
                                    {{ $detail->item->unit->unit_name ?? '-' }}
                                    <input type="hidden" name="items[{{ $index }}][unit_id]" class="unit-id-value" value="{{ $detail->item->unit_id ?? '' }}">
                                </td>
                                <td class="px-4 py-3">
                                    <select name="items[{{ $index }}][condition]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                                        <option value="normal" {{ $detail->condition == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="damaged" {{ $detail->condition == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">✓ Saved</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 font-bold transition text-lg">✕</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="add-row" class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Item
                </button>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                    Update Transaction
                </button>
                <a href="{{ route('admin.outgoing.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
let rowIdx = {{ $outgoing->details->count() }};
const itemApiUrl = '{{ route("admin.api.items.search") }}';
const deptApiUrl = '{{ route("admin.api.departements.search") }}';

$(document).ready(function() {
    // Department Select2
    $('#departement_id').select2({
        theme: 'bootstrap-5',
        ajax: { 
            url: deptApiUrl, 
            dataType: 'json', 
            delay: 250, 
            processResults: data => ({ results: data.results }) 
        },
        placeholder: 'Select Department'
    });

    // Initialize Existing Rows
    $('.outgoing-row').each(function() {
        initializeSelect2($(this).find('.item-select'));
        attachRowEvents($(this));
    });

    // Add Row
    $('#add-row').click(function() {
        let newRow = `
            <tr class="outgoing-row hover:bg-gray-50">
                <td class="px-4 py-3 text-center count text-gray-600"></td>
                <td class="px-4 py-3">
                    <select name="items[${rowIdx}][item_id]" class="item-select w-full" required></select>
                </td>
                <td class="px-4 py-3 text-center font-semibold stock-display text-blue-600">-</td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${rowIdx}][quantity]" min="1" class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                </td>
                <td class="px-4 py-3 text-center unit-display text-gray-600">-</td>
                <td class="px-4 py-3">
                    <select name="items[${rowIdx}][condition]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                        <option value="normal">Normal</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">-</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="remove-row text-red-500 hover:text-red-700 font-bold transition text-lg">✕</button>
                </td>
            </tr>`;
        
        let $newRow = $(newRow);
        $('#items-table-body').append($newRow);
        initializeSelect2($newRow.find('.item-select'));
        attachRowEvents($newRow);
        updateRowNumbers();
        rowIdx++;
    });

    // Remove Row
    $(document).on('click', '.remove-row', function() {
        if ($('.outgoing-row').length > 1) {
            $(this).closest('tr').remove();
            updateRowNumbers();
            validateStocks();
        }
    });

    validateStocks();
});

function getSelectedIds(currentSelect) {
    let ids = [];
    $('.item-select').each(function() {
        let val = $(this).val();
        if (val && this !== currentSelect) ids.push(val);
    });
    return ids.join(',');
}

function initializeSelect2(element) {
    element.select2({
        theme: 'bootstrap-5',
        width: '100%',
        ajax: {
            url: itemApiUrl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term, exclude: getSelectedIds(this[0]) };
            },
            processResults: function(data) {
                return {
                    results: data.results.map(item => ({
                        id: item.id,
                        text: item.text,
                        stock: item.stock,
                        unit: item.unit
                    }))
                };
            }
        },
        placeholder: 'Search Item...'
    }).on('select2:select', function(e) {
        let data = e.params.data;
        let row = $(this).closest('tr');
        
        row.find('.stock-display').text(data.stock).data('val', data.stock);
        row.find('.unit-display').text(data.unit);
        validateStocks();
    });
}

function attachRowEvents(row) {
    row.find('.quantity-input').on('input', validateStocks);
}

function updateRowNumbers() {
    $('.outgoing-row').each((i, row) => $(row).find('.count').text(i + 1));
}

function validateStocks() {
    let hasError = false;
    $('.outgoing-row').each(function() {
        let row = $(this);
        let stock = parseInt(row.find('.stock-display').data('val')) || 0;
        let qty = parseInt(row.find('.quantity-input').val()) || 0;
        let badge = row.find('.status-badge');

        if (qty > stock && stock > 0) {
            badge.text('❌ Low Stock').attr('class', 'status-badge px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700');
            hasError = true;
        } else if (qty > 0) {
            badge.text('✓ Ready').attr('class', 'status-badge px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700');
        }
    });

    if (hasError) {
        $('#validation-alert').removeClass('hidden').find('#alert-content').text('Quantity exceeds available stock!');
        $('#submit-btn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
    } else {
        $('#validation-alert').addClass('hidden');
        $('#submit-btn').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
    }
}
</script>
@endsection