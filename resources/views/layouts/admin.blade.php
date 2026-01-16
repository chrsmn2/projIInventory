<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    @vite('resources/css/app.css')

    <!-- Alpine.js (WAJIB) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    <!-- OVERLAY (HP / TABLET) -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-white z-50 transform
               -translate-x-full lg:translate-x-0
               transition-transform duration-300"
        :class="{ 'translate-x-0': sidebarOpen }"
    >
        @include('layouts.sidebar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col lg:ml-64">

        <!-- TOPBAR -->
        <header class="h-16 bg-white border-b flex items-center px-4 gap-4">
            <!-- HAMBURGER -->
            <button
                @click="sidebarOpen = true"
                class="lg:hidden text-gray-700 focus:outline-none"
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

            <h1 class="text-lg font-semibold text-gray-800">
                @yield('title', 'Dashboard')
            </h1>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
