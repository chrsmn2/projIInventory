@extends('layouts.admin')

@section('title', 'Suppliers')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Suppliers</h1>
            <p class="text-gray-600 mt-1">Manage supplier information</p>
        </div>

        <a href="{{ route('admin.suppliers.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Supplier
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search suppliers..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Search
                    </button>
                </form>
            </div>

            <div class="sm:w-48">
                <form method="GET">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="per_page" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach([5,10,25,50] as $size)
                            <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Code</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Supplier Name</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Phone</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Address</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-mono text-blue-600 font-semibold">{{ $supplier->supplier_code }}</span>
                            </td>

                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $supplier->supplier_name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $supplier->contact_phone }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $supplier->contact_email }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                {{ Str::limit($supplier->address, 40) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($supplier->status === 'active') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                    class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this supplier?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 text-xs font-semibold bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-1">No suppliers found</p>
                                    <p class="text-sm">Get started by creating your first supplier</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-sm text-gray-600">
                Showing <span class="font-semibold">{{ $suppliers->firstItem() ?? 0 }}</span>
                to <span class="font-semibold">{{ $suppliers->lastItem() ?? 0 }}</span>
                of <span class="font-semibold">{{ $suppliers->total() }}</span> suppliers
            </p>

            <div class="flex justify-center">
                {{ $suppliers->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection

