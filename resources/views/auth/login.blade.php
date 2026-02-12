@extends('layouts.guest')

@section('title', 'Login')

@section('content')
@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
    <ul class="text-red-800 text-sm space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Email
        </label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
        @error('email')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Password
        </label>
        <input
            type="password"
            name="password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
        @error('password')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
    </div>

    <div class="flex items-center">
        <input
            type="checkbox"
            name="remember"
            id="remember"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded"
        >
        <label for="remember" class="ml-2 block text-sm text-gray-700">
            Ingat Saya
        </label>
    </div>

    <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition"
    >
        Login
    </button>
</form>

@if (Route::has('password.request'))
<div class="text-center mt-6">
    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
        Lupa Password?
    </a>
</div>
@endif
@endsection
