@extends('layouts.app')

@section('title', 'Edit Requester')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-blue-600 text-white">
            <h2 class="text-xl font-bold">Edit Requester</h2>
            <p class="text-sm opacity-80">Modify existing requester details</p>
        </div>

        <form action="{{ route('admin.requesters.update', $requester->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="requester_name" value="{{ old('requester_name', $requester->requester_name) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('requester_name') border-red-500 @enderror"
                           required>
                    @error('requester_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department', $requester->department) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('department') border-red-500 @enderror"
                           required>
                    @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $requester->contact_phone) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('contact_phone') border-red-500 @enderror"
                               required>
                        @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $requester->contact_email) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('contact_email') border-red-500 @enderror"
                               required>
                        @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('status') border-red-500 @enderror" required>
                        <option value="active" {{ old('status', $requester->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $requester->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3 border-t pt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Changes
                </button>
                <a href="{{ route('admin.requesters.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection