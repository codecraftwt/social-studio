<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class TransactionDetail extends Model
{
    use HasFactory;
    protected $table = 'transaction_details'; // Adjust if your table name differs

    // Specify the fillable fields
    protected $fillable = [
        'user_id',
        'transaction_id',
        'subscription_type',
        'payment_screenshot',
        'payment_date',
        'status', // For approve/reject
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];
}
