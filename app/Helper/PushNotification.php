<?php 

namespace App\Helpers;
use App\Models\Notification;
use Carbon\Carbon;

class PushNotification
{

 
    function push_notification_android($notification_id, $title, $message, $id,$type) {



        $data = '{
                    "to" : "' . $notification_id . '", 
                    "notification" : {
                         "body" : "' . $message . '",
                         "title" : "' . $title . '",
                          "type" : "' . $type . '",
                         "id" : "' . $id . '",
                         "message" : "' . $message . '",
                        "icon" : "new",
                        "sound" : "default"
                        } 
         
                  }';


           
         
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        $server_key = env('FCM_KEY');
         
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);

        //echo print_r($result);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);


        }

        function addNotification($data){

        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();
        $insert = Notification::insert($data);
        
        if($insert){
        return true;
        }


        }

}
