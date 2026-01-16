<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';
    protected $fillable = [
        'loan_code',
        'loan_date',
        'return_date',
        'requester_name',
        'department',
        'purpose',
        'status',
        'admin_id',
    ];

    protected $casts = [
        'loan_date' => 'datetime',
    ];

    //RELATIONSHIP

    // Admin yang membuat loan
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id', 'name');
    }

    // Detail barang yang dipinjam
    public function details()
    {
        return $this->hasMany(LoanDetail::class, 'loan_id');
    }
}
