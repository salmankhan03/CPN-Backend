<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SliderImages extends Model
{

    use  SoftDeletes,FileUploadTrait;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'slider_images';

    protected $fillable = [
        'image',
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
        'created_by',
        'original_name',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function setImageAttribute($value)
    {
        if (!$this->attributes['id']){
            $this->saveFile($value, 'image', "slider_images/" . date('Y/m'));
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
