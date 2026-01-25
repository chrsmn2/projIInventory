@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Profil</h1>
        <p class="text-gray-600 mt-2">Kelola informasi akun dan keamanan Anda</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Info Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 h-32"></div>
        
        <div class="px-8 pb-8">
            <!-- Profile Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-6 -mt-16 mb-8">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center border-4 border-white shadow-lg">
                        <svg class="h-16 w-16 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.7 15.25c4.967 0 9.311 2.684 11.3 6.75z" />
                            <path d="M16.5 5.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->position)
                        <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->position }}
                            @if(auth()->user()->department)
                                • {{ auth()->user()->department }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            <!-- Edit Profile Form -->
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 border-t pt-8">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        @error('name')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        @error('username')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        @error('phone')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Email (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                        <input type="text" name="department" value="{{ old('department', auth()->user()->department) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        @error('department')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="position" value="{{ old('position', auth()->user()->position) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        @error('position')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                    <textarea name="address" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">{{ old('address', auth()->user()->address) }}</textarea>
                    @error('address')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                </div>

                <!-- Bio -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Ceritakan tentang Anda..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">{{ old('bio', auth()->user()->bio) }}</textarea>
                    @error('bio')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                </div>

                <!-- Profile Photo Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                        <input type="file" name="profile_photo" accept="image/*" id="profile_photo"
                               class="w-full">
                        <p class="text-gray-500 text-sm mt-2">Format: JPG, PNG, GIF • Ukuran maksimal: 2MB</p>
                    </div>
                    @error('profile_photo')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Card -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Ubah Password</h3>
        
        <form action="{{ route('admin.profile.update-password') }}" method="POST" class="space-y-6 max-w-2xl">
            @csrf
            @method('PATCH')

            <!-- Current Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini *</label>
                <input type="password" name="current_password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                @error('current_password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                @error('password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
            </div>

            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                Ubah Password
            </button>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-red-600">
        <h3 class="text-2xl font-bold text-red-600 mb-4">Danger Zone</h3>
        <p class="text-gray-600 mb-6">Menghapus akun Anda akan menghapus semua data secara permanen dan tidak dapat dipulihkan.</p>
        
        <form action="{{ route('admin.profile.destroy') }}" method="POST" class="max-w-2xl" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE')

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi dengan memasukkan password Anda *</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                @error('password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                Hapus Akun Secara Permanen
            </button>
        </form>
    </div>
</div>

<script>
    // Auto-update header profile photo when image is selected
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                // Update image preview if needed
                const preview = document.querySelector('[data-profile-preview]');
                if (preview) {
                    preview.src = event.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-refresh header photo after form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form && form.action.includes('profile.update')) {
            form.addEventListener('submit', function(e) {
                // After successful submission, refresh the header photo
                setTimeout(function() {
                    if (window.Alpine && window.Alpine.store) {
                        // Alpine refresh
                        window.location.reload();
                    }
                }, 1000);
            });
        }
    });
</script>
@endsection