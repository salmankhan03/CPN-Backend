<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeValue extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $fillable = [
        "name",
        "type", // same as parent type >> dropdoon or radio button
        "status",
        'product_attribute_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
