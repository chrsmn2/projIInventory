<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItemDetail extends Model
{
    use HasFactory;

    protected $table = 'incoming_item_details';
    protected $fillable = [
        'incoming_item_id', 
        'item_id', 
        'quantity', 
        'notes'
    ];
    public function incomingItem()
    {
        return $this->belongsTo(IncomingItem::class, 'incoming_item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
