<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use Stripe;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('admin');
    }

    public function admin()
    {   
        $userCount = User::count();
        $userActiveCount = User::where('status','1')->count();
        $userDeactiveCount = User::where('status','0')->count();
        $userTrailCount = User::where('stripe_id',null)->count(); 
        $userProfilePicRequestCount = User::where('stripe_id',null)->count(); 
        $userSubsCount = User::with('subscription')
        ->whereHas('subscription', function($q){
        $q->where('stripe_status','active');
        }) 
        ->where('status','1') 
        ->count();

        $reportCount = Report::count();
  
        return view('admin.index',compact('userCount','userActiveCount','userDeactiveCount','userTrailCount','userProfilePicRequestCount','userSubsCount','reportCount'));
    }

    public function plans(){

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $sd = $stripe->plans->all(); 
        return response()->json($sd);

    }



}
