<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIM-IT</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-50">

<div x-data="{ sidebarOpen: false }" class="flex h-screen">

    <!-- OVERLAY (Mobile) -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-blue-900 text-white z-50 overflow-y-auto transform
               -translate-x-full lg:translate-x-0 lg:relative lg:transform-none
               transition-transform duration-300"
        :class="{ 'translate-x-0': sidebarOpen }"
    >
        @include('layouts.sidebar')
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- NAVBAR / HEADER -->
        <header class="h-16 bg-blue-900 text-white border-b border-blue-800 flex items-center justify-between px-4 lg:px-6 shadow">
            <!-- LEFT: HAMBURGER -->
            <div class="flex items-center gap-4">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden text-white hover:bg-blue-800 p-2 rounded transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-lg font-semibold hidden md:block">@yield('title', 'Dashboard')</h1>
            </div>

            <!-- RIGHT: USER PROFILE -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-xs text-blue-200">{{ ucfirst(auth()->user()->role ?? 'user') }}</p>
                </div>
                <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/40' }}" 
                     alt="avatar" 
                     class="w-10 h-10 rounded-full object-cover border-2 border-white">
            </div>
        </header>

        <!-- CONTENT AREA -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6">
            <!-- Flash Messages -->
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ $message }}
                </div>
            @endif

            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</div>

<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    .bg-blue-900 { background-color: #1e3a8a; }
    .border-blue-800 { border-color: #1e40af; }
    .hover\:bg-blue-800:hover { background-color: #1e40af; }
</style>

</body>
</html>
