<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItemDetail extends Model
{
    use HasFactory;

    protected $table = 'outgoing_item_details';
    protected $fillable = [
        'outgoing_item_id', 
        'item_id', 
        'quantity', 
        'unit_id',
        'condition'
    ];

    public function outgoingItem()
    {
        return $this->belongsTo(OutgoingItem::class, 'outgoing_item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
