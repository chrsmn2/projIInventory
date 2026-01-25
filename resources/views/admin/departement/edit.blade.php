@extends('layouts.admin')

@section('title', 'Departement Update')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <div class="px-6 py-4 bg-blue-600 rounded-t-xl">
        <h2 class="text-xl font-bold text-black">Update Departement</h2>
        <p class="text-sm text-gray-500">Update departement information</p>
    </div>

    <form action="{{ route('admin.departement.update', $departement->id) }}"
          method="POST"
          class="p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold text-gray-800">Departement Code</label>
            <input type="text" name="code"
                   value="{{ $departement->code_dept }}"
                   class="w-full mt-1 rounded-lg border border-gray-300 text-gray-900
                          focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                   disabled>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Departement Name</label>
            <input type="text" name="departement_name"
                   value="{{ old('departement_name', $departement->departement_name) }}"
                   class="w-full mt-1 rounded-lg border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('departement_name') border-red-500 @enderror"
                   required>
            @error('departement_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Condition
            </label>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                           name="condition"
                           value="normal"
                           {{ old('condition', $departement->is_active == 1 ? 'normal' : 'damaged') === 'normal' ? 'checked' : '' }}
                           class="text-gray-700 focus:ring-blue-500">
                    <span class="text-sm">Normal</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                           name="condition"
                           value="damaged"
                           {{ old('condition', $departement->is_active == 1 ? 'normal' : 'damaged') === 'damaged' ? 'checked' : '' }}
                           class="text-red-600 focus:ring-red-500">
                    <span class="text-sm">Damaged</span>
                </label>
            </div>
            @error('condition')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-800">Description</label>
            <textarea name="description" rows="4"
                      class="w-full mt-1 rounded-lg border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $departement->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.departement.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-md transition">
                Update Departement
            </button>
        </div>
    </form>
</div>

@endsection
