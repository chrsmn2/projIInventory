@extends('layouts.admin')

@section('title', 'Edit Item')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-xl">
        <h2 class="text-lg font-semibold text-white">Edit Item</h2>
        <p class="text-sm text-blue-100">Update item information</p>
    </div>

    <!-- FORM -->
    <form action="{{ route('admin.items.update', $item) }}"
          method="POST"
          class="p-6 space-y-5">
        @csrf
        @method('PUT')

        <!-- CATEGORY -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Category
            </label>
            <select name="category_id" required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $item->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- ITEM NAME -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Item Name
            </label>
            <input type="text" 
                   name="item_name" 
                   value="{{ $item->item_name }}"
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500",
                   required>
        </div>

        <!-- STOCK (READ ONLY) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Stock
            </label>
            <input type="number"
                value="{{ $item->stock }}"
                readonly
                class="w-full rounded-lg bg-gray-100 border-gray-300 text-gray-600 cursor-not-allowed">
            <p class="text-xs text-gray-500 mt-1">
                Stock is updated automatically from incoming & outgoing transactions
            </p>
        </div>


        <!-- CONDITION -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Condition
            </label>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                        name="condition"
                        value="normal"
                        {{ $item->condition === 'normal' ? 'checked' : '' }}
                        class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm">Normal</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                        name="condition"
                        value="damaged"
                        {{ $item->condition === 'damaged' ? 'checked' : '' }}
                        class="text-red-600 focus:ring-red-500">
                    <span class="text-sm">Damaged</span>
                </label>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Description
            </label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ $item->description }}</textarea>
        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.items.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">
                Cancel
            </a>

            <button
                class="px-5 py-2 bg-blue-600 text-white
                    font-semibold rounded-lg
                    hover:bg-blue-700 transition">
                Update Item
            </button>

        </div>
    </form>
</div>

@endsection
