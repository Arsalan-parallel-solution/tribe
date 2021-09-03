<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
 
// use App\Models\Book;
// use App\Models\Package;
use App\Models\User;
// use App\Models\UserAddress;
// use App\Notifications\OrderPlacedNotification;
// use App\Notifications\UserSubscribedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle charge succeeded.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleChargeSucceeded($payload)
    {
        // Handle the incoming event...
        return $payload;
    }

    public function handleCustomerSubscriptionUpdated($payload)
    {

        $user = Cashier::findBillable($payload['data']->customer);
        $stripe_plan = $user->subscription('default')->items->first()->stripe_plan;
        $plan = Package::where('stripe_plan',$stripe_plan)->first();

        $user_books = $user->books->pluck('id');
        
        $books = Book::whereNotIn('id',$user_books)->whereHas('genres', function($q)  use ($user){
            $q->whereIn('genre_id', $user->genres);
        })->inRandomOrder()->limit($plan->book_quantity)->pluck('id');

        $address = UserAddress::find($user->default_address);


        $order = $plan->orders()->create([
            'user_id' => $user->id,
            'amount' => $plan->amount,
            'book_quantity' => $plan->book_quantity,
            'genres' => json_encode($user->genres),
            'books' => json_encode($books),
            'delivery_address' => $address->street_address,
            'delivery_phone' => $address->phone
        ]);

    $details = [
        'title' => 'New Order Received.',
        'order_id' => $order->id
    ];

    $package_details = [
        'title' => $user->name.' Subscribed to '.$plan->name.' Package',
    ];

    $admin = User::role('Admin')->get();
 

        // Handle the incoming event...
        return $payload;
    }



}
