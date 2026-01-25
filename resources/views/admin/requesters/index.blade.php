@extends('layouts.admin')

@section('title', 'Requesters')

@section('content')

<div class="w-full bg-white rounded-xl shadow border border-gray-200 overflow-hidden">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800">
        <div>
            <h2 class="text-xl font-semibold text-white">Requesters / Borrowers</h2>
            <p class="text-sm text-gray-300">Manage all system requesters and their department information</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                {{-- Search Form --}}
                <form method="GET" action="{{ route('admin.requesters.index') }}" class="flex gap-2 flex-1 sm:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name..."
                        class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition font-medium">
                        Search
                    </button>
                </form>

                {{-- Per Page Dropdown --}}
                <form method="GET" action="{{ route('admin.requesters.index') }}" class="flex items-center">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border rounded-lg text-sm focus:outline-none text-gray-700 cursor-pointer">
                        @foreach([5,10,25,50] as $size)
                            <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <a href="{{ route('admin.requesters.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500 text-white font-semibold text-sm rounded-lg hover:bg-emerald-600 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Requester
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mx-6 mt-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-200 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Section --}}
    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[1000px] text-sm border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 w-12 text-center border-b">No</th>
                    <th class="px-4 py-3 text-left border-b">Requester Name</th>
                    <th class="px-4 py-3 text-left border-b">Department</th>
                    <th class="px-4 py-3 text-left border-b">Phone</th>
                    <th class="px-4 py-3 text-left border-b">Email</th>
                    <th class="px-4 py-3 text-center border-b">Status</th>
                    <th class="px-4 py-3 text-center border-b w-40">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
                @forelse ($requesters as $requester)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-center text-gray-500">
                        {{ ($requesters->currentPage() - 1) * $requesters->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-800 uppercase">
                        {{ $requester->requester_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 italic">
                        {{ $requester->departement->departement_name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $requester->contact_phone ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 font-medium">
                        {{ $requester->contact_email ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $requester->status === 'active' 
                                ? 'bg-green-100 text-green-700' 
                                : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($requester->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.requesters.edit', $requester->id) }}"
                               class="px-3 py-1.5 text-xs font-semibold bg-blue-100 text-gray-700 rounded hover:bg-blue-600 hover:text-white transition shadow-sm">
                                Edit
                            </a>
                            <form action="{{ route('admin.requesters.destroy', $requester->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this requester?');" class="inline">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-100 text-red-600 rounded hover:bg-red-600 hover:text-white transition shadow-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">
                        No requesters data found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
            <div class="text-sm text-gray-600">
                Showing <span class="font-bold text-gray-800">{{ $requesters->firstItem() ?? 0 }}</span> 
                to <span class="font-bold text-gray-800">{{ $requesters->lastItem() ?? 0 }}</span> 
                of <span class="font-bold text-gray-800">{{ $requesters->total() }}</span> requesters
            </div>

            <div class="requester-pagination">
                {{-- Link pagination dengan style tailwind bawaan laravel --}}
                {{ $requesters->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
