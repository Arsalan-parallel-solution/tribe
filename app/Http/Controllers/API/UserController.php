<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Report;
use App\Helpers\PushNotification;
use Auth;
use DB;
use Validator;
use Exception;
use Carbon\Carbon;
class UserController extends Controller
{   

     public function firstStep(Request $request){
    
        $validator = Validator::make($request->all(),[ 
            'age' => 'required', 
            'height' => 'required', 
            'weight' => 'required', 
            'gender' => 'required', 
            'sexual_orientation' => 'required', 
            'pronouns' => 'required', 
            'ethnicity' => 'required', 
            'hiv_status' => 'required', 
            'social_media_links' => 'required', 
            'description' => 'required' 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        try{

        $input = $request->all();
        $input['user_id'] = $request->user()->id;
        $input['created_at'] = Carbon::now()->toDateTimeString();
        $input['updated_at'] = Carbon::now()->toDateTimeString();
        $UserMeta = UserMeta::insert($input);

        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Usermeta added.';
        $responseArray['data'] = $request->user()->userMeta;
       
        return response()->json($responseArray,201);
 

        }catch(Exception $e){
             
            return response()->json(['error' => 'Something went wrong.'], 500);

        }


     }

     public function secondStep(Request $request){
    
        $validator = Validator::make($request->all(),[ 
            'looking_for' => 'required', 
            'hangout' => 'required', 
            'position' => 'required', 
            'tribe' => 'required' 
        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        try{
        
        $userMeta = UserMeta::where('user_id',$request->user()->id)->first();
        $userMeta->looking_for = $request->looking_for;
        $userMeta->hangout = $request->hangout;
        $userMeta->position = $request->position;
        $userMeta->tribe = $request->tribe;
        $userMeta->update();

         
        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Usermeta updated.';
        $responseArray['data'] = $request->user()->userMeta;
       
        return response()->json($responseArray,201);



        }catch(Exception $e){
             
            return response()->json(['error' => 'Something went wrong.'], 500);

        }


     }

     public function thirdStep(Request $request){
    
        $validator = Validator::make($request->all(),[ 
            'profile_image' => 'required' 
        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        try{
        
        $userMeta = UserMeta::where('user_id',$request->user()->id)->first();
        $userMeta->profile_image = $request->profile_image; 
        $userMeta->update();

         
        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Usermeta updated.';
        $responseArray['data'] = $request->user()->userMeta;
       
        return response()->json($responseArray,201);



        }catch(Exception $e){
             
            return response()->json(['error' => 'Something went wrong.'], 500);

        }


     }



     public function postdetails(){ 
        $post = User::with('following','followers')->withCount('following','followers')->where('id',Auth::user()->id)->get();
        return $post;
     }

   public function showComment(Request $request){

         $validator = Validator::make($request->all(),[ 
            'comment_id' => 'required|exists:comments,id', 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
        }


        try{

        $comments = Comment::where('id',$request->comment_id)->first();

            if ($comments != null) { 
                $responseArray = [];
                $responseArray['code'] = 200;
                $responseArray['messages'] = 'Comment Detail';
                $responseArray['data'] = $comments;
                return response()->json($responseArray,200);
            }

                $responseArray['code'] = 204;
                $responseArray['messages'] = 'Invalid comment ID';
                $responseArray['data'] = null;


            return response()->json($responseArray, 204);


        }catch(Exception $e){

         return response()->json(['error' => 'Something went wrong.'], 500);
        
        }

    }

    public function updateComment(Request $request, $id){

         $validator = Validator::make($request->all(),[ 
            'comment' => 'required', 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
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
       
        return response()->json($responseArray,201);
       
        }catch(Exception $e){

         return response()->json(['error' => 'Something went wrong.'], 500);
        
        }

    }



    public function showAllComments(Request $request){

        $validator = Validator::make($request->all(),[ 
            'post_id' => 'required|exists:posts,id', 

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }    
        
        //$comments = Comment::where('id',$request->comment_id)->first();
        // $comments = Comment::where([['parent_id',null],['id',$request->comment_id]])->withDepth() 
        //     ->get()
        //     ->toTree();

            $comments = Comment::withDepth() 
            ->where('post_id',$request->post_id)->with('user')->get()
            ->toTree();

        $responseArray = [];
        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Comment list';
        $responseArray['data'] = $comments;
        return response()->json($responseArray,200);

    }


    public function followUnFollowUser(Request $request){

        $validator = Validator::make($request->all(),[  
            'to' => 'required|exists:users,id',  
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }   


        try{ 

        $responseArray = [];



        $getFollow = DB::table('follows')->where([["by",$request->user()->id],["to",$request->to]])->first();

        if($getFollow){

          DB::table('follows')->where('id',$getFollow->id)->delete();

          $responseArray['code'] = 201;
          $responseArray['messages'] = 'Unfollow successfully';
          $responseArray['data'] = null;
        
        }else{
        

        $User = User::find($request->to);
        
        if($User->is_private=="yes"){

        $getFollowRequest = DB::table('follow_requests')->where([["by",$request->user()->id],["to",$request->to]])->first();        


        if($getFollowRequest){

          DB::table('follow_requests')->where('id',$getFollowRequest->id)->delete();

          $responseArray['code'] = 201;
          $responseArray['messages'] = 'Unfollow request successfully';
          $responseArray['data'] = null;
        
        }else{
        
        $follow = DB::table('follow_requests')->insert(
           array( 
            "to" => $request->to,
            "by" => $request->user()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
             )
        );

        $notificationData=[];
        $notificationData['by'] = $request->user()->id;
        $notificationData['to'] = $request->to; 
        $notificationData['type'] = 'follow'; 
        $notification = $this->notification($notificationData);


        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Follow request sent successfully';
        $responseArray['data'] = null;



        }

        
        }else{

        $follow = DB::table('follows')->insert(
           array( 
            "to" => $request->to,
            "by" => $request->user()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
             )
        );

 
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Follow successfully';
        $responseArray['data'] = null;
           
        }

        }
 

        return response()->json($responseArray,201);

        }catch(Exception $e){

         return response()->json(['error' => 'Something went wrong.'], 500);

        }

        

    }

    public function requestAcceptReject(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'request_id' => 'required|exists:follow_requests,id',
            'status' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        try{

            $followRequest = DB::table('follow_requests')->find($request->request_id);

            if($request->status=="accept"){
                
                
 
                $follow = DB::table('follows')->insert(
                array( 
                "to" => $followRequest->to,
                "by" => $followRequest->by,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
                )
            );

                if($follow){

                    DB::table('follow_requests')->delete($followRequest->id);  

                    $notificationData=[];
                    $notificationData['by'] = $followRequest->by;
                    $notificationData['to'] = $followRequest->to; 
                    $notificationData['type'] = 'accept'; 
                    $notification = $this->notification($notificationData);


                    $responseArray['code'] = 201;
                    $responseArray['messages'] = 'Request accepted successfully';
                    $responseArray['data'] = null;



                }


            }else{

            $followRequest->delete();

            $responseArray['code'] = 201;
            $responseArray['messages'] = 'Request rejected successfully';
            $responseArray['data'] = null;
 
            }
            
            return response()->json($responseArray,201); 


        }catch(Exception $e){

            return response()->json(['error' => 'Something went wrong.'], 500); 
        
        }
 

    }


    public function report(Request $request){

    $validator = Validator::make($request->all(),[  
            'description' => 'required',  
            'type' => 'required',
            'comment_id' => 'required_without_all:profile_id,post_id|exists:comments,id',
            'profile_id' => 'required_without_all:post_id,comment_id|exists:users,id',
            'post_id' => 'required_without_all:comment_id,profile_id|exists:posts,id',
        ]);

    if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

    try {


   $notification_id = $request->user()->device_key;
   $title = "Greeting Notification";
   $message = "Have good day!";
   $id = $request->user()->id;
   $type = "basic";
    
  
   // $obj = new PushNotification();
   // $api = $obj->push_notification_android($notification_id, $title, $message, $id,$type);

   // $res = $obj->send_notification_FCM($notification_id, $title, $message, $id,$type);
    
    $input = $request->all(); 
    $input['user_id'] = $request->user()->id;
    $input['created_at'] = Carbon::now()->toDateTimeString();
    $input['updated_at'] = Carbon::now()->toDateTimeString();
    
    if($request->type=="comment"){
        $reportcheck = Report::where([['user_id',$request->user()->id],['comment_id',$request->comment_id]])->first();
    }else if($request->type=="post"){
        $reportcheck = Report::where([['user_id',$request->user()->id],['post_id',$request->post_id]])->first();
    }else if ($request->type=="profile") {
         $reportcheck = Report::where([['user_id',$request->user()->id],['comment',$request->profile_id]])->first();
    }
    
    if($reportcheck){

    $responseArray['code'] = 201;
    $responseArray['messages'] = 'Reported already';
    $responseArray['data'] = null;

    return response()->json($responseArray,201);

    }

    $report = Report::insert($input);

    $responseArray['code'] = 201;
    $responseArray['messages'] = 'Reported successfully';
    $responseArray['data'] = null;

    return response()->json($responseArray,201);

    }catch(Exception $e){

         return response()->json(['error' => 'Something went wrong.'], 500);

     }


    }


    public function notification($data){

        $notification = new PushNotification();
        $user = $notification->addNotification($data);
          
    }


    public function timeline(Request $request){

        if($request->has('skip')){

            $posts = Post::with('user','postmeta','likes','comments')
            ->withCount('likes','comments')
            ->where([['status','1'],['privacy','1'],['group_id',null]])
            ->skip($request->skip)
            ->take(10)->get();

        }else{

            $posts = Post::with('user','postmeta','likes','comments')
            ->withCount('likes','comments')
            ->where([['status','1'],['privacy','1'],['group_id',null]])
            ->take(10)
            ->get();
        }
        
        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Post list';
        $responseArray['data'] = $posts;

        return response()->json($responseArray,200);
 
    }


    public function trending(Request $request){

        if($request->has('skip')){

            $posts = Post::with('user','postmeta') 
            ->where([['status','1'],['privacy','1'],['group_id',null]])
            ->skip($request->skip)
            ->take(10)
            ->orderBy('views', 'DESC')
            ->get();

        }else{

            $posts = Post::with('user','postmeta') 
            ->where([['status','1'],['privacy','1'],['group_id',null]])
            ->take(10)
            ->orderBy('views', 'DESC')
            ->get();
        }
        
        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Post list';
        $responseArray['data'] = $posts;

        return response()->json($responseArray,200);
 
    }


    public function search(Request $request){

        $validator = Validator::make($request->all(),[
            'search_type' => 'required',
            'search_text' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        if($request->search_type=="account"){

            $users = User::where('username', 'like', '%' . $request->search_text . '%');
            if($request->has('skip')){
            $users = $users->skip($request->skip);
            }
            $users = $users->take(10);
            $users = $users->get();


            $responseArray['code'] = 200;
            $responseArray['messages'] = 'Accounts list';
            $responseArray['data'] = $users;

            return response()->json($responseArray,200);

        }else{

            $posts = Post::with('user','postmeta') 
            ->where([['status','1'],['privacy','1'],['group_id',null],['content', 'like', '%' . $request->search_text . '%']]);

            $posts = $posts->orderBy('views', 'DESC'); 
            if($request->has('skip')){
             $posts = $posts->skip($request->skip);
            } 
            $posts = $posts->take(10);
            $posts = $posts->get();

            $responseArray['code'] = 200;
            $responseArray['messages'] = 'Posts list';
            $responseArray['data'] = $posts;

        return response()->json($responseArray,200);

        } 
        
        
 
    }








    

}
