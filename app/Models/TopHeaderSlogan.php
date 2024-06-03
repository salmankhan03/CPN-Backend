<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopHeaderSlogan extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'text', 'url'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}