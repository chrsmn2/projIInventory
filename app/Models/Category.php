<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Nama tabel di database
    protected $fillable = [
        'name',
        'description'
    ];

    // Relasi: 1 Category bisa punya banyak Item
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
