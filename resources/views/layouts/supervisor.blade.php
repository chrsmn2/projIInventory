<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supervisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-md flex flex-col">
        <div class="p-6 font-bold text-xl border-b">Supervisor Panel</div>
        <nav class="flex-1 p-4">
            <a href="{{ route('supervisor.dashboard') }}" class="block py-2 px-4 hover:bg-gray-200 rounded">Dashboard</a>
            <a href="{{ route('supervisor.loan-approvals.index') }}" class="block py-2 px-4 hover:bg-gray-200 rounded">Approve Loans</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex-1 p-6 overflow-auto">
        @yield('content')
    </div>

</body>
</html>
