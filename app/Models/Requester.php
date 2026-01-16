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
        'department',
        'contact_phone',
        'contact_email',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }
}