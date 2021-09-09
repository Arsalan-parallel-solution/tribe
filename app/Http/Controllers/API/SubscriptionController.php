<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\User;
use Stripe;
use Session;
use Exception;
use Auth;
class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscription.create');
    }

    public function orderPost(Request $request)
    {
            $user = auth()->user();
            $input = $request->all();
            $token =  $request->stripeToken;
            $paymentMethod = $request->paymentMethod;
            try {

                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                
                if (is_null($user->stripe_id)) {
                    $stripeCustomer = $user->createAsStripeCustomer();
                }

                \Stripe\Customer::createSource(
                    $user->stripe_id,
                    ['source' => $token]
                );

                //$user->subscription('test',$input['plane'])->cancelled();
                if ($user->subscribed('test')) {
                   // return $user->subscribed('test');
                }

                $user = User::find(Auth::user()->id);

                $invoices = $user->invoices();

                return dd($invoices);

                 //$user = User::find(31);

                $subscriptionItem = $user->subscription('test');

                // return dd($subscriptionItem);

                //$user->subscription('test', $input['plane'])->cancel();
                //$user->subscription('test', $input['plane'])->cancelNow();

                $user->newSubscription('test',$input['plane'])
                    ->create($paymentMethod, [
                    'email' => $user->email,
                ]); 

                return back()->with('success','Subscription is completed.');

            } catch (Exception $e) {
                return back()->with('success',$e->getMessage());
            }
            
    }
}
