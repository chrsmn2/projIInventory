<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use Facade
use Illuminate\Support\Facades\DB;

echo "\n========== USERS IN DATABASE ==========\n";
$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
}

echo "\n========== INCOMING ITEMS BY ADMIN ==========\n";
$incoming = DB::table('incoming_items')
    ->select(DB::raw('admin_id, COUNT(*) as count'))
    ->groupBy('admin_id')
    ->get();
foreach ($incoming as $row) {
    $adminName = DB::table('users')->where('id', $row->admin_id)->value('name') ?? 'Unknown';
    echo "Admin ID: {$row->admin_id} (Name: {$adminName}) | Count: {$row->count}\n";
}

echo "\n========== OUTGOING ITEMS BY ADMIN ==========\n";
$outgoing = DB::table('outgoing_items')
    ->select(DB::raw('admin_id, COUNT(*) as count'))
    ->groupBy('admin_id')
    ->get();
foreach ($outgoing as $row) {
    $adminName = DB::table('users')->where('id', $row->admin_id)->value('name') ?? 'Unknown';
    echo "Admin ID: {$row->admin_id} (Name: {$adminName}) | Count: {$row->count}\n";
}

echo "\n========== OUTGOING ITEMS BY SUPERVISOR ==========\n";
$supervisor = DB::table('outgoing_items')
    ->select(DB::raw('supervisor_id, COUNT(*) as count'))
    ->groupBy('supervisor_id')
    ->get();
foreach ($supervisor as $row) {
    $supName = DB::table('users')->where('id', $row->supervisor_id)->value('name') ?? 'Unknown';
    echo "Supervisor ID: {$row->supervisor_id} (Name: {$supName}) | Count: {$row->count}\n";
}

echo "\n";
