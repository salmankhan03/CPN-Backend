<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleHasPermissions extends Model
{
    public function Role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function Permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
