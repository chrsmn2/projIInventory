<?php $__env->startSection('title', 'Incoming Items'); ?>

<?php $__env->startSection('content'); ?>
<?php
    /**
     * Sort helper
     */
    function sortUrl($column) {
        $isActive = request('sort_by') === $column;
        $dir = ($isActive && request('sort_dir') === 'asc') ? 'desc' : 'asc';

        return route('admin.incoming.index', array_merge(request()->all(), [
            'sort_by'  => $column,
            'sort_dir' => $dir,
        ]));
    }

    function sortIcon($column) {
        if (request('sort_by') !== $column) return '⇅';
        return request('sort_dir') === 'asc' ? '▲' : '▼';
    }
?>

<div class="space-y-6">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Incoming Items</h1>
            <p class="text-gray-600 mt-1">Manage incoming item transactions</p>
        </div>

        <a href="<?php echo e(route('admin.incoming.create')); ?>"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
            + Add Incoming Item
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="GET" id="filterForm" class="flex flex-col sm:flex-row gap-3">

            
            <div class="flex flex-1 gap-2">
                <input type="text"
                       name="search"
                       value="<?php echo e($search); ?>"
                       placeholder="Search code, date, suppliers..."
                       class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </button>
            </div>

            
            <a href="<?php echo e(route('admin.incoming.index')); ?>"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 text-center">
                Reset
            </a>

            
            <div class="w-40">
                <select name="per_page"
                        onchange="document.getElementById('filterForm').submit();"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <?php $__currentLoopData = [5,10,25,50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size); ?>" <?php echo e($perPage == $size ? 'selected' : ''); ?>>
                            Show <?php echo e($size); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

        </form>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>

                        <th class="px-6 py-4 text-left">
                            <a href="<?php echo e(sortUrl('code')); ?>"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Code <span class="text-xs"><?php echo e(sortIcon('code')); ?></span>
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="<?php echo e(sortUrl('incoming_date')); ?>"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Date <span class="text-xs"><?php echo e(sortIcon('incoming_date')); ?></span>
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">Admin</th>
                        <th class="px-6 py-4 text-left">
                            <a href="<?php echo e(sortUrl('supplier_name')); ?>" class="inline-flex items-center gap-1 hover:text-blue-600">
                                Supplier <span class="text-xs"><?php echo e(sortIcon('supplier_name')); ?></span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $incoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">
                            <?php echo e($incoming->firstItem() + $loop->index); ?>

                        </td>

                        <td class="px-6 py-4 font-mono text-blue-600 font-semibold">
                            <?php echo e($item->code); ?>

                        </td>

                        <td class="px-6 py-4 font-medium">
                            <?php echo e(\Carbon\Carbon::parse($item->incoming_date)->format('d-m-Y')); ?>

                        </td>

                        <td class="px-6 py-4">
                            <?php echo e($item->admin?->name ?? '-'); ?>

                        </td>

                        <td class="px-6 py-4 font-medium">
                            <?php echo e($item->supplier?->supplier_name ?? '-'); ?>

                        </td>

                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?php echo e(route('admin.incoming.show', $item->id)); ?>" class="p-1 text-blue-600 hover:text-blue-800" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <a href="<?php echo e(route('admin.incoming.edit', $item->id)); ?>" class="p-1 text-blue-600 hover:text-blue-800" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>

                                <form method="POST" action="<?php echo e(route('admin.incoming.destroy', $item->id)); ?>" onsubmit="return confirm('Delete this incoming record?')" style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-1 text-red-600 hover:text-red-800" title="Delete">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            No incoming items found
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="px-6 py-4 border-t bg-gray-50 flex flex-col sm:flex-row sm:justify-between gap-4">
            <p class="text-sm text-gray-600">
                Showing
                <strong><?php echo e($incoming->firstItem() ?? 0); ?></strong>
                to
                <strong><?php echo e($incoming->lastItem() ?? 0); ?></strong>
                of
                <strong><?php echo e($incoming->total()); ?></strong>
                items
            </p>

            <?php echo e($incoming->links('pagination::tailwind')); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mardiana Syafitry\projIInventory\resources\views/admin/incoming/index.blade.php ENDPATH**/ ?>