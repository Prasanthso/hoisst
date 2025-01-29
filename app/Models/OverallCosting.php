<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallCosting extends Model
{
    use HasFactory;

    // Define the table name (optional, if your table is named differently)
    protected $table = 'overall_costings';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'productId',
        'rm_cost_unit',
        'pm_cost_unit',
        'rm_pm_cost',
        'overhead',
        'rm_sg_mrp',
        'pm_sg_mrp',
        'sg_mrp',
        'sg_margin',
        'oh_amt',
        'total_cost',
        'sell_rate',
        'sell_rate_bf',
        'tax',
        'margin_amt',
        'discount',
        'present_mrp',
        'margin'
    ];

    // If you want to work with timestamps (created_at and updated_at)
    public $timestamps = true;
}
