@extends('layouts.admin')

@section('title', 'Add Supplier')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border border-gray-200">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">
        <h2 class="text-xl font-bold text-white">Add New Vendors</h2>
        <p class="text-sm text-gray-300">Create a new master data vendors</p>
    </div>


    <form action="{{ route('admin.suppliers.store') }}" method="POST" class="p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-bold text-gray-800">
                Vendors Code
            </label>
            <input type="text" name="supplier_code"
                   class="w-full mt-1 rounded-lg border border-gray-300
                          text-gray-900
                          focus:ring-emerald-500 focus:border-emerald-500"
                   placeholder="Auto-generated"
                   disabled>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Vendors Name</label>
            <input type="text" name="supplier_name" value="{{ old('supplier_name') }}"
                   class="w-full px-3 py-2 border rounded-lg @error('supplier_name') border-red-500 @enderror"
                   required>
            @error('supplier_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Contact</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                   class="w-full px-3 py-2 border rounded-lg @error('contact_phone') border-red-500 @enderror"
                   placeholder="e.g., +62812345678"
                   required>
            @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Contact Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                   class="w-full px-3 py-2 border rounded-lg @error('contact_email') border-red-500 @enderror"
                   placeholder="e.g., supplier@example.com"
                   required>
            @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Address</label>
            <textarea name="address" rows="3"
                      class="w-full px-3 py-2 border rounded-lg @error('address') border-red-500 @enderror"
                      required>{{ old('address') }}</textarea>
            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg @error('status') border-red-500 @enderror"
                    required>
                <option value="">Select Status</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Save
            </button>
            <a href="{{ route('admin.suppliers.index') }}"
               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
