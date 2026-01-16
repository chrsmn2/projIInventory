<!-- filepath: resources/views/admin/suppliers/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Edit Supplier</h2>

    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Supplier Name</label>
            <input type="text" name="supplier_name" value="{{ old('supplier_name', $supplier->supplier_name) }}"
                   class="w-full px-3 py-2 border rounded-lg @error('supplier_name') border-red-500 @enderror"
                   required>
            @error('supplier_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Contact</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $supplier->contact_phone) }}"
                   class="w-full px-3 py-2 border rounded-lg @error('contact_phone') border-red-500 @enderror"
                   required>
            @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Contact Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}"
                   class="w-full px-3 py-2 border rounded-lg @error('contact_email') border-red-500 @enderror"
                   required>
            @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Address</label>
            <textarea name="address" rows="3"
                      class="w-full px-3 py-2 border rounded-lg @error('address') border-red-500 @enderror"
                      required>{{ old('address', $supplier->address) }}</textarea>
            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg @error('status') border-red-500 @enderror"
                    required>
                <option value="active" {{ old('status', $supplier->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $supplier->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update
            </button>
            <a href="{{ route('admin.suppliers.index') }}"
               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection