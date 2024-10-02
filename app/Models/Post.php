<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'post_image',
        'post_pdf',
        'post_title',
        'post_explanation',
        'header_footer_access',
        'category_id',
        'sub_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
