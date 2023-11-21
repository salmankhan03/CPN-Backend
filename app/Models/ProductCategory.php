<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $table   = "product_category";

    // preventing from adding timestamp by default
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'status',
    ];

    protected $hidden = ['deleted_at'];
}
