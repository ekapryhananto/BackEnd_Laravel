<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'release_year',
        'price',
        'total_page',
        'thickness',
        'category_id'
    ];
}
