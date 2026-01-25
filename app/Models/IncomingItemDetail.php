<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingItemDetail extends Model
{
    protected $table = 'incoming_item_details';

    protected $fillable = [
        'incoming_item_id',
        'item_id',
        'quantity',
        'unit_id',
        'notes',
    ];

    public function incoming()
    {
        return $this->belongsTo(IncomingItem::class, 'incoming_item_id');
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
