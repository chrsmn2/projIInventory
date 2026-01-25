@extends('layouts.admin')

@section('title', 'Loan Report')

@section('content')

<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-gray-700 to-gray-800 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Loan Report</h2>
            <p class="text-sm text-blue-100">
                Detailed report of all loan transactions
            </p>
        </div>

        <!-- RIGHT SIDE: Filter & Export -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

            <!-- FILTER -->
            <form method="GET" class="flex gap-2 flex-wrap">
                <select name="status"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                </select>

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    Filter
                </button>
            </form>

            <!-- EXPORT BUTTON -->
            <button onclick="window.print()" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                📥 Export PDF
            </button>

        </div>

    </div>

    <!-- TABLE SECTION -->
    <div class="overflow-x-auto">
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

