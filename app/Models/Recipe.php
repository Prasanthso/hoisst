<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipedetails';

    protected $fillable = [
        'product_id',
        'description',
        'instructions',
        'video_path'
    ];
}
