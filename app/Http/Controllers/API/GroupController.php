<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Post;
use App\Models\Group;
use App\Models\PostMeta;
use App\Models\Comment;
use Auth;
use DB;
use Exception;

class GroupController extends Controller
{    

     public function listGroup(Request $request){

        $group = $request->user()
        ->groups()
        ->where('status',1)
        ->with('members')
        ->get();

        return $group;

     }

     public function singleGroup(Request $request, $id){

        $validator = Validator::make($request->all(),[  
            'group_id' => 'required|exists:groups,id'  
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $approvedstatus = 1 ;
        $pendingstatus = 1;
 
        $group = Group::where([['status',1],['id',$id]])
        ->with('members') 
        ->get();

        $group[0]['approved_post'] = post::where([['group_id',$id],['status',1]])->get();
        $group[0]['pending_post'] = post::where([['group_id',$id],['status',2]])->get();
         
        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Group detail';
        $responseArray['data'] = $group;
         

        return response()->json($responseArray,200);


        return $group;

     }

     public function addGroup(Request $request){

        $validator = Validator::make($request->all(),[  
            'name' => 'required', 
            'description' => 'required', 
            'icon' => 'required|mimes:jpg,bmp,png' 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        try{

            $input = $request->all();
            $input['user_id'] = $request->user()->id;
            $input['status'] = 1;
            $group = Group::create($input);  
            
            $memberArray = array();
            foreach($request->members as $member){
                $memberArray[] = $member;
            }
 
            $group->members()->attach($memberArray);

        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Group added successfully';
        $responseArray['data'] = $group->load('members');
         

        return response()->json($responseArray,201);

            
        }catch(Exception $e){   
            return response()->json(['error' => 'Something went wrong.'], 500);
        }


     }

     public function updateGroup(Request $request, $id){

        $validator = Validator::make($request->all(),[  
            'name' => 'required', 
            'description' => 'required', 
            'icon' => 'required|mimes:jpg,bmp,png' 

        ]);

         if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        try{

        $groupUpdate = Group::findOrFail($id);
        $groupUpdate->name = $request->name;
        $groupUpdate->description = $request->description;
        $groupUpdate->icon = $request->icon;
        $groupUpdate->update();

        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Group updated';
        $responseArray['data'] = $groupUpdate->load('members','posts','posts.user');

        return response()->json($responseArray,201);
        
        }catch(Exception $e){
             return response()->json(['error' => 'Something went wrong.'], 500);
        }

  
     }

     public function deleteGroup(Request $request, $id){

        // return $id;

        try{

        $deleteGroup = Group::where([['user_id',$request->user()->id],['id',$id],['status',1]])->first();   
        
        if($deleteGroup){

        $deleteGroup->status = 0;
        $deleteGroup->update();

        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Group deleted';
        $responseArray['data'] = null;

        return response()->json($responseArray,201);
        
        }else{

        $responseArray['code'] = 204;
        $responseArray['messages'] = 'Not found';
        $responseArray['data'] = null;

        return response()->json($responseArray,200 ); 

        }

        }catch(Exception $e){

            return response()->json(['error' => 'Something went wrong.'], 500);

        }

     }

     public function addMember(Request $request){

        $validator = Validator::make($request->all(),[  
            'group_id' => 'required|exists:groups,id',   
            'member_id' => 'required|exists:users,id',   

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $memberArray=[];

        $group = Group::find($request->group_id);

        

        foreach($request->member_id as $member){
           
            $hasMember =  $group->members()->where('group_users.user_id', $member)->exists();
            if(!$hasMember){
            $memberArray[] = $member;
            }
            
        }
         
        if(count($memberArray) > 0){


        $group->members()->attach($memberArray);

        $responseArray['code'] = 204 ;
        $responseArray['messages'] = 'Not found';
        $responseArray['data'] = $group->members()->get();

        return response()->json($responseArray,200 ); 

        }else{

        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Members already exists';
        $responseArray['data'] = $group->members()->get();

        return response()->json($responseArray,200); 
 
        } 

     }

      public function deleteMember(Request $request){

        $validator = Validator::make($request->all(),[  
            'group_id' => 'required|exists:groups,id',   
            'member_id' => 'required|exists:users,id',   

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $memberArray=[];

        $group = Group::find($request->group_id);
  
        foreach($request->member_id as $member){
            
            $memberArray[] = $member;
           
        }
          
        $group->members()->detach($memberArray);
        //$group->members()->attach($memberArray);

        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Members deleted';
        $responseArray['data'] = $group->members()->get();

        return response()->json($responseArray,201); 
  

     }


     public function approvedDisapprovedPost(Request $request){

        $validator = Validator::make($request->all(),[  
            'post_id' => 'required|exists:posts,id',   
            'status' => 'required',   

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        try{

            $post = Post::findOrFail($request->post_id); 
            $post->status = $request->status; 
            $post->update();

            $responseArray['code'] = 201;
            $responseArray['messages'] = $request->status == 0 ? 'Post disapproved' : 'Post approved';
            $responseArray['data'] = $post;

            return response()->json($responseArray,201);  

        }catch(Exception $e){

            return response()->json(['error' => 'Something went wrong.'], 500);

        }

     }






}
