<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function following() {
    return $this->belongsToMany(User::class, 'follows', 'by', 'to');
    }

    // users that follow this user
    public function followers() {
        return $this->belongsToMany(User::class, 'follows', 'to', 'by');
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }


}
