@extends('layouts.admin')

@section('title', 'Add Outgoing Items')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="bg-white rounded-xl shadow border border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <div>
            <h2 class="text-xl font-semibold text-white">Add Outgoing Items</h2>
            <p class="text-sm text-gray-300">Add new outgoing items and automatically deduct stock</p>
        </div>
        <a href="{{ route('admin.outgoing.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">&larr; Back</a>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.outgoing.store') }}" method="POST" id="outgoingForm">
            @csrf

            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="outgoing_date" value="{{ old('outgoing_date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                    <select id="departement_id" name="departement_id" class="w-full" required>
                        <option value="">Select Department</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Notes</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Optional notes"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div id="validation-alert" class="mb-4 p-3 bg-yellow-50 border border-yellow-300 text-yellow-700 rounded hidden">
                <strong>Attention:</strong> <div id="alert-content"></div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">Items Selection</label>
                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 w-10 text-center">#</th>
                                <th class="px-4 py-3">Item Name</th>
                                <th class="px-4 py-3 w-24 text-center">Stock</th>
                                <th class="px-4 py-3 w-24 text-center">Unit</th>
                                <th class="px-4 py-3 w-32 text-center">Qty Request</th>
                                <th class="px-4 py-3">Condition</th>
                                <th class="px-4 py-3 w-28 text-center">Status</th>
                                <th class="px-4 py-3 w-16 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body" class="divide-y divide-gray-200">
                            <tr class="outgoing-row">
                                <td class="px-4 py-3 text-center count">1</td>
                                <td class="px-4 py-3">
                                    <select name="items[0][item_id]" class="item-select w-full" required></select>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold stock-display">-</td>
                                <td class="px-4 py-3 text-center unit-display">-</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[0][quantity]" min="1" class="quantity-input w-full px-3 py-2 border rounded-lg text-center" required>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="items[0][condition]" class="w-full px-3 py-2 border rounded-lg">
                                        <option value="normal">Normal</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="status-badge px-2 py-1 text-xs font-bold rounded bg-gray-100">-</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 font-bold">✕</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" id="add-row" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">+ Add Item</button>
            </div>

            <div class="mt-8 flex gap-3 border-t pt-6">
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700">Save Transaction</button>
                <a href="{{ route('admin.outgoing.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-bold">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
let rowIdx = 1;
const itemApiUrl = '{{ route("admin.api.items.search") }}';
const deptApiUrl = '{{ route("admin.api.departements.search") }}';

$(document).ready(function() {
    // 1. Dept Select2
    $('#departement_id').select2({
        theme: 'bootstrap-5',
        ajax: { url: deptApiUrl, dataType: 'json', delay: 250, processResults: data => ({ results: data.results }) },
        placeholder: 'Select Department'
    });

    // 2. Initial Row
    initializeSelect2($('.item-select'));
    attachRowEvents($('.outgoing-row'));

    // 3. Add Row
    // 3. Add Row
$('#add-row').click(function() {
    // Gunakan template string agar row benar-benar bersih/baru
    let newRowHtml = `
        <tr class="outgoing-row">
            <td class="px-4 py-3 text-center count"></td>
            <td class="px-4 py-3">
                <select name="items[${rowIdx}][item_id]" class="item-select w-full" required></select>
            </td>
            <td class="px-4 py-3 text-center font-semibold stock-display">-</td>
            <td class="px-4 py-3 text-center unit-display">-</td>
            <td class="px-4 py-3">
                <input type="number" name="items[${rowIdx}][quantity]" min="1" class="quantity-input w-full px-3 py-2 border rounded-lg text-center" required>
            </td>
            <td class="px-4 py-3">
                <select name="items[${rowIdx}][condition]" class="w-full px-3 py-2 border rounded-lg">
                    <option value="normal">Normal</option>
                    <option value="damaged">Damaged</option>
                </select>
            </td>
            <td class="px-4 py-3 text-center">
                <span class="status-badge px-2 py-1 text-xs font-bold rounded bg-gray-100">-</span>
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-row text-red-500 hover:text-red-700 font-bold">✕</button>
            </td>
        </tr>`;

    let $newRow = $(newRowHtml);
    
    // Append ke tabel
    $('#items-table-body').append($newRow);
    
    // Inisialisasi Select2 HANYA pada select yang baru dibuat
    initializeSelect2($newRow.find('.item-select'));
    
    // Pasang event listener untuk validasi stok
    attachRowEvents($newRow);
    
    // Update nomor urut
    updateRowNumbers();
    
    rowIdx++;
});

    // 4. Remove Row
    $(document).on('click', '.remove-row', function() {
        if ($('.outgoing-row').length > 1) {
            $(this).closest('tr').remove();
            updateRowNumbers();
            validateStocks();
        }
    });
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
                return { q: params.term, exclude: getSelectedIds(this) };
            },
            processResults: data => ({ results: data.results })
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
    let errors = [];

    $('.outgoing-row').each(function() {
        let row = $(this);
        let stock = parseInt(row.find('.stock-display').data('val')) || 0;
        let qty = parseInt(row.find('.quantity-input').val()) || 0;
        let itemName = row.find('.item-select text').text() || "Item";

        let badge = row.find('.status-badge');
        if (qty > stock && stock > 0) {
            badge.text('❌ Low Stock').attr('class', 'status-badge px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-700');
            errors.push(`Stock for this item is only ${stock}`);
            hasError = true;
        } else if (qty > 0) {
            badge.text('✓ Ready').attr('class', 'status-badge px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-700');
        }
    });

    if (hasError) {
        $('#validation-alert').removeClass('hidden');
        $('#alert-content').html('Some items exceed available stock.');
        $('#submit-btn').prop('disabled', true).addClass('opacity-50');
    } else {
        $('#validation-alert').addClass('hidden');
        $('#submit-btn').prop('disabled', false).removeClass('opacity-50');
    }
}
</script>
@endsection
