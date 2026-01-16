@extends('layouts.admin')

@section('title', 'Data Loans')

@section('content')
<div class="space-y-6">

    <!-- HEADER SECTION -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Loan Management</h1>
            <p class="text-gray-600 mt-1">Track and manage all borrowing requests</p>
        </div>
        <a href="{{ route('admin.loans.create') }}"
           class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold
                  hover:bg-indigo-700 transition duration-200 inline-flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Loan Request
        </a>
    </div>

    <!-- STATS CARDS -->
    @php
        $pendingCount = $loans->where('status', 'pending')->count();
        $approvedCount = $loans->where('status', 'approved')->count();
        $rejectedCount = $loans->where('status', 'rejected')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Approved Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $approvedCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Rejected Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $rejectedCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Search by loan code or requester name..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold
                           hover:bg-indigo-700 transition duration-200 whitespace-nowrap">
                Search
            </button>
            @if($search)
            <a href="{{ route('admin.loans.index') }}"
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold
                      hover:bg-gray-300 transition duration-200 text-center whitespace-nowrap">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- TABLE SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Loan Code</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Requester</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Department</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($loans as $index => $loan)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <!-- NO -->
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            {{ ($loans->currentPage() - 1) * $loans->perPage() + $index + 1 }}
                        </td>

                        <!-- CODE -->
                        <td class="px-6 py-4">
                            <span class="font-mono text-indigo-600 font-semibold">{{ $loan->loan_code }}</span>
                        </td>

                        <!-- DATE -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ date('d M Y', strtotime($loan->loan_date)) }}
                        </td>

                        <!-- REQUESTER -->
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            {{ $loan->requester_name ?? 'N/A' }}
                        </td>

                        <!-- DEPARTMENT -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ $loan->department ?? '-' }}
                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-4 text-center">
                            @if($loan->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                           bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    Pending
                                </span>
                            @elseif($loan->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                           bg-green-50 text-green-700 border border-green-200">
                                    Approved
                                </span>
                            @elseif($loan->status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                           bg-red-50 text-red-700 border border-red-200">
                                    Rejected
                                </span>
                            @endif
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- VIEW -->
                                <a href="{{ route('admin.loans.show', $loan->id) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200"
                                   title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <!-- EDIT -->
                                <a href="{{ route('admin.loans.edit', $loan->id) }}"
                                   class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition duration-200"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                @if($loan->status == 'pending')
                                    <!-- APPROVE -->
                                    <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-200"
                                                title="Approve">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- REJECT -->
                                    <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to reject this request?')">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-200"
                                                title="Reject">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                <!-- DELETE -->
                                <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this loan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200"
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-semibold mb-1">No Loan Requests</p>
                                <p class="text-sm">Create a new loan request to get started</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($loans->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-semibold">{{ $loans->firstItem() }}</span> to 
                    <span class="font-semibold">{{ $loans->lastItem() }}</span> of 
                    <span class="font-semibold">{{ $loans->total() }}</span> results
                </div>

                <div>
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
