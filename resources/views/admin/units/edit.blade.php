@extends('layouts.admin')

@section('title', 'Edit Unit')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="flex flex-col px-8 py-8 rounded-t-xl bg-gradient-to-r from-gray-700 to-gray-800">
    <h2 class="text-xl font-bold text-white">
        Update Unit
    </h2>
    <p class="text-sm text-gray-300 mt-1">
        Update unit information
    </p>
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
                   class="w-full mt-1 rounded-lg border border-gray-300 text-blue-700
                          focus:ring-blue-500 focus:border-blue-500"
                   disabled>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Unit Name</label>
            <input type="text" name="name"
                   value="{{ old('name', $unit->name) }}"
                   class="w-full mt-1 rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900
                          focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
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
                class="px-6 py-2 rounded-lg
                       bg-emerald-600 text-white
                       font-bold shadow
                       hover:bg-emerald-700 transition">
                Update Unit
            </button>
        </div>
    </form>

</div>

@endsection

