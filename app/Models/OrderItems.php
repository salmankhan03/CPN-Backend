<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItems extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'order_items';

    protected $fillable = [
        "order_id",
        "product_id",
        "use_product_original_data",
        "price",
        "quantity"
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}