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
}
