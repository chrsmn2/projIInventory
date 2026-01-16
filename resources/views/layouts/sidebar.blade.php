@php
    $role = auth()->user()->role ?? null;
@endphp

<div class="h-full flex flex-col bg-white border-r">

    <!-- LOGO -->
    <div class="h-16 flex items-center px-6 border-b">
        <span class="text-lg font-bold text-gray-800">
            SIM - IT
        </span>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

        <!-- ================= ADMIN ================= -->
        @if ($role === 'admin')

            <!-- DASHBOARD -->
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-2 rounded-lg font-medium
               {{ request()->routeIs('admin.dashboard')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Dashboard
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400 px-4">Master Data</div>

            <a href="{{ route('admin.categories.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.categories.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Categories
            </a>

            <a href="{{ route('admin.items.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.items.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Items
            </a>

             <a href="{{ route('admin.suppliers.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.suppliers.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Suppliers/Vendors
            </a>

            <a href="{{ route('admin.requesters.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.requesters.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Requesters/Borrowers
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400 px-4">Transactions</div>

            <a href="{{ route('admin.incoming.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.incoming.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Incoming Items
            </a>

            <a href="{{ route('admin.loans.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.loans.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Borrowed Items
            </a>

            <a href="{{ route('admin.outgoing.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.outgoing.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Outgoing Items
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400 px-4">Reports</div>
    
            <!--
            <a href="{{ route('admin.reports.stock') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                Stock Report
            </a>

            <a href="{{ route('admin.reports.loan') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                Loan Report
            </a> -->

        @endif

        <!-- ================= SUPERVISOR ================= -->
        @if ($role === 'supervisor')

            <!-- DASHBOARD -->
            <a href="{{ route('supervisor.dashboard') }}"
               class="block px-4 py-2 rounded-lg font-medium
               {{ request()->routeIs('supervisor.dashboard')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Dashboard
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400 px-4">Approval</div>

            <a href="{{ route('supervisor.loan-approvals.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('supervisor.loan-approvals.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Loan Approval
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400 px-4">Monitoring</div>

            <a href="{{ route('supervisor.stock.index') }}"
               class="block px-4 py-2 rounded-lg
               {{ request()->routeIs('supervisor.stock.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                Stock Overview
            </a>

            <a href="{{ route('supervisor.stock.low') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                Low Stock Alert
            </a>

            <a href="{{ route('supervisor.loan.monitor') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                Loan Monitoring
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400 px-4">Reports</div>

            <a href="{{ route('supervisor.reports.loan') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                Loan Report
            </a>

            <a href="{{ route('supervisor.reports.stock') }}"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                Stock Report
            </a>

        @endif

    </nav>

    <!-- LOGOUT -->
    <div class="px-4 py-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="w-full text-left px-4 py-2 rounded-lg
                       text-red-600 hover:bg-red-50">
                Logout
            </button>
        </form>
    </div>

</div>
