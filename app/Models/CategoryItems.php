<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItems extends Model
{
    use HasFactory;

    protected $table = 'categoryitems';

    protected $fillable = [
        'categoryId',
        'itemname',
        'description',
        'created_user',
    ];
}
