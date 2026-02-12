<?php $__env->startSection('title', 'Edit Incoming Item'); ?>

<?php $__env->startSection('content'); ?>
<!-- SELECT2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-2xl font-bold text-white">Update Incoming Item</h2>
        <p class="text-blue-100 text-sm mt-1">Transaction Code: <span class="font-mono font-semibold"><?php echo e($incoming->code); ?></span></p>
    </div>

    <!-- Error Messages -->
    <?php if($errors->any()): ?>
    <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-red-800">Validasi gagal</h3>
                <ul class="mt-2 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-sm text-red-700">• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form Content -->
    <div class="p-6 space-y-6">
        <form action="<?php echo e(route('admin.incoming.update', $incoming->id)); ?>" method="POST" id="incomingForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Form Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Incoming Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="incoming_date"
                           value="<?php echo e(old('incoming_date', $incoming->incoming_date->format('Y-m-d'))); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Supplier <span class="text-red-500">*</span>
                    </label>
                    <select id="supplier_id" name="supplier_id" class="w-full" required>
                        <?php if($incoming->supplier_id): ?>
                            <option value="<?php echo e($incoming->supplier_id); ?>" selected><?php echo e($incoming->supplier->supplier_name ?? 'Selected Supplier'); ?></option>
                        <?php endif; ?>
                    </select>
                    <input type="hidden" id="supplier-id-value" value="<?php echo e(old('supplier_id', $incoming->supplier_id)); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <input type="text" name="notes"
                           value="<?php echo e(old('notes', $incoming->notes)); ?>"
                           placeholder="Optional notes"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
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
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-28">Qty</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-20">Unit</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-28">Status</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700 w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody" class="divide-y divide-gray-200">
                            <?php
                                $oldItems = old('items');
                                $rows = is_array($oldItems)
                                    ? $oldItems
                                    : $incoming->details->map(function ($detail) {
                                        return [
                                            'item_id' => $detail->item_id,
                                            'quantity' => $detail->quantity,
                                            'unit_id' => optional($detail->item)->unit_id ?? '',
                                            'item_name' => optional($detail->item)->item_name ?? 'Selected Item',
                                            'unit_name' => optional(optional($detail->item)->unit)->unit_name ?? '-',
                                        ];
                                    })->toArray();
                            ?>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $itemId = $row['item_id'] ?? '';
                                $selectedItem = $items->firstWhere('id', (int) $itemId);
                                $itemName = $selectedItem->item_name ?? ($row['item_name'] ?? 'Selected Item');
                                $unitName = optional(optional($selectedItem)->unit)->unit_name ?? ($row['unit_name'] ?? '-');
                                $unitId = optional($selectedItem)->unit_id ?? ($row['unit_id'] ?? '');
                            ?>
                            <tr class="item-row hover:bg-gray-50">
                                <td class="px-4 py-3 text-center count text-gray-600"><?php echo e($i + 1); ?></td>
                                <td class="px-4 py-3">
                                    <select name="items[<?php echo e($i); ?>][item_id]" class="item-select w-full" required>
                                        <option value="">-- Select Item --</option>
                                        <?php if($itemId): ?>
                                            <option value="<?php echo e($itemId); ?>"
                                                    data-unit="<?php echo e($unitName !== '-' ? $unitName : ''); ?>"
                                                    data-unit-id="<?php echo e($unitId); ?>"
                                                    selected>
                                                <?php echo e($itemName); ?>

                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <input type="hidden" class="item-id-value" value="<?php echo e($itemId); ?>">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[<?php echo e($i); ?>][quantity]"
                                           value="<?php echo e($row['quantity'] ?? 1); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                                           min="1"
                                           required>
                                </td>
                                <td class="px-4 py-3 text-center unit-display text-gray-600">
                                    <span class="unit-text"><?php echo e($unitName); ?></span>
                                    <input type="hidden" name="items[<?php echo e($i); ?>][unit_id]" class="unit-id-value" value="<?php echo e($unitId); ?>">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">✓ Saved</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 font-bold transition text-lg">✕</button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                    Update Transaction
                </button>
                <a href="<?php echo e(route('admin.incoming.index')); ?>" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
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
    let itemCount = <?php echo e(is_array(old('items')) ? count(old('items')) : count($incoming->details)); ?>;
    const apiUrl = '<?php echo e(route("admin.api.items.search")); ?>';
    const supplierApiUrl = '<?php echo e(route("admin.api.suppliers.search")); ?>';

    document.addEventListener('DOMContentLoaded', function () {
        const addItemBtn = document.getElementById('add-row');
        const tableBody = document.getElementById('itemsTableBody');
        const supplierIdValue = document.getElementById('supplier-id-value').value;

        // Initialize SELECT2 untuk Supplier
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
            placeholder: '-- Select Supplier --',
            allowClear: true
        });

        // Set initial supplier value
        if (supplierIdValue) {
            $('#supplier_id').val(supplierIdValue).trigger('change');
        }

        // Initialize SELECT2 untuk existing items
        document.querySelectorAll('.item-select').forEach((select, index) => {
            const hiddenValue = select.closest('tr').querySelector('.item-id-value')?.value;
            initializeSelect2(select, hiddenValue);
        });

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
            }).on('select2:select', function(e) {
                updateUnitDisplay(this);
            });
        }

        $('.item-select').each(function () {
            const select = $(this);
            const option = select.find('option:selected');

            if (option.val()) {
                const data = {
                    id: option.val(),
                    text: option.text(),
                    unit: option.data('unit'),
                    unit_id: option.data('unit-id')
                };

                const newOption = new Option(data.text, data.id, true, true);
                newOption.dataset.unit = data.unit;
                newOption.dataset.unitId = data.unit_id;
                select.append(newOption).trigger('change');

                // Update unit display immediately for existing items
                updateUnitDisplay(this);
            }
        });

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

        function updateUnitDisplay(selectElement) {
            const row = selectElement.closest('tr');
            const unitText = row.querySelector('.unit-text');
            const unitIdInput = row.querySelector('.unit-id-value');

            const select2Data = $(selectElement).select2('data');
            let unit = '-';
            let unitId = '';

            if (select2Data && select2Data[0] && (select2Data[0].unit || select2Data[0].unit_id)) {
                unit = select2Data[0].unit || '-';
                unitId = select2Data[0].unit_id || '';
            } else {
                const selectedOption = selectElement.querySelector('option:checked');
                if (selectedOption && selectedOption.dataset.unit) {
                    unit = selectedOption.dataset.unit;
                    unitId = selectedOption.dataset.unitId || '';
                }
            }

            if (unitText) {
                unitText.textContent = unit;
            }
            if (unitIdInput) {
                unitIdInput.value = unitId;
            }
        }


        addItemBtn.addEventListener('click', function () {
            const newRow = document.createElement('tr');
            newRow.classList.add('item-row', 'hover:bg-gray-50');

            newRow.innerHTML = `
                <td class="px-4 py-3 text-center count text-gray-600"></td>
                <td class="px-4 py-3">
                    <select name="items[${itemCount}][item_id]" class="item-select w-full" required>
                        <option value="">-- Select Item --</option>
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${itemCount}][quantity]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           min="1" value="1" required>
                </td>
                <td class="px-4 py-3 text-center unit-display text-gray-600">
                    <span class="unit-text">-</span>
                    <input type="hidden" name="items[${itemCount}][unit_id]" class="unit-id-value" value="">
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">-</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="remove-row text-red-500 hover:text-red-700 font-bold transition text-lg">✕</button>
                </td>
            `;

            tableBody.appendChild(newRow);

            // Initialize SELECT2 untuk select baru
            const newSelect = newRow.querySelector('.item-select');
            initializeSelect2(newSelect);

            // Setup event listeners untuk select baru
            $(newSelect).on('select2:select', function(e) {
                updateUnitDisplay(newSelect);
            });

            itemCount++;
            updateRowNumbers();
            attachRemoveListeners();
        });

        tableBody.addEventListener('change', function (e) {
            if (e.target.matches('.item-select')) {
                updateUnitDisplay(e.target);
            }
        });

        // Setup event listeners untuk existing selects
        document.querySelectorAll('.item-select').forEach(select => {
            $(select).on('select2:select', function(e) {
                updateUnitDisplay(select);
            });

            // Trigger initial display after a short delay to ensure Select2 is ready
            setTimeout(() => updateUnitDisplay(select), 300);
        });

        function attachRemoveListeners() {
            document.querySelectorAll('.remove-row').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const rows = document.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        const row = this.closest('tr');
                        const select = row.querySelector('.item-select');
                        if (select) {
                            $(select).select2('destroy');
                        }
                        row.remove();
                        updateRowNumbers();
                    }
                });
            });
        }

        function updateRowNumbers() {
            document.querySelectorAll('.item-row').forEach((row, i) => {
                row.querySelector('.count').textContent = i + 1;
            });
        }

        attachRemoveListeners();
    });

    <?php if($errors->any()): ?>
        window.scrollTo({ top: 0, behavior: 'smooth' });
    <?php endif; ?>
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mardiana Syafitry\projIInventory\resources\views/admin/incoming/edit.blade.php ENDPATH**/ ?>