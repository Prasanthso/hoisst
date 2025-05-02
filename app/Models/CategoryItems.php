<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class CategoryItems extends Model
{
    use HasFactory;

    protected $table = 'categoryitems';

    protected $fillable = [
        'categoryId',
        'itemname',
        'description',
        'created_user',
        'status',
        'store_id',
    ];

    public static function rmCategoryItem()
    {
        $rmCategoryItems = DB::table('categoryitems')->where('categoryId', 1)->where('status', 'active')->get();
        return $rmCategoryItems;
    }

    public static function pmCategoryItem()
    {
        $pmCategoryItem = DB::table('categoryitems')->where('categoryId', 2)->where('status', 'active')->get();
        return $pmCategoryItem;
    }

    public static function ohCategoryItem()
    {
        $ohCategoryItem = DB::table('categoryitems')->where('categoryId', 3)->where('status', 'active')->get();
        return $ohCategoryItem;
    }

    public static function pdCategoryItem()
    {
        $pdCategoryItem = DB::table('categoryitems')->where('categoryId', 4)->where('status', 'active')->get();
        return $pdCategoryItem;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }
}
