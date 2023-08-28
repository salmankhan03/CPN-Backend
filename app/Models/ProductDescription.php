<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDescription extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'product_description';

    protected $fillable = [
        'description', 'suggested_use', 'other_ingredients', 'warnings', 'disclaimer'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];
}
