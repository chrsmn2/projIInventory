<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    use HasFactory;

    protected $table = 'requesters';

    protected $fillable = [
        'requester_name',
        'departement_id',
        'contact_email',
        'contact_phone',
        'status',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }
}