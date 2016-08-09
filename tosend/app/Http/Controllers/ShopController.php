<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\PaymentResults;
use App\StartedPayments;
use App\UsersCoins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

use App\User;
use Auth;
use Mail;
use DB;
use App\Drop as Drop;
use Mdb\PayPal\Ipn\Event\MessageVerifiedEvent;
use Mdb\PayPal\Ipn\Event\MessageInvalidEvent;
use Mdb\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use Mdb\PayPal\Ipn\ListenerBuilder\Guzzle\InputStreamListenerBuilder as ListenerBuilder;

class ShopController extends Controller {


    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        if(Auth::check()){
            $payment_id = md5(uniqid(mt_rand(), true));

            return view('shop', array('payment_id' => $payment_id));
        }
        else{
            return redirect("/");
        }
    }

    public function cancelPayment()
    {
        if(Auth::check()){
            Log::info("Payment cancelled. User: ".Auth::user()->email);
            return redirect("/shop");
        }
        else{
            return redirect("/");
        }
    }

    public function successPayment()
    {
        if(Auth::check()){
            Log::info("Payment finished. User: ".Auth::user()->email);
            return redirect("/coinStatistics");
        }
        else{
            return redirect("/");
        }
    }
    
    public function startPayment(Request $request){
        $r =  $request;

        if(!empty($_POST['payment_id'])){
            if(Auth::check()){
                $startedPayment = new StartedPayments;
                $startedPayment->user_id = Auth::user()->id;
                $startedPayment->payment_id = $_POST['payment_id'];
                $startedPayment->save();

                Log::info("Payment started. User: ".Auth::user()->email." Payment id: ".$_POST['payment_id']);
            }
        }
    }
    
    public function payPalIPN(){
        // Common setup for API credentials
        $listenerBuilder = new ListenerBuilder();

        $listenerBuilder->useSandbox(); // use PayPal sandbox

        $listener = $listenerBuilder->build();

        $listener->onVerified(function (MessageVerifiedEvent $event) {
            $ipnMessage = $event->getMessage();

            $txn_id = $_POST['txn_id'];
            $status = $_POST['payment_status'];
            $payment_id = $_POST['custom'];

            $startedPayment = StartedPayments::where('payment_id', '=', $payment_id)->first();

            $user = User::where('id', '=', $startedPayment->user_id)->first();

            $oldResult = PaymentResults::where('txn_id', '=', $txn_id)->where('status', '=', 'Completed')->first();

            $paymentResult = new PaymentResults;
            $paymentResult->txn_id = $_POST['txn_id'];
            $paymentResult->status = $_POST['payment_status'];
            $paymentResult->first_name = $_POST['first_name'];
            $paymentResult->last_name = $_POST['last_name'];
            $paymentResult->payer_email = $_POST['payer_email'];
            $paymentResult->payment_id = $_POST['custom'];
            $paymentResult->mc_gross = $_POST['mc_gross'];
            $paymentResult->user_id = $user->id;
            $paymentResult->save();

            if(!$oldResult && $status == "Completed"){

                $userCoins = new UsersCoins;
                $userCoins->user_id = $user->id;
                $userCoins->isAdded = TRUE;

                $amount = $_POST['mc_gross'];

                if($amount == '5.00'){
                    $user->coins += Config::get('app.5$');
                    $userCoins->amount = Config::get('app.5$');

                }
                elseif ($amount == '12.00'){
                    $user->coins += Config::get('app.12$');
                    $userCoins->amount = Config::get('app.12$');

                }
                elseif ($amount == '30.00'){
                    $user->coins += Config::get('app.30$');
                    $userCoins->amount = Config::get('app.30$');
                }

                $userCoins->save();
                $user->save();
                Log::info("Payment completed with status: ".$status.", payment_id: ".$_POST['custom'].", amount: ".$amount.", user: ".$user->email);

            }
            else{
                Log::info("Payment completed with status: ".$status.", payment_id: ".$_POST['custom']);
            }

            Log::info($ipnMessage);

            // IPN message was verified, everything is ok! Do your processing logic here...
        });

        $listener->onInvalid(function (MessageInvalidEvent $event) {
            $ipnMessage = $event->getMessage();
            Log::info("IPN message was was invalid, something is not right!");
            Log::info($ipnMessage);
            // IPN message was was invalid, something is not right!
        });

        $listener->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
            $error = $event->getError();
            Log::info("Some thing went wrong in the payment !");
            Log::info($error);
            // Something bad happend when trying to communicate with PayPal! Do your logging here...
        });

        $listener->listen();

    }
    
}
