@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Items</p>
        <p class="text-2xl font-bold mt-2">120</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Categories</p>
        <p class="text-2xl font-bold mt-2">8</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Incoming</p>
        <p class="text-2xl font-bold mt-2">30</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Outgoing</p>
        <p class="text-2xl font-bold mt-2">15</p>
    </div>

</div>
@endsection
