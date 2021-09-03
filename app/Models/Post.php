<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_content', 
        'post_type',
        'group_id',
        'views',
        'user_id',
        'post_privacy',
        'status' 
    ];

    // postmeta for media
    public function postmeta(){
        return $this->hasMany(PostMeta::class);
    }

    // post related user
    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function likes(){
        return $this->belongsToMany(User::class,'post_likes')->withTimestamps();
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }

    // public function likesCount()
    // {
    //     return $this->belongsToMany('Order')
    //         ->selectRaw('count(orders.id) as aggregate')
    //         ->groupBy('pivot_product_id');
    // }


    // public function likes()
    // {
    //     return $this->belongsToMany('App\Product', 'products_shops', 
    //       'shops_id', 'products_id');
    // }



}
