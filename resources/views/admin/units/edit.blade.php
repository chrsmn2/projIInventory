@extends('layouts.admin')

@section('title', 'Edit Unit')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="px-6 py-4 bg-blue-600 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Edit Unit</h2>
        <p class="text-sm text-blue-100">Update unit information</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.units.update', $unit->id) }}"
          method="POST"
          class="p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold text-gray-800">Unit Code</label>
            <input type="text" name="code"
                   value="{{ $unit->code }}"
                   class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                          focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                   disabled>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Unit Name</label>
            <input type="text" name="name"
                   value="{{ $unit->name }}"
                   class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                          focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Description</label>
            <textarea name="description" rows="4"
                      class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                             focus:ring-blue-500 focus:border-blue-500">{{ $unit->description }}</textarea>
        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.units.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-black hover:bg-gray-100">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold">
                Update Unit
            </button>
        </div>
    </form>

</div>

@endsection

