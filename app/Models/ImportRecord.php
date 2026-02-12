<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportRecord extends Model
{
    use HasFactory;

    protected $table = 'import_records';

    protected $fillable = [
        'filename',
        'hash',
        'user_id',
    ];
}
