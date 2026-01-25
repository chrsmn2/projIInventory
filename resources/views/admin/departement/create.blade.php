@extends('layouts.admin')

@section('title', 'Add Departement')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border border-gray-200">

    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-black">Add New Departement</h2>
        <p class="text-sm text-gray-500">Create a new master data departement</p>
    </div>

    <form action="{{ route('admin.departement.store') }}"
          method="POST"
          class="p-6 space-y-5">
        @csrf
       
         <div>
            <label class="block text-sm font-bold text-gray-800">
                Department Code
            </label>
            <input type="text" name="code"
                   class="w-full mt-1 rounded-lg border border-gray-300
                          text-gray-900
                          focus:ring-emerald-500 focus:border-emerald-500"
                   placeholder="Auto-generated"
                   disabled>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Departement Name
            </label>
            <input type="text" 
                   name="departement_name"
                   value="{{ old('departement_name') }}"
                   placeholder="e.g. IT Support"
                   class="w-full mt-1 rounded-lg border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('departement_name') border-red-500 @enderror"
                   required>
            @error('departement_name') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800 mb-1">Status/Condition</label>
            <select name="condition" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('condition') border-red-500 @enderror"
                    required>
                <option value="normal" {{ old('condition') === 'normal' ? 'selected' : '' }}>Normal (Active)</option>
                <option value="broken" {{ old('condition') === 'broken' ? 'selected' : '' }}>Broken (Inactive)</option>
            </select>
            @error('condition') 
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Description
            </label>
            <textarea name="description"
                      rows="4"
                      class="w-full mt-1 rounded-lg border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Optional description...">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center justify-between pt-4 border-t">
            <a href="{{ route('admin.departement.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition">
                 Cancel
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-lg bg-emerald-600 text-white font-bold shadow-md hover:bg-emerald-700 active:scale-95 transition">
                Save Departement
            </button>
        </div>
    </form>

</div>

@endsection
