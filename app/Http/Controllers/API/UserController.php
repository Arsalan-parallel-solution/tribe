<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Auth;
use DB;
use Validator;
use Exception;
use Carbon\Carbon;
class UserController extends Controller
{
     public function postdetails(){ 
        $post = User::with('following','followers')->withCount('following','followers')->where('id',Auth::user()->id)->get();
        return $post;
     }

   public function showComment(Request $request){

         $validator = Validator::make($request->all(),[ 
            'comment_id' => 'required|exists:comments,id', 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),200);
        }

        $comments = Comment::where('id',$request->comment_id)->first();

            if ($comments != null) { 
                $responseArray = [];
                $responseArray['code'] = 201;
                $responseArray['messages'] = 'Comment Detail';
                $responseArray['data'] = $comments;
                return response()->json($responseArray,200);
            }

                $responseArray['code'] = 201;
                $responseArray['messages'] = 'Invalid comment ID';
                $responseArray['data'] = null;


            return response()->json($responseArray, 500);

    }

    public function updateComment(Request $request, $id){

         $validator = Validator::make($request->all(),[ 
            'comment' => 'required', 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),200);
        }

        try{

        $comment = Comment::findOrFail($id);
        $comment->comment = $request->comment;  
        $comment->update();
        $mediaArray = [];
 
        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Comment updated';
        $responseArray['data'] = $comment;
       
        return response()->json($responseArray,200);
       
        }catch(Exception $e){

         return response()->json(['error' => 'Something went wrong.'], 500);
        
        }

    }



    public function showAllComments(Request $request){

        $validator = Validator::make($request->all(),[ 
            'post_id' => 'required|exists:posts,id', 

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),200);
        }    
        
        //$comments = Comment::where('id',$request->comment_id)->first();
        // $comments = Comment::where([['parent_id',null],['id',$request->comment_id]])->withDepth() 
        //     ->get()
        //     ->toTree();

            $comments = Comment::withDepth() 
            ->where('post_id',$request->post_id)->with('user')->get()
            ->toTree();

        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Comment list';
        $responseArray['data'] = $comments;
         return response()->json($responseArray,200);

    }


    public function followUnFollowUser(Request $request){

        $validator = Validator::make($request->all(),[  
            'to' => 'required|exists:users,id',  
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),200);
        }   


        try{ 

        $responseArray = [];

        $getFollow = DB::table('follows')->where([["by",$request->user()->id],["to",$request->to]])->first();

        if($getFollow){

          DB::table('follows')->where('id',$getFollow->id)->delete();

          $responseArray['code'] = 201;
          $responseArray['messages'] = 'Unfollow successfully';
          $responseArray['data'] = $comments;
        
        }else{
        
         $follow = DB::table('follows')->insert(
           array( "to" => $request->to,
            "by" => $request->user()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
             )
        );

        
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Follow successfully';
        $responseArray['data'] = $comments;
         


        }

        return response()->json($responseArray,200);

        }catch(Exception $e){

         return response()->json(['error' => 'Something went wrong.'], 500);

        }

        

    }



}
