<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [

        'by',
        'to',
        'post_id',
        'comment_id',
        'badge_id',
        'reward_id',
        'promotion_id',
        'type'

    ];
}
