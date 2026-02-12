<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $fillable = [
        'item_code',
        'item_name',
        'category_id',
        'condition',
        'min_stock',
        'stock',
        'unit_id',
        'description',
    ];

    // Relasi: Item milik satu Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Item memiliki satu Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi ke transaksi detail
    public function incomingDetails()
    {
        return $this->hasMany(IncomingItemDetail::class, 'item_id');
    }

    public function outgoingDetails()
    {
        return $this->hasMany(OutgoingItemDetail::class, 'item_id');
    }

    public function loanDetails()
    {
        return $this->hasMany(LoanDetail::class, 'item_id');
    }

        public function conditionBadge()
    {
        return match (strtolower(trim($this->condition))) {
            'good'   => 'bg-green-100 text-green-800',
            'damaged'  => 'bg-red-100 text-red-800',
            default    => 'bg-yellow-100 text-yellow-800',
        };
    }

}
