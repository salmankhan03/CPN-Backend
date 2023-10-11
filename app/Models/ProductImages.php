<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImages extends Model
{

    use HasFactory, SoftDeletes, FileUploadTrait;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'name', 'product_id', 'original_name'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    public function setNameAttribute($value)
    {
        $this->saveFile($value, 'name', "products/" . date('Y/m'));
    }


    public function getNameAttribute()
    {
        if (empty($this->attributes['name'])) {
            return config('app.url') . "/images/user.webp";
        } else {
            return $this->getFileUrl($this->attributes['name']);
        }
    }
}
