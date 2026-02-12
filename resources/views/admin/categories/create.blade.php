@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Add New Category</h2>
        <p class="text-sm text-gray-300">Create new item category</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.categories.store') }}"
          method="POST"
          class="p-6 space-y-3">
        @csrf

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Category Name
            </label>
            <input type="text" 
                   id="category_name"
                   name="category_name"
                   class="w-full mt-1 rounded-lg border @error('category_name') border-red-500 @else border-gray-300 @enderror
                          text-gray-900
                          focus:ring-emerald-500 focus:border-emerald-500"
                   value="{{ old('category_name') }}"
                   required>
            @error('category_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @include('admin.partials.check-name', ['type'=>'category', 'inputId'=>'category_name'])
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Description
            </label>
            <textarea name="category_description"
                      rows="4"
                      class="w-full mt-1 rounded-lg border border-gray-300
                             text-gray-900
                             focus:ring-emerald-500 focus:border-emerald-500"
                      placeholder="Optional"></textarea>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 rounded-lg
                      bg-gray-200 text-gray-800
                      font-semibold hover:bg-gray-300 transition">
                 Back
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-lg
                       bg-emerald-600 text-white
                       font-bold shadow
                       hover:bg-emerald-700 transition">
                Save Category
            </button>
        </div>
    </form>

</div>

@endsection

