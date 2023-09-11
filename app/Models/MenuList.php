<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuList extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $table = 'menu_list';

    protected $fillable = [
        'name', 'url', 'order'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'sub_menus' => 'array'
    ];
}
