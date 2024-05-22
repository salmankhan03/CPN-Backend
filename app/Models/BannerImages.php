<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerImages extends Model
{

    use SoftDeletes,FileUploadTrait;

    const SIDE_LEFT = "LEFT"; 
    const SIDE_RIGHT = "RIGHT"; 

    public $timestamps = true;

    protected $table = 'banner_image';

    protected $fillable = [
        'image',
        'side',
        'created_by',
        'deleted_by',
        'original_name',
        'heading',
        'content',
        'button_label',
        'button_url',
        'content_position'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function setImageAttribute($value)
    {   
        if (empty($this->attributes['id'])){

            $this->saveFile($value, 'image', "banner_images/" . date('Y/m'));
        }
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
