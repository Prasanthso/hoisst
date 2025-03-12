<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overhead extends Model
{
    use HasFactory;

    protected $table = 'overheads';

    protected $fillable = [
        'name',
        'ohcode',
        'uom',
        'category_id1',
        'category_id2',
        'category_id3',
        'category_id4',
        'category_id5',
        'category_id6',
        'category_id7',
        'category_id8',
        'category_id9',
        'category_id10',
        'price',
        'update_frequency',
        'price_update_frequency',
        'price_threshold',
        // 'hsncode',
        'itemweight',
        'itemType_id',
        // 'tax',
    ];
}
