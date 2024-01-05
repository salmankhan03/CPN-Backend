<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'email_template';

    protected $fillable = [
        'name',
        'body',
        'from_email',
        'to_email',

    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];
}
