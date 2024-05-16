<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerImages extends Model
{

    const SIDE_LEFT = "LEFT"; 
    const SIDE_RIGHT = "RIGHT"; 

    use SoftDeletes,FileUploadTrait;

    public $timestamps = true;

    protected $table = 'banner_image';

    protected $fillable = [
        'image',
        'side',
        'created_by',
        'deleted_by',
        'original_name'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function setImageAttribute($value)
    {
        $this->saveFile($value, 'image', "banner_images/" . date('Y/m'));
    }

    public function getImageAttribute()
    {
        if (empty($this->attributes['image'])) {
            return config('app.url') . "/images/user.webp";
        } else {
            return $this->getFileUrl($this->attributes['image']);
        }
    }
}
