<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleMenuItemMap extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'role_menu_item_map';

    protected $fillable = [
        'role_id', 'menu_item_id', 'updated_by_user_id'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    public function menuItem()
    {
        return $this->hasMany(MenuList::class, 'id', 'menu_item_id');
    }
}