@extends('layouts.admin')

@section('title', 'Edit Unit')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border">

    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Edit Unit</h2>
        <p class="text-sm text-gray-300">Update unit details</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.units.update', $unit->id) }}"
          method="POST"
          class="p-6 space-y-3">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Unit Name
            </label>
            <input type="text" 
                   id="unit_name"
                   name="unit_name"
                   class="w-full mt-1 rounded-lg border @error('unit_name') border-red-500 @else border-gray-300 @enderror
                          text-gray-900
                          focus:ring-emerald-500 focus:border-emerald-500"
                   value="{{ old('unit_name', $unit->unit_name) }}"
                   required>
            @error('unit_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @include('admin.partials.check-name', [
                'type'=>'unit', 
                'inputId'=>'unit_name',
                'original' => $unit->unit_name,
                'id' => $unit->id
            ])
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Description
            </label>
            <textarea name="unit_description"
                      rows="4"
                      class="w-full mt-1 rounded-lg border border-gray-300
                             text-gray-900
                             focus:ring-emerald-500 focus:border-emerald-500"
                      placeholder="Optional">{{ old('unit_description', $unit->unit_description) }}</textarea>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.units.index') }}"
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
                Update Unit
            </button>
        </div>
    </form>

</div>

@endsection

