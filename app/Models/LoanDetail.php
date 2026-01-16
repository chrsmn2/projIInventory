<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    use HasFactory;

    protected $table = 'loan_details';

    protected $fillable = [
        'loan_id',
        'item_id',
        'quantity',
        'returned_quantity',
        'condition_out',
        'condition_in',
        'status',
        'supervisor_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
