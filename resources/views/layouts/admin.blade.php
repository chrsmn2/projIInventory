<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    @vite('resources/css/app.css')

    <!-- Alpine.js (WAJIB) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --color-dark: #1f2937;
            --color-darker: #111827;
        }
    </style>
</head>

<body class="bg-gray-100">

<div x-data="{ 
    sidebarOpen: false,
    refreshProfilePhoto: function() {
        // Add timestamp to bust cache
        const timestamp = new Date().getTime();
        const profileImg = document.getElementById('profile-photo-header');
        if (profileImg && profileImg.src.includes('storage/profile-photos')) {
            const baseUrl = profileImg.src.split('?')[0];
            profileImg.src = baseUrl + '?' + timestamp;
        }
    }
}" x-init="window.refreshProfilePhoto = refreshProfilePhoto" class="flex h-screen overflow-hidden">

    <!-- OVERLAY (HP / TABLET) -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white z-50 transform
               -translate-x-full lg:translate-x-0
               transition-transform duration-300"
        :class="{ 'translate-x-0': sidebarOpen }"
    >
        @include('layouts.sidebar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col lg:ml-64">

        <!-- TOPBAR / NAVBAR -->
        <header class="h-16 bg-gray-800 text-white border-b border-gray-700 flex items-center justify-between px-4 lg:px-6 shadow-md">
            <!-- LEFT: HAMBURGER + TITLE -->
            <div class="flex items-center gap-4">
                <!-- HAMBURGER (ALWAYS VISIBLE) -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="text-white focus:outline-none hover:bg-gray-700 p-1 rounded transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="text-lg font-medium text-white">
                    @yield('title', 'Dashboard')
                </h1>
            </div>

            <!-- RIGHT: PROFILE -->
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline text-sm">{{ auth()->user()->name ?? 'Admin' }}</span>
                @if(auth()->user()->profile_photo)
                    <img id="profile-photo-header" src="{{ asset('storage/' . auth()->user()->profile_photo) }}?{{ time() }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-9 h-9 rounded-full object-cover border-2 border-gray-700 cursor-pointer hover:opacity-80 transition" 
                         onclick="window.location.href='{{ route('admin.profile.edit') }}'">
                @else
                    <div class="w-9 h-9 rounded-full bg-gray-600 flex items-center justify-center border-2 border-gray-700 cursor-pointer hover:bg-gray-500 transition" onclick="window.location.href='{{ route('admin.profile.edit') }}'">
                        <svg class="h-5 w-5 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.7 15.25c4.967 0 9.311 2.684 11.3 6.75z" />
                            <path d="M16.5 5.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                    </div>
                @endif
            </div>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>
</div>

<style>
    .bg-navy { background-color: #1e3a8a; }
    .bg-navy-dark { background-color: #162c61; }
    .border-navy-dark { border-color: #162c61; }
</style>

</body>
</html>
