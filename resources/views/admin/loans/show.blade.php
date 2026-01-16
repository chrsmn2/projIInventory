@extends('layouts.admin')

@section('title', 'Loan Detail')

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Loan Detail</h2>
            <p class="text-sm text-indigo-100">View loan request and items</p>
        </div>

        <a href="{{ route('admin.loans.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700
                  rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
            &larr; Back
        </a>
    </div>

    <!-- CONTENT -->
    <div class="p-6 space-y-6">

        <!-- LOAN INFO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Loan Information</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Code:</span>
                        <span class="font-semibold text-indigo-600">{{ $loan->loan_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-semibold">{{ date('d M Y', strtotime($loan->loan_date)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold">
                            @if($loan->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-bold">Pending</span>
                            @elseif($loan->status == 'approved')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">Approved</span>
                            @elseif($loan->status == 'rejected')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">Rejected</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Requester Information</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-semibold">{{ $loan->requester_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Department:</span>
                        <span class="font-semibold">{{ $loan->department }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Purpose:</span>
                        <span class="font-semibold">{{ $loan->purpose ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Loaned Items</h3>
            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold border-b">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Item Name</th>
                            <th class="px-4 py-3 text-center">Qty Request</th>
                            <th class="px-4 py-3 text-center">Qty Returned</th>
                            <th class="px-4 py-3 text-center">Condition Out</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($loan->details as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $detail->item->item_name }}</td>
                            <td class="px-4 py-3 text-center">{{ $detail->quantity }}</td>
                            <td class="px-4 py-3 text-center">{{ $detail->returned_quantity ?? 0 }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">
                                    {{ ucfirst($detail->condition_out ?? 'good') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    @if($detail->status == 'borrowed') bg-orange-100 text-orange-700
                                    @elseif($detail->status == 'returned') bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($detail->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                No items found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection