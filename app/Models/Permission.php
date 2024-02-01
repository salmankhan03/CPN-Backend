<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends  SpatiePermission
{
    protected $guarded = ["id"];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    use SoftDeletes;
}
