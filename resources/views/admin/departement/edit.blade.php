@extends('layouts.admin')

@section('title', 'Edit Departement')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Update Departement</h2>
        <p class="text-sm text-gray-300">Update departement details</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.departement.update', $departement->id) }}"
          method="POST"
          class="p-6 space-y-3">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Departement Name
            <span class="text-red-500">*</span></label>
            <input type="text"
                   id="departement_name"
                   name="departement_name"
                   class="w-full mt-1 rounded-lg border @error('departement_name') border-red-500 @else border-gray-300 @enderror
                          text-gray-900
                          focus:ring-emerald-500 focus:border-emerald-500"
                   value="{{ old('departement_name', $departement->departement_name) }}"
                   required>
            @error('departement_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @include('admin.partials.check-name', [
                'type'=>'departement',
                'inputId'=>'departement_name',
                'original' => $departement->departement_name,
                'id' => $departement->id
            ])
        </div>

        <div>
        <label class="block text-sm font-bold text-gray-800 mb-1">
                Status
            <span class="text-red-500">*</span></label>

            <select name="status"
                    class="w-full rounded-lg border border-gray-300
                        text-gray-900
                        focus:ring-emerald-500 focus:border-emerald-500"
                    required>
                <option value="">-- Select Status --</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Description
            </label>
            <textarea name="departement_description"
                      rows="4"
                      class="w-full mt-1 rounded-lg border border-gray-300
                             text-gray-900
                             focus:ring-emerald-500 focus:border-emerald-500"
                      placeholder="Optional">{{ old('departement_description', $departement->departement_description) }}</textarea>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.departement.index') }}"
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
                Update
            </button>
        </div>
    </form>

</div>

@endsection
