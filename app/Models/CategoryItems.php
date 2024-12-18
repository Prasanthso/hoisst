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
        'created_user'
    ];

    public static function getRawMaterialsCategory()
    {
        return self::where('categoryId', 1)->get();
    }

    public static function getPackingMaterialCategory()
    {
        return self::where('categoryId', 2)->get();
    }

    public static function getOverheadsCategory()
    {
        return self::where('categoryId', 3)->get();
    }

    public static function getProductsCategory()
    {
        return self::where('categoryId', 4)->get();
    }
}
