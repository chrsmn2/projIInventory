<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'supplier_name',
        'contact_phone',
        'contact_email',
        'address',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at'=> 'datetime',
    ];

    public function incomings()
    {
        return $this->hasMany(IncomingItem::class, 'supplier_id');
    }
}
