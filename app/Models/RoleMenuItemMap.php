<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleMenuItemMap extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $table = 'role_menu_item_map';

    protected $fillable = [
        'role_id', 'menu_item_id'
    ];

    public function Track()
    {
        return $this->belongsTo(Track::class, 'track_id');
    }
}
