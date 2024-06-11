<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class ProductTag extends Model
{

    use SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'product_tags';

    protected $fillable = [
        'name', 
        'product_id'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'id');
    }

}