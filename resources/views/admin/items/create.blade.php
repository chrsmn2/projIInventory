@extends('layouts.admin')

@section('title', 'Add Item')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-xl">
        <h2 class="text-lg font-semibold text-white">Add New Item</h2>
        <p class="text-sm text-emerald-100">Create a new inventory item</p>
    </div>

    <!-- FORM -->
    <form action="{{ route('admin.items.store') }}" method="POST" class="p-6 space-y-5">
        @csrf

        <!-- CATEGORY -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Category
            </label>
            <select name="category_id" required
                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
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
            <input type="text" name="item_name" required
                   placeholder="Enter item name"
                   class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
        </div>

        <!-- STOCK -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Stock
            </label>
            <input type="number"
       value="0"
       readonly
       class="w-full bg-gray-100 border-gray-300 cursor-not-allowed">
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
                        checked
                        class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Normal</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                        name="condition"
                        value="damaged"
                        class="text-red-600 focus:ring-red-500">
                    <span class="text-sm text-gray-700">Damaged</span>
                </label>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Description
            </label>
            <textarea name="description" rows="3"
                      placeholder="Optional description"
                      class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
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
                Save Item
            </button>

        </div>
    </form>
</div>

@endsection
