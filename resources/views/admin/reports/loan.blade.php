@extends('layouts.admin')

@section('title', 'Loan Report')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Loan Report</h1>
            <p class="text-gray-600 mt-1">Detailed report of all loan transactions</p>
        </div>

        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors hidden-print">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export PDF
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="sm:w-48">
                <form method="GET" class="flex gap-2">
                    <select name="status" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1200px] text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Loan Code</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Requester</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Department</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Items</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Return Date</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Admin</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($loans as $loan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ ($loans->currentPage() - 1) * $loans->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-mono text-indigo-600 font-semibold">{{ $loan->loan_code }}</span>
                            </td>

                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ date('d M Y', strtotime($loan->loan_date)) }}
                            </td>

                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $loan->requester_name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $loan->department ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $loan->details->count() }} item{{ $loan->details->count() != 1 ? 's' : '' }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $loan->return_date ? date('d M Y', strtotime($loan->return_date)) : '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($loan->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($loan->status == 'approved') bg-green-100 text-green-800
                                    @elseif($loan->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($loan->status == 'returned') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $loan->admin->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.loans.show', $loan->id) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-1">No loan records found</p>
                                    <p class="text-sm">Try adjusting your filter criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-sm text-gray-600">
                Showing <span class="font-semibold">{{ $loans->firstItem() }}</span>
                to <span class="font-semibold">{{ $loans->lastItem() }}</span>
                of <span class="font-semibold">{{ $loans->total() }}</span> loan records
            </p>

            <div class="flex justify-center">
                {{ $loans->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- PRINT STYLES -->
<style>
    @media print {
        .hidden-print {
            display: none !important;
        }

        body {
            background: white;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #333;
        }
    }
</style>

@endsection
        <table class="w-full text-sm text-left">

            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Loan Code</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Loan Date</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Return Date</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Borrower</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Department</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Purpose</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Items</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Admin</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse ($loans as $loan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-700">{{ $loop->iteration + ($loans->currentPage() - 1) * $loans->perPage() }}</td>

                    <td class="px-6 py-3 font-semibold text-gray-900">
                        <a href="{{ route('admin.loans.show', $loan->id) }}" class="text-gray-700 hover:underline">
                            {{ $loan->loan_code }}
                        </a>
                    </td>

                    <td class="px-6 py-3 text-gray-700">
                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}
                    </td>

                    <td class="px-6 py-3 text-gray-700">
                        {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}
                    </td>

                    <td class="px-6 py-3 text-gray-700">{{ $loan->borrower_name }}</td>

                    <td class="px-6 py-3 text-gray-700">{{ $loan->department ?? '-' }}</td>

                    <td class="px-6 py-3 text-gray-700 max-w-xs truncate">
                        {{ $loan->purpose ?? '-' }}
                    </td>

                    <td class="px-6 py-3 text-center">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                            {{ $loan->details->count() }} item(s)
                        </span>
                    </td>

                    <td class="px-6 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold inline-block
                            @if($loan->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($loan->status == 'approved') bg-green-100 text-green-700
                            @elseif($loan->status == 'rejected') bg-red-100 text-red-700
                            @elseif($loan->status == 'returned') bg-blue-100 text-blue-700
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-3 text-gray-700">
                        {{ $loan->admin->name ?? '-' }}
                    </td>

                    <td class="px-6 py-3 text-center">
                        <a href="{{ route('admin.loans.show', $loan->id) }}"
                           class="inline-flex items-center justify-center
                                  px-3 py-1.5 bg-blue-500 text-white text-xs rounded
                                  hover:bg-blue-600 transition">
                            View
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-8 text-center text-gray-500">
                        <p class="text-base">No loan records found</p>
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Showing <span class="font-semibold">{{ $loans->firstItem() ?? 0 }}</span>
            to <span class="font-semibold">{{ $loans->lastItem() ?? 0 }}</span>
            of <span class="font-semibold">{{ $loans->total() }}</span> results
        </div>

        <div class="flex gap-2">
            {{ $loans->links('pagination::tailwind') }}
        </div>
    </div>

</div>

<!-- PRINT STYLES -->
<style>
    @media print {
        .hidden-print {
            display: none !important;
        }

        body {
            background: white;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #333;
        }
    }
</style>

@endsection

