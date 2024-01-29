<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class ProductCategory extends Model
{
    use SoftDeletes, HasRecursiveRelationships;

    protected $table   = "product_category";

    const DEFAULT_PRODUCT_CATEGORY = "Uncategorized";



    // preventing from adding timestamp by default
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'status',
    ];

    // protected $visible = ['id', 'name', 'children'];

    protected $hidden = ['deleted_at'];

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->with('children', 'categoryImage');
    }

    public function categoryImage()
    {
        return $this->hasMany(CategoryImages::class, 'category_id', 'id');
    }
}
