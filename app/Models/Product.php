<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'products';

    protected $fillable = [
        'name', 'price', 'produced_by', 'currency', 'shipping_weight', 'product_code', 'upc_code', 'package_quantity', 'dimensions', 'is_visible', 'description', 'suggested_use',
        'other_ingredients',
        'disclaimer',
        'warnings'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];
}
