<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $table = 'outgoing_items';
    protected $fillable = ['admin_id', 'supervisor_id', 'outgoing_date', 'destination', 'status', 'notes'];

    public function details()
    {
        return $this->hasMany(OutgoingItemDetail::class, 'outgoing_item_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
