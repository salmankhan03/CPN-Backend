<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategoryParentCategoryMap extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $table = 'product_category_parent_category_map';

    protected $fillable = [
        'category_id', 'parent_id'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    public function children()
    {
        return $this->hasMany(ProductCategoryParentCategoryMap::class, 'parent_id');
    }
}
