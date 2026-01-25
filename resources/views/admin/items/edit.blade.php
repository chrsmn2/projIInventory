@extends('layouts.admin')

@section('title', 'Edit Item')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-lg font-semibold text-white">Edit Item</h2>
    </div>

    <!-- FORM -->
    <form action="{{ route('admin.items.update', $item->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <!-- Item Code (DISABLED) -->
        <div class="mb-4">
            <label for="item_code" class="block text-sm font-bold text-gray-700 mb-2">Item Code <span class="text-red-500">*</span></label>
            <input type="text" id="item_code" name="item_code" value="{{ $item->item_code }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 outline-none" 
                   disabled>
            <p class="text-xs text-gray-500 mt-1">🔒 Item code cannot be changed</p>
        </div>

        <!-- Item Name -->
        <div class="mb-4">
            <label for="item_name" class="block text-sm font-bold text-gray-700 mb-2">Item Name <span class="text-red-500">*</span></label>
            <input type="text" id="item_name" name="item_name" value="{{ old('item_name', $item->item_name) }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('item_name') border-red-500 @enderror" 
                   placeholder="Enter item name" required>
            @error('item_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Category (Select2) -->
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
            <select id="category_id" name="category_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('category_id') border-red-500 @enderror select2" required>
                <option value="">-- Select Category --</option>
                @forelse($categories as $category)
                    <option value="{{ $category->id }}" data-code="{{ $category->code }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->code }} - {{ $category->name }}
                    </option>
                @empty
                    <option disabled>No categories available</option>
                @endforelse
            </select>
            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Condition -->
        <div class="mb-4">
            <label for="condition" class="block text-sm font-bold text-gray-700 mb-2">Condition <span class="text-red-500">*</span></label>
            <select id="condition" name="condition" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('condition') border-red-500 @enderror" required>
                <option value="">-- Select Condition --</option>
                <option value="Good" {{ old('condition', $item->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                <option value="Fair" {{ old('condition', $item->condition) == 'Fair' ? 'selected' : '' }}>Fair</option>
                <option value="Poor" {{ old('condition', $item->condition) == 'Poor' ? 'selected' : '' }}>Poor</option>
            </select>
            @error('condition') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Minimum Stock -->
        <div class="mb-4">
            <label for="min_stock" class="block text-sm font-bold text-gray-700 mb-2">Minimum Stock <span class="text-red-500">*</span></label>
            <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $item->minimum_stocks) }}" min="0"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('min_stock') border-red-500 @enderror" 
                   placeholder="Enter minimum stock level" required>
            @error('min_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Unit (Select2) -->
        <div class="mb-4">
            <label for="unit_id" class="block text-sm font-bold text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
            <select id="unit_id" name="unit_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('unit_id') border-red-500 @enderror select2" required>
                <option value="">-- Select Unit --</option>
                @forelse($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $item->unit_id) == $unit->id ? 'selected' : '' }}>
                        {{ $unit->code }} - {{ $unit->name }}
                    </option>
                @empty
                    <option disabled>No units available</option>
                @endforelse
            </select>
            @error('unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Stock (DISABLED) -->
        <div class="mb-4">
            <label for="stock" class="block text-sm font-bold text-gray-700 mb-2">Stock <span class="text-red-500">*</span></label>
            <input type="number" id="stock" name="stock" value="{{ $item->stock }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 outline-none" 
                   disabled>
            <p class="text-xs text-gray-500 mt-1">🔒 Stock is managed automatically through incoming and outgoing transactions</p>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
            <textarea id="description" name="description" rows="4" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('description') border-red-500 @enderror" 
                      placeholder="Enter item description">{{ old('description', $item->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 border-t pt-6">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">

<!-- Include Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            placeholder: '-- Select Option --'
        });
    });
</script>

                ✓ Update
            </button>
            <a href="{{ route('admin.items.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                ✕ Cancel
            </a>
        </div>
    </form>

</div>
@endsection

