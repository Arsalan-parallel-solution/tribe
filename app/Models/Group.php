<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description',
        'user_id',
        'icon',
        'status' 
    ];

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function members(){
        return $this->belongsToMany(User::class,'group_users')->withTimestamps();
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function approvedposts(){
        return $this->hasMany(Post::class);
    }

    public function pendingposts(){
        return $this->hasMany(Post::class);
    }


}
