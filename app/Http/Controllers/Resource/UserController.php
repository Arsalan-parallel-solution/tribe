<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use DB;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = User::with('userMeta','post');
            return Datatables::of($model)
                    ->addIndexColumn()
                    ->addColumn('post_count', function (User $user) { 
                         return count($user->post); 
                    })->addColumn('hiv_status', function (User $user) {
                         return array($user->userMeta['hiv_status']);
                    })
                    ->addColumn('sexual_orientation', function (User $user) {
                         return array($user->userMeta['sexual_orientation']);
                    })
                    ->addColumn('gender', function (User $user) {
                         return array($user->userMeta['gender']);
                    }) 
                    ->addColumn('status', function (User $user) {
                          if($user->status=="1"){
                             $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Active</a>';
                          }else{  
                            $btn = '<a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Deactive</a>';
                          }
                          return $btn;
                    })  
                    ->addColumn('action', function($row){
     
                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action','status']) 
                    ->make(true);
        }
        
        return view('admin.users.show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('userMeta')->find($id);
        $post = Post::with('postmeta','comments','likes')->withCount('comments','likes')->where('user_id',$user->id)->paginate(10);
        $reports = Report::with('comment','post')
        ->where('user_id',$user->id)->paginate(10);

        $countFollowings = DB::table('follows')->where('to',$id)->count();
        $countFollowers = DB::table('follows')->where('by',$id)->count();
        return view('admin.users.profile',compact('user','post','countFollowers','countFollowings','reports'));
    }


    public function postShow(Request $request, $id)
    {   


        $model = Post::with('postmeta','comments','likes')->where('user_id',$id);
            return Datatables::of($model)
                    ->addIndexColumn()
                    ->addColumn('comment_count', function (Post $post) { 
                         return count($post->comments); 
                    })
                    ->addColumn('likes_count', function (Post $post) { 
                         return count($post->likes); 
                    })
                    ->addColumn('postmeta', function (Post $post) {
                         return $post->postmeta;
                    }) 
                    ->addColumn('action', function(Post $post ){
     
                           $btn = '<a class="edit btn btn-primary btn-sm  viewPost" data-id="'.$post->id.'" style="font-size: 0.8em;">View Post</a>';
                           $btn .= '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
    
                            return $btn;
                    }) 
                    ->rawColumns(['action']) 
                    ->make(true);
      }


    public function Reportshow(Request $request, $id)
    {
        $user = User::with('userMeta')->find($id);
        $post = Post::with('postmeta','comments','likes')->withCount('comments','likes')->where('user_id',$user->id)->paginate(10);
        $reports = Report::with('comment','post')
        ->where('user_id',$user->id)->paginate(10);

        $countFollowings = DB::table('follows')->where('to',$id)->count();
        $countFollowers = DB::table('follows')->where('by',$id)->count();
        return view('admin.users.profile',compact('user','post','countFollowers','countFollowings','reports'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
