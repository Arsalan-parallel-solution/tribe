<?php

namespace App\Models;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use NodeTrait; 

    protected $fillable = [
        'parent_id', 
        'comment', 
        'post_id', 
        'user_id', 
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }

  public function allCommentReplies()
  {
        // when visiting the url where i'm 3 levels deep, i'm getting the right category, but as you see:
     print_r($this->isRoot());  // returns: 1       
        print_r($this->ancestors()->get()->toTree()); // returns: Kalnoy\Nestedset\Collection Object ( [items:protected] => Array ( ) )
        // it doesn't seem to recognise the nestedset system

        $ancestors         = ($this->isRoot()) ? NULL : $this->ancestors()->get();

        $categoryCollection   = (empty($ancestors)) ? self::with('CommentReplies') ->find($this->id): $ancestors->toTree()->with('CommentReplies');

        return $categoryCollection;
 }


}
