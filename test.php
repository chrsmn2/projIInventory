<?php

require_once 'vendor/autoload.php';

use App\Models\Item;

$prefix = 'CAT-001-ITM-';
$last = Item::where('item_code', 'like', $prefix.'%')->latest('id')->first();

if ($last) {
    $lastNumberString = str_replace($prefix, '', $last->item_code);
    echo "Last number string: " . $lastNumberString . "\n";
    $number = intval($lastNumberString) + 1;
    echo "Next number: " . $number . "\n";
    $code = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    echo "New code: " . $code . "\n";
} else {
    echo "No last item found\n";
}