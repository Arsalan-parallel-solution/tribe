<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;


    protected $fillable = [

             'user_id' ,
             'age' ,
             'height',
             'weight',
             'gender',
             'sexual_orientation',
             'pronouns',
             'ethnicity',
             'hiv_status',
             'social_media_links',
             'desciption',
             'looking_for',
             'position',
             'hangout',
             'tribe',
             'profile_image' 
    ];
}
