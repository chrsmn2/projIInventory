<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $computer = Category::where('name', 'Computer')->first();
        $printer = Category::where('name', 'Printer')->first();
        $mouse = Category::where('name', 'Mouse')->first();

        // Tambahkan barang
        Item::create([
            'item_code' => 'C001',
            'item_name' => 'Dell Laptop',
            'category_id' => $computer->id,
            'stock' => 10,
            'min_stock' => 2,
            'condition' => 'normal'
        ]);

        Item::create([
            'item_code' => 'P001',
            'item_name' => 'HP LaserJet',
            'category_id' => $printer->id,
            'stock' => 5,
            'min_stock' => 1,
            'condition' => 'normal'
        ]);

        Item::create([
            'item_code' => 'M001',
            'item_name' => 'Logitech Mouse',
            'category_id' => $mouse->id,
            'stock' => 15,
            'min_stock' => 5,
            'condition' => 'normal'
        ]);
    }
}
