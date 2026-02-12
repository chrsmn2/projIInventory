@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="text-center space-y-4">
    <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 100 8 4 4 0 000-8z" />
    </svg>
    
    <h2 class="text-2xl font-bold text-gray-900">Pendaftaran Ditutup</h2>
    
    <p class="text-gray-600">
        Sistem ini tidak menyediakan self-registration. Silakan hubungi Administrator untuk membuat akun baru.
    </p>
    
    <div class="pt-4">
        <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
            Kembali ke Login
        </a>
    </div>
</div>
@endsection
