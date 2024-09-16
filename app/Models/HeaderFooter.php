<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderFooter extends Model
{
    use HasFactory;
    protected $table = 'header_footer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'header_path',
        'footer_path',
    ];
    public $timestamps = true;
}
