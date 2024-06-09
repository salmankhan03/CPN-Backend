<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{

    use HasFactory, SoftDeletes;

    const IS_FEATURED_YES = 1;
    const IS_FEATURED_NO = 0;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'products';

    protected $casts = [
        'variants' => 'array',
    ];

    protected $fillable = [
        'name', 'price', 'is_visible', 'description',
        'category_id',
        'sku',
        'slug',
        'is_combination',
        'bar_code',
        'quantity',
        'tags',
        'variants',
        'status',
        'brand_id',
        'brand',
        'is_tax_apply',
        'sell_price',
        'visitors_counter',
        'variants_array',
        'ratings',
        'is_featured',
        'sell_price_updated_at',
        'ratings_updated_at',
        'is_featured_updated_at'
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