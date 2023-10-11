<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSubCategories extends Model
{
    use SoftDeletes;

    protected $table   = "product_sub_categories";

    // preventing from adding timestamp by default
    public $timestamps = false;

    protected $fillable = [
        'name', 'category_id'
    ];

    protected $hidden = ['deleted_at'];

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }
}
