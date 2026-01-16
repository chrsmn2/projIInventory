<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    use HasFactory;

    protected $table = 'incoming_items';
    protected $fillable = [
        'admin_id', 
        'incoming_date',
        'supplier',  // TAMBAHKAN INI
        'source', 
        'notes'
    ];

    protected $casts = [
        'incoming_date' => 'date',
    ];

    // Relasi ke detail
    public function details()
    {
        return $this->hasMany(IncomingItemDetail::class, 'incoming_item_id');
    }

    // Relasi ke Admin (User)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
