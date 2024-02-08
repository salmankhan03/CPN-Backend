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
        'name', 'price', 'is_visible', 'description',
        'category_id',
        'sku',
        'slug',
        'is_combination',
        'bar_code',
        'quantity',
        'slug',
        'tags',
        'variants',
        'status',
        'brand_id',
        'brand',
        'is_tax_apply',
        'sell_price'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }
}
