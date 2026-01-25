@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="px-6 py-4 bg-blue-600 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Edit Category</h2>
        <p class="text-sm text-blue-100">Update category information</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.categories.update', $category->id) }}"
          method="POST"
          class="p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold text-gray-800">Code Categories</label>
            <input type="text" name="code"
                   value="{{ $category->code }}"
                   class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                          focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                   disabled>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Category Name</label>
            <input type="text" name="name"
                   value="{{ $category->name }}"
                   class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                          focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Description</label>
            <textarea name="description" rows="4"
                      class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                             focus:ring-blue-500 focus:border-blue-500">{{ $category->description }}</textarea>
        </div>

        <!-- ACTION -->

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-black hover:bg-gray-100">
                Cancel
            </a>


        <button
            class="px-4 py-2 rounded-lg border border-gray-300 text-black hover:bg-blue-100">
            Update Category
        </button>
    </form>
</div>

@endsection

