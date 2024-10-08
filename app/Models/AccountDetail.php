<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    protected $table = 'account_details';

    protected $fillable = [
        'account_name',
        'account_number',
        'ifsc_code',
        'bank_name',
        'status',
    ];
}
