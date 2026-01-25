@extends('layouts.app')

@section('title', 'Add Requester')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow border border-gray-200">
    
    <!--HEADER-->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-white">Add New Requester</h2>
    </div>

    @if ($errors->any())
        <div class="mx-auto p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Validation Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.requesters.store') }}" method="POST" class="p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Full Name -->
            <div class="md:col-span-2">
                <label for="requester_name" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Full Name</label>
                <input type="text" id="requester_name" name="requester_name" value="{{ old('requester_name') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none @error('requester_name') border-red-500 @enderror"
                       placeholder="Enter requester name" required>
                @error('requester_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Department -->
            <div class="md:col-span-2">
                <label for="departement_id" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Department</label>
                <select id="departement_id" name="departement_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none @error('departement_id') border-red-500 @enderror" required>
                    <option value="">-- Select Department --</option>
                    @if($departments)
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('departement_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->departement_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('departement_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Phone Number -->
            <div>
                <label for="contact_phone" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Phone Number</label>
                <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none @error('contact_phone') border-red-500 @enderror"
                       placeholder="Enter phone number">
                @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="contact_email" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Address</label>
                <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none @error('contact_email') border-red-500 @enderror"
                       placeholder="Enter email address">
                @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label for="status" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Account Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none @error('status') border-red-500 @enderror" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>ACTIVE</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>INACTIVE</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-10 flex gap-4">
            <button type="submit" class="px-10 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg">
                SAVE DATA
            </button>
            <a href="{{ route('admin.requesters.index') }}" class="px-10 py-3 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition">
                CANCEL
            </a>
        </div>
    </form>
</div>
@endsection
