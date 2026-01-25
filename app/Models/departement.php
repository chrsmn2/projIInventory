<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    protected $table = 'departement';
        
    protected $fillable = [
        'code_dept',
        'departement_name',
        'is_active',
    ];

    public function requesters()
    {   
        return $this->hasMany(Requester::class, 'departement_id');
    }
}
