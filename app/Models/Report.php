<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [

        'type',
        'description',
        'profile_id',
        'comment_id',
        'post_id',
        'user_id',

    ];


    public function comment(){

        return $this->belongsTo(Comment::class);
    }

    public function profile(){

        return $this->belongsTo(User::class);
    }

    public function post(){

        return $this->belongsTo(Post::class);
    }



}
