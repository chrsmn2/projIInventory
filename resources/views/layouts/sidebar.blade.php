@php
    $role = strtolower(auth()->user()->role ?? '');
@endphp

<!-- SIDEBAR HEADER -->
<div class="h-16 px-6 border-b border-gray-700 flex items-center">
    <h2 class="text-lg font-bold tracking-wide text-white">SIM-IT</h2>
</div>

<!-- SIDEBAR MENU -->
<nav class="px-3 py-6 space-y-2 overflow-y-auto h-[calc(100vh-80px)]">

    @if ($role === 'admin')

        <!-- Dashboard Analitik -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium
                  {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l4-4m-9-5l-2-3m0 0l-7-4 7-4"/>
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>
        <hr class="border-gray-700 my-4">

        <!-- Master Data -->
        <div x-data="{ open: {{ request()->routeIs('admin.categories.*', 'admin.units.*', 'admin.items.*', 'admin.departement.*', 'admin.suppliers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <span class="text-sm">Master Data</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Categories
                </a>
                <a href="{{ route('admin.units.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.units.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m3 6h6m-9-9h3m-3 3H9m6-6h3m-3 3h3M9 7v6m6 0V7"/>
                    </svg>
                    Units
                </a>
                <a href="{{ route('admin.items.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.items.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Items
                </a>
                <a href="{{ route('admin.departement.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.departement.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 14H8m0 0H6m6 0h2m0 0h2m-5 10v2m0-6v6m5-6v6m-5 0h10"/>
                    </svg>
                    Departement
                </a>
                <a href="{{ route('admin.suppliers.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.suppliers.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Vendors
                </a>
            </div>
        </div>
        <hr class="border-gray-700 my-4">

        <!-- TRANSACTIONS -->
        <div x-data="{ open: {{ request()->routeIs('admin.incoming.*', 'admin.outgoing.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span class="text-sm">Transactions</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <a href="{{ route('admin.incoming.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.incoming.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2H3l9-2m0-18v18m0-9L3 7m9 0L3 5m9 0l9-2m-9 2v10m0-9v18"/>
                    </svg>
                    Incoming Items
                </a>
                <a href="{{ route('admin.outgoing.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.outgoing.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-9-2V7l9-2m0 0l9-2v12l-9 2m0 0V5m0 18v-9"/>
                    </svg>
                    Outgoing Items
                </a>
            </div>
        </div>
        <hr class="border-gray-700 my-4">

        <!-- Report -->
        <div x-data="{ open: {{ request()->routeIs('admin.reports.*', 'admin.loans.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-sm">Reports</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <!--<a href="{{ route('admin.loans.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Loan Management
                </a>-->
                <a href="{{ route('admin.reports.stock') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.reports.stock') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Stock Report
                </a>
                <a href="{{ route('admin.reports.movement') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.reports.movement') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Transaction Report
                </a>
            </div>
        </div>

        <hr class="border-gray-700 my-4">

        <!-- Profile -->
        <div x-data="{ open: {{ request()->routeIs('admin.profile.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm">Profile</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <a href="{{ route('admin.profile.edit') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('admin.profile.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Settings
                </a>
            </div>
        </div>
        <hr class="border-gray-700 my-4">

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="block">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-red-700 transition font-medium text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="text-sm">Logout</span>
            </button>
        </form>

    @elseif ($role === 'supervisor')

        <!-- Dashboard -->
        <a href="{{ route('supervisor.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium
                  {{ request()->routeIs('supervisor.dashboard') ? 'bg-gray-700' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l4-4m-9-5l-2-3m0 0l-7-4 7-4"/>
            </svg>
            <span class="text-sm">Dashboard Analitik</span>
        </a>

        <!-- Approval -->
        <div x-data="{ open: {{ request()->routeIs('supervisor.loan-approvals.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">Approval</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <a href="{{ route('supervisor.loan-approvals.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('supervisor.loan-approvals.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Loan Approval
                </a>
            </div>
        </div>

        <!-- Monitoring -->
        <div x-data="{ open: {{ request()->routeIs('supervisor.stock.*', 'supervisor.loan.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-sm">Monitoring</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <a href="{{ route('supervisor.stock.index') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition
                          {{ request()->routeIs('supervisor.stock.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Stock Overview
                </a>
                <a href="{{ route('supervisor.stock.low') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 2v2m0 2v2m0-8a4 4 0 110-8 4 4 0 010 8z"/>
                    </svg>
                    Low Stock Alert
                </a>
                <a href="{{ route('supervisor.loan.monitor') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Loan Monitoring
                </a>
            </div>
        </div>

        <!-- Report -->
        <div x-data="{ open: {{ request()->routeIs('supervisor.reports.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium"
                    :class="{ 'bg-gray-700': open }">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-sm">Report</span>
                </div>
                <svg class="w-4 h-4 transition transform flex-shrink-0" :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-4 mt-2 space-y-1.5 border-l border-gray-600 pl-3">
                <a href="{{ route('supervisor.reports.loan') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Loan Report
                </a>
                <a href="{{ route('supervisor.reports.stock') }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs text-gray-300 rounded hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Stock Report
                </a>
            </div>
        </div>

        <hr class="border-gray-700 my-4">

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-gray-700 transition font-medium
                  {{ request()->routeIs('profile.*') ? 'bg-gray-700' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-sm">Profile</span>
        </a>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="block">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-100 hover:bg-red-700 transition font-medium text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="text-sm">Logout</span>
            </button>
        </form>

    @endif

</nav>
