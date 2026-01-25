@extends('layouts.admin')

@section('title', 'Suppliers')

@section('content')

<div class="w-full bg-white rounded-xl shadow border border-gray-200 overflow-hidden">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-gray-700 to-gray-800">

        <div>
            <h2 class="text-xl font-semibold text-black">Suppliers / Vendors</h2>
            <p class="text-sm text-gray-500">
                Manage all supplier and vendor information
            </p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

            <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name..."
                        class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-300">

                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition font-medium">
                        Search
                    </button>
                </form>

                <form method="GET" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="per_page"
                            onchange="this.form.submit()"
                            class="px-3 py-2 border rounded-lg text-sm focus:outline-none">
                        @foreach([5,10,25,50] as $size)
                            <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <a href="{{ route('admin.suppliers.create') }}"
               class="inline-flex items-center justify-center gap-2
                      px-4 py-2 bg-emerald-500 text-white
                      font-semibold text-sm rounded-lg
                      hover:bg-emerald-600 transition
                      ml-auto mt-2 sm:mt-0 shadow-sm">
                + Add Supplier
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mx-6 mt-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-200 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[1000px] text-sm border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 w-12 text-center border-b">No</th>
                    <th class="px-4 py-3 text-left border-b">Supplier Code</th>
                    <th class="px-4 py-3 text-left border-b">Vendors</th>
                    <th class="px-4 py-3 text-left border-b">Phone</th>
                    <th class="px-4 py-3 text-left border-b">Email</th>
                    <th class="px-4 py-3 text-left border-b">Address</th>
                    <th class="px-4 py-3 text-center border-b">Status</th>
                    <th class="px-4 py-3 text-center border-b w-40">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
                @forelse ($suppliers as $supplier)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-center text-gray-500">
                        {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-800">
                        {{ $supplier->supplier_code }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-800">
                        {{ $supplier->supplier_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $supplier->contact_phone }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 font-medium">
                        {{ $supplier->contact_email }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ Str::limit($supplier->address, 40) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $supplier->status === 'active'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($supplier->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                    class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Update
                                    </a>

                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this supplier?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 text-xs font-semibold bg-red-600 text-white rounded hover:bg-red-700 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">
                        No suppliers data found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
            <div class="text-sm text-gray-600">
                Showing <span class="font-bold">{{ $suppliers->firstItem() ?? 0 }}</span>
                to <span class="font-bold">{{ $suppliers->lastItem() ?? 0 }}</span>
                of <span class="font-bold">{{ $suppliers->total() }}</span> suppliers
            </div>

            <div class="flex items-center gap-1">
                <a href="{{ $suppliers->previousPageUrl() ?? '#' }}"
                   class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100 {{ $suppliers->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                    <<
                </a>
                <a href="{{ $suppliers->nextPageUrl() ?? '#' }}"
                   class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100 {{ !$suppliers->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">
                    >>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

