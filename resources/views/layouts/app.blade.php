<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">

<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

    <!-- OVERLAY (MOBILE) -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-40 z-40 md:hidden"
        x-transition
    ></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50
               transform transition-transform duration-300
               -translate-x-full md:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        @include('layouts.sidebar')
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col md:ml-64">

        <!-- TOPBAR -->
        <header class="h-16 bg-white shadow flex items-center px-4">
            <!-- Hamburger -->
            <button
                @click="sidebarOpen = true"
                class="md:hidden p-2 rounded bg-gray-200"
            >
                ☰
            </button>

            <h1 class="ml-4 text-lg font-semibold">
                @yield('title', 'Dashboard')
            </h1>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</div>

</body>
</html>
