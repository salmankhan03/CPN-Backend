<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends  SpatieRole
{
    protected $guarded = ["id"];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    use SoftDeletes;
}
