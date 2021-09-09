<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'email_verify',
        'status',
        'is_private',
        'last_active',
        'phone',
        'verification_time',
        'otp',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'otp',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class,'post_likes')->withTimestamps();
    }

    public function followingRequest() {
    return $this->belongsToMany(User::class, 'follow_requests', 'by', 'to');
    }
 
    public function followersRequest() {
        return $this->belongsToMany(User::class, 'follow_requests', 'to', 'by');
    }


    public function following() {
    return $this->belongsToMany(User::class, 'follows', 'by', 'to');
    }
 
    public function followers() {
        return $this->belongsToMany(User::class, 'follows', 'to', 'by');
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }

    public function groups(){
        return $this->hasMany(Group::class);
    }

    public function userMeta(){
        return $this->hasOne(userMeta::class);
    }    
 

    public function member(){
        return $this->belongsToMany(User::class,'group_users')->withTimestamps();
    }

    public function subscription(){
        return $this->belongsToMany(User::class,'subscriptions','user_id');
    }


}
