<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','gstin'
        ,'org_pan', 'org_name', 'org_address',
        'pref_product',
        'pref_product_size',
        'user_type',
        'company_gst',
        'company_linked_address',
        'company_pan',
        'company_name',
        'business_nature',
        'first_name',
        'last_name',
        'addressone',
        'addresstwo',
        'city',
        'state',
        'pincode',
        'address_type',
          
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
 
   public function setPasswordAttribute($password)
   {
       if ( !empty($password) ) {
           $this->attributes['password'] = bcrypt($password);
       }
   }
}
