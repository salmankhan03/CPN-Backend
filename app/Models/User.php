<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, FileUploadTrait, HasRoles;

    const CUSOTMER_ROLE_ID = 5;
    const CUSOTMER_ROLE_NAME = "CUSTOMER";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'profile_pic',
        'role_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'contact_no',
        'secondary_contact_number',
        'city',
        'state',
        'country',
        'zipcode',
        'street_address',
        'street',
        'landmark'
    ];



    public $timestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->uuid = (string) Uuid::uuid6();
            $model->save();
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function menuList()
    {
        return $this->hasMany(RoleMenuItemMap::class, 'role_id', 'role_id');
    }

    public function setProfilePicAttribute($value)
    {
        $this->saveFile($value, 'profile_pic', "user/" . date('Y/m'));
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function getProfilePicAttribute()
    {
        if (empty($this->attributes['profile_pic'])) {
            return config('app.url') . "/images/user.webp";
        } else {
            return $this->getFileUrl($this->attributes['profile_pic']);
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function shippingAddress()
    {
        return $this->hasMany(UserShippingAddress::class, 'user_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(UserBillingAddress::class, 'user_id')->latest();
    }

    public function sentEmails()
    {
        return $this->hasManyThrough(SentOrderStatusUpdateEmailLog::class, Order::class, 'user_id', 'order_id', 'id', 'id');
    }

    public function shippingAddressAddedyByUser(){
        return $this->shippingAddress()->where('is_added_by_user','=', 1);
    }

    public function billingAddressAddedyByUser(){
        return $this->hasMany(UserBillingAddress::class, 'user_id')->where('is_added_by_user','=', 1);
    }

    public function defaultShippingAddress()
    {
        return $this->shippingAddress()->where('is_added_by_user','=', 1)->where('is_default' , "=" , 1)->latest();
    }

    public function defaultBillingAddress()
    {
        return $this->hasMany(UserBillingAddress::class, 'user_id')->where('is_added_by_user','=', 1)->where('is_default' , "=" , 1)->latest();
    }
}
