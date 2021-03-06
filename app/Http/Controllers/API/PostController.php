<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Post;
use App\Models\Group;
use App\Models\PostMeta;
use App\Models\Comment;
use App\Models\Notification;
use App\Helpers\PushNotification;
use Auth;
use DB;
use Exception;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->user());
        $post = Post::with(['postmeta','user','likes','comments','comments.user','comments.replies'])->withCount('likes','comments')->where([['user_id',$request->user()->id],['status',1]])->get();
        
        $responseArray = [];
        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Post list';
        $responseArray['data'] = $post;

        return response()->json($responseArray,200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
        $validator = Validator::make($request->all(),[ 
        'content' => 'required',
        'media' => 'required',
        'type' => 'required', 
        'privacy' => 'required'
        ]);

        if($validator->fails()){
        return response()->json($validator->errors(),422);
        }

        try{

        $post = new Post();
        $post->content = $request->content; 
        $post->type = $request->type;
        
        $post->user()->associate($request->user()->id);
        $post->privacy = $request->privacy;
        
        if($request->has('group_id')){
            $post->group_id = $request->group_id;
            $post->status = 2;
        }else{
            $post->status = 1;
        }

        $post->save();

        if($request->has('group_id')){

        $group = Group::find($request->group_id);

        $notificationData=[];
        $notificationData['by'] = $request->user()->id;
        $notificationData['to'] = $group->user_id;
        $notificationData['post_id'] = $post->id;
        $notificationData['group_id'] = $request->group_id;
        $notificationData['type'] = 'request'; 
        $notification = $this->notification($notificationData);

        }


        $mediaArray = [];

        foreach($request->media as $media){
            $mediaArray[] = array('media'=>$media, 'post_id'=> $post->id); 
        }
        // dd($mediaArray);
        $post->postmeta()->insert($mediaArray);

        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Post added successfully';
        $responseArray['data'] = $post->load('likes','postmeta','comments','comments.user','comments.replies')->loadCount('likes','comments');

        return response()->json($responseArray,201);


        }catch(Exception $e){

        return response()->json(['error' => 'Something went wrong.'], 500);
        
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('likes','postmeta','comments','comments.user','comments.replies')->withCount('likes','comments')->where([['id',$id],['status',1]])->get();
        
        
        $postUpdate = Post::find($id);
        if($postUpdate){
        $countView = $postUpdate->views;
        $postUpdate->views = $countView+1;
        $postUpdate->update();
        }
        
        $responseArray = [];

        if(count($post) > 0){
 
                $responseArray['code'] = 200;
                $responseArray['messages'] = 'Post detail';
                $responseArray['data'] = $post;

                return response()->json($responseArray,200);

        } 
 
                $responseArray['code'] =  204 ;
                $responseArray['messages'] =  'Not found';
                $responseArray['data'] = null;
 
                 return response()->json($responseArray,200);
 

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        try {
        $post = Post::findOrFail($id);
        $post->content = $request->content; 
        $post->type = $request->type;
        $post->status = 1; 
        $post->privacy = $request->privacy;
        $post->save();
        $mediaArray = [];
        //$request->user()->post()->postmeta()->where('post_meta.post_id',$id)->delete();
        // foreach($request->media as $media){
        //     $mediaArray[] = array('media'=>$media, 'post_id'=> $post->id); 
        // }
        // dd($mediaArray);
        //$post->postmeta()->insert($mediaArray);



        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Post updated successfully';
        $responseArray['data'] = $post->load('likes','postmeta','comments','comments.user','comments.replies')->loadCount('likes','comments');

        return response()->json($responseArray,201);
        
        }catch(Exception $e){

        return response()->json(['error' => 'Something went wrong.'], 500);
        
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   

        try{

        $post = Post::findOrFail($id); 
        $post->status = 0;  
        $post->save();

        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Post deleted successfully';
        $responseArray['data'] = null;

        return response()->json($responseArray,201);

        }catch(Exception $e){

        return response()->json(['error' => 'Something went wrong.'], 500);
        
        }

    }

    public function addDeletePostLike(Request $request){

        $validator = Validator::make($request->all(),[  
                'post_id' => 'required|numeric|exists:posts,id'
        ]);
 
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        // try{

        $responseArray = []; 
        $hasLikes =  $request->user()->likes()->where('post_likes.post_id', $request->post_id)->exists();
         
        if($hasLikes==1){

        $request->user()->likes()->detach($request->post_id);
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'like removed';
        $responseArray['data'] = null; 
        
        }else{

        $request->user()->likes()->attach($request->post_id);

        $post = Post::find($request->post_id);

        $notificationData=[];
        $notificationData['by'] = $request->user()->id;
        $notificationData['to'] = $post->user_id;
        $notificationData['post_id'] = $post->id;
        $notificationData['type'] = 'like'; 

        $notification = $this->notification($notificationData);

        $responseArray['code'] = 201;
        $responseArray['messages'] = 'like added';
        $responseArray['data'] = null;
        }

        return response()->json($responseArray,201);
        
        // }catch(Exception $e){
        //     return response()->json(['error' => 'Something went wrong.'], 500);
        // }

    }

    public function addComment(Request $request){

        $validator = Validator::make($request->all(),[ 
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required'

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }        

        try{
             $comment = new Comment();
             $comment->parent_id = 0;
             $comment->comment = $request->comment;
             $comment->user_id = $request->user()->id;
             $comment->post_id = $request->post_id;
             $comment->save(); 

        $post = Post::find($request->post_id);
        $notificationData=[];
        $notificationData['by'] = $request->user()->id;
        $notificationData['to'] = $post->user_id;
        $notificationData['post_id'] = $request->post_id;
        $notificationData['type'] = 'comment'; 

        $notification = $this->notification($notificationData);


        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Comment added';
        $responseArray['data'] = $comment;
         

        return response()->json($responseArray,201);



        }catch(Exception $e){
            return response()->json(['error' => 'Something went wrong.'], 500);
        }

    }

    public function addCommentReply(Request $request){

        $validator = Validator::make($request->all(),[ 
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'required',
            'comment' => 'required'

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }        


        try{


        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,

        ]);

        if($request->parent && $request->parent_id !== 0) {
            //  Here we define the parent for new created category
            $node = Comment::find($request->parent);

            $node->appendNode($comment);
        }
 
             
        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Comment reply added';
        $responseArray['data'] = $comment;

        return response()->json($responseArray,201);

        }catch(Exception $e){
            return response()->json(['error' => 'Something went wrong.'], 500);
        }

    }

    public function deleteComment($id){
 
        try{
 
            $comments =Comment::where('id',$id)->first();

            if ($comments != null) {
                $comments->delete();
                $responseArray = [];
                $responseArray['code'] = 201;
                $responseArray['messages'] = 'Comment deleted';
                $responseArray['data'] = null;
                return response()->json($responseArray,200);
            }

                $responseArray['code'] = 201;
                $responseArray['messages'] = 'Invalid comment ID';
                $responseArray['data'] = null;


            return response()->json($responseArray, 201);


        }catch(Exception $e){
            return response()->json(['error' => 'Something went wrong.'], 500);

        } 

    }


    public function notification($data){

        $notification = new PushNotification();
        $user = $notification->addNotification($data);
         

    }



}
