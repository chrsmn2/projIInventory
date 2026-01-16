@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Add New Category</h2>
        <p class="text-sm text-emerald-100">Create new item category</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.categories.store') }}"
          method="POST"
          class="p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Category Name
            </label>
            <input type="text" name="name"
                   class="w-full mt-1 rounded-lg border-gray-300
                          text-gray-900
                          focus:ring-emerald-500 focus:border-emerald-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Description
            </label>
            <textarea name="description"
                      rows="4"
                      class="w-full mt-1 rounded-lg border-gray-300
                             text-gray-900
                             focus:ring-emerald-500 focus:border-emerald-500"
                      placeholder="Optional"></textarea>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 rounded-lg
                      bg-gray-200 text-gray-800
                      font-semibold hover:bg-gray-300 transition">
                 Back
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-lg
                       bg-emerald-600 text-black
                       font-bold shadow
                       hover:bg-emerald-700 transition">
                Save Category
            </button>
        </div>
    </form>

</div>

@endsection
