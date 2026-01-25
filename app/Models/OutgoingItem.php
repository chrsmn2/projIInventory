<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $table = 'outgoing_items';
    protected $fillable = [
        'code', 
        'admin_id', 
        'supervisor_id', 
        'outgoing_date', 
        'departement_id', 
        'notes'];

    protected $casts = [
        'outgoing_date' => 'date',
    ];

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

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }
}
