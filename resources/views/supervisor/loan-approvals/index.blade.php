@extends('layouts.supervisor')

@section('title', 'Loan Approvals')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Loan Approvals</h1>
            <p class="text-gray-600 mt-1">Review and approve loan requests</p>
        </div>
    </div>

    <!-- STATS -->
    @php
        $pendingCount = $approvals->where('status', 'pending')->count();
        $approvedCount = $approvals->where('status', 'approved')->count();
        $rejectedCount = $approvals->where('status', 'rejected')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Reviews</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingCount }}</p>
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
                    <p class="text-gray-600 text-sm font-medium">Approved</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $approvedCount }}</p>
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
                    <p class="text-gray-600 text-sm font-medium">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejectedCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Search by code or requester..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <select name="status"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold
                           hover:bg-indigo-700 transition duration-200 whitespace-nowrap">
                Filter
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Loan Code</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Requester</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Department</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($approvals as $index => $approval)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            {{ ($approvals->currentPage() - 1) * $approvals->perPage() + $index + 1 }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="font-mono text-indigo-600 font-semibold">{{ $approval->loan->loan_code }}</span>
                        </td>

                        <td class="px-6 py-4 text-gray-900">
                            {{ $approval->loan->requester_name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $approval->loan->department }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ date('d M Y', strtotime($approval->loan->loan_date)) }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($approval->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                           bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    Pending
                                </span>
                            @elseif($approval->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                           bg-green-50 text-green-700 border border-green-200">
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                           bg-red-50 text-red-700 border border-red-200">
                                    Rejected
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('supervisor.loan-approvals.show', $approval->id) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200"
                                   title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @if($approval->status == 'pending')
                                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-200" 
                                            onclick="approveModal({{ $approval->id }})" title="Approve">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>

                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-200" 
                                            onclick="rejectModal({{ $approval->id }})" title="Reject">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
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
                                <p class="text-lg font-semibold mb-1">No Approvals</p>
                                <p class="text-sm">No loan approvals to review</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($approvals->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-semibold">{{ $approvals->firstItem() }}</span> to 
                    <span class="font-semibold">{{ $approvals->lastItem() }}</span> of 
                    <span class="font-semibold">{{ $approvals->total() }}</span> results
                </div>

                <div>
                    {{ $approvals->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

</div>

<!-- APPROVE MODAL -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Approve Loan</h3>
        </div>
        <form id="approveForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3" placeholder="Add approval notes..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeApproveModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold
                               hover:bg-gray-50 transition duration-200">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold
                               hover:bg-green-700 transition duration-200">
                    Approve
                </button>
            </div>
        </form>
    </div>
</div>

<!-- REJECT MODAL -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Reject Loan</h3>
        </div>
        <form id="rejectForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="notes" rows="3" placeholder="Explain why this loan is being rejected..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold
                               hover:bg-gray-50 transition duration-200">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold
                               hover:bg-red-700 transition duration-200">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function approveModal(id) {
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveForm').action = `/supervisor/loan-approvals/${id}/approve`;
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function rejectModal(id) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectForm').action = `/supervisor/loan-approvals/${id}/reject`;
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal saat ESC ditekan
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>
@endsection