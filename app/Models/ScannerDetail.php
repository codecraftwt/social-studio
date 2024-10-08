<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScannerDetail extends Model
{
    use HasFactory;
    protected $table = 'scanner_details';

    protected $fillable = [
        'payment_method',
        'OR_code',
        'status',
    ];
}
