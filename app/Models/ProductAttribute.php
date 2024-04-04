<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'product_attribute';

    protected $fillable = [
        "name",
        "title",
        "option",
        "status",
        "variants"
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function Variants()
    {
        return $this->hasMany(ProductAttributeValue::class,"product_attribute_id" , "id");
    }
}
