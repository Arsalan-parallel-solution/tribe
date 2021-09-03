<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Notifications\ResetPasswordOTP;
use Validator;
use Response;
use Notification;
use Carbon\Carbon;

class RegisterController extends Controller
{
     
    // User Authentication - Register

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
        return  response()->json($validator->errors(),422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['is_private'] = 'no';
        $otp = rand(1000,9999);
        $now  = date("Y-m-d H:i:s");
        $input['otp'] = $otp;
        $input['verification_time'] = $now;

        $user = User::create($input); 

        $site_title = "Tribe"; 

        $mailBody = '
                <p>Dear <b>'.  $request->email .'</b>,</p>
                <p style="font-size:16px;color:#333333;line-height:24px;margin:0">Use the OTP to verify your email address.</p>
                <h3 style="color:#333333;font-size:24px;line-height:32px;margin:0;padding-bottom:23px;margin-top:20px;text-align:center">'
                .$otp.'</h3>
                <br/><br/>
                <p style="color:#333333;font-size:16px;line-height:24px;margin:0;padding-bottom:23px">Thank you<br /><br/>'.$site_title.'</p>
                ';
                // dd($mailBody);
                // $ref_id
        $array = array('subject'=>'OTP Email Verification - '.$site_title,'view'=>'emails.site.company_panel','body' => $mailBody);
              

        Mail::to($request->email)->send(new SendMail($array));  

        $responseArray = [];
        $responseArray['code'] = 201;
        $responseArray['messages'] = 'Please check your mail for verification.';
        $responseArray['data'] = $user;

        return response()->json($responseArray,201);

    }

    // User Authentication - Login


    public function login(Request $request){

    $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

    if($validator->fails()){
        return  response()->json($validator->errors(),422);
    }

    if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){

        $user = Auth::user();
        $token = auth()->user()->createToken('api-authentication')->accessToken;
        $responseArray = [];
        $responseArray['token'] = $token;
        $responseArray['name'] = $user->username;

        if(!$user->email_verify=="yes"){

            return response()->json([ 'error' => 'Kindly verify your account.'],200);
        }

        return response()->json($responseArray,200);

    }else{

        return response()->json([ 'error' => 'Unauthenticated'],203);
    }

    
    }

    public function verifyToken(Request $request){

       $validator = Validator::make($request->all(),[
            'token' => 'required', 
            'email' => 'required|exists:users,id', 
        ]);

        if($validator->fails()){
        
        return response()->json($validator->errors(),422);
        
        }
        $responseArray = [];
        
        $user = User::where([['email',$request->email],['otp',$request->token],['email_verify',null]])->first();
        
        if($user != null){
        
        $expiryDateTimeDB = Carbon::parse($user->verification_time);

        $currentDateTime = Carbon::now();
 
 
        $expiryDateTime = $expiryDateTimeDB->addMinutes(2);

        if($currentDateTime->gt($expiryDateTime)){
            
        
        $responseArray['code'] = 200;
        $responseArray['messages'] = 'OTP expired';
        $responseArray['data'] = null; 
        
        return response()->json($responseArray,200);
        
        }else{

        $user->email_verify = 1;
        $user->save();

        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Profile activated successfully. Proceed to Login';
        $responseArray['data'] = null; 
        
        return response()->json($responseArray,200);
      
        } 
        
        }else{

        $responseArray['code'] = 203;
        $responseArray['messages'] = 'OTP expired';
        $responseArray['data'] = null;

        return response()->json($responseArray,200);

        }

    }

 
    public function forgotPassword(Request $request){ 
        
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',            
        ]);

        $provider = User::where('email' , $request->email)->first();

        if ($validator->fails()) { 
            return  response()->json($validator->errors(),422);
        }   

        try{  
            
            $user = User::where('email', $request->email)->first();

            $otp = mt_rand(1000, 9999);

            $user->otp = $otp;
            $user->verification_time = Carbon::now();
            $user->save();
            
            Notification::send($user, new ResetPasswordOTP($otp));
           
            $responseArray = [];
            $responseArray['code'] = 200;
            $responseArray['messages'] = 'OTP sent to your email!';
            $responseArray['data'] = $user;

            return response()->json($responseArray,200);


        }catch(Exception $e){

                return response()->json(['error' => 'Something went wrong.'], 500);
        }
     

    }

    public function forgotPasswordToken(Request $request){

       $validator = Validator::make($request->all(),[
            'token' => 'required',
            'email' => 'required|exists:users,id', 
            
        ]);

        if($validator->fails()){
        
        return response()->json($validator->errors(),422);
        
        }
        $responseArray = [];

        try{
        
        $user = User::where([['otp',$request->token],['email',$request->email]])->first();
        
        if($user != null){
        
        $expiryDateTimeDB = Carbon::parse($user->verification_time);

        $currentDateTime = Carbon::now();
 
 
        $expiryDateTime = $expiryDateTimeDB->addMinutes(2);

        if($currentDateTime->gt($expiryDateTime)){
            
        
        $responseArray['code'] = 203;
        $responseArray['messages'] = 'OTP expired';
        $responseArray['data'] = null; 
        
        return response()->json($responseArray,203);
        
        }else{

        // $user->email_verify = 1;
        // $user->save();

        $responseArray['code'] = 200;
        $responseArray['messages'] = 'Valid token';
        $responseArray['data'] = $user; 
        
        return response()->json($responseArray,200);
      
        } 
        
        }else{

        $responseArray['code'] = 203;
        $responseArray['messages'] = 'OTP expired or invalid.';
        $responseArray['data'] = null;

        return response()->json($responseArray,203);

        }

            }catch(Exception $e){

                return response()->json(['error' => 'something went wrong'], 500);

            }

    }


    public function resetPassword(Request $request){


        $validator = \Validator::make($request->all(), [
                'password' => 'required|min:6',
                'id' => 'required|numeric|exists:users,id'
            ]);

        if($validator->fails()){ 
        return response()->json($validator->errors(),422); 
        }  
 
        try{
            
            $responseArray = [];

            $user = User::findOrFail($request->id); 
            $user->password = bcrypt($request->password);
            $user->save();
            
                
            $responseArray['code'] = 201;
            $responseArray['messages'] = 'Password updated successfully.';
            $responseArray['data'] = null;

            return response()->json($responseArray,201);
            

        }catch (Exception $e) {
 
                return response()->json(['error' => 'something went wrong'], 500);
             
        }
    }







}
