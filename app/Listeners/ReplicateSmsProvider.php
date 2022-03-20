<?php

namespace App\Listeners;

use App\Events\SmsOutbound;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Message;
use App\Models\PhoneNumber;

//Implement Job/Queue to create delay between sending and success/fail
class ReplicateSmsProvider implements ShouldQueue
{

    /**
     * TODO
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }


    /**
     * Handle the event. 
     *
     * @param  \App\Events\SmsOutbound  $event
     * @return void
     */
    //Should be using an API endpoint for the sms provider, but I didn't want to be making requests from routes.
    //I also assume it's bad practise passing a model in an event, and mutating it... Seems to be working though.
    public function handle(SmsOutbound $event)
    {

        $message = $event->message;
        $provider = $message->provider;

        $failRate = $provider->fail_rate;
        $sendPrice = $provider->send_price;

        $recipientPhoneNumber = PhoneNumber::where('number', $message->to)->first();//->product->provider;
        
        //set a receive price for recipient, only if they're a tenant.
        if (!empty($recipientPhoneNumber)) {
            $recipientProvider = $recipientPhoneNumber->product->provider;
            $message->receive_price = $recipientProvider->receive_price;
        }

        // Calculate online status of sms provider using the fail rate.
        if ( rand(1, 100) < $failRate) {
            $message->status = 'failed';
        } else {
            $message->status = 'sending';
        }
        //$message->save();

        // Timed callback to update message status to sent/not sent
        // For now, always assume the message was sent.

        // if (messageSentSuccessfully) {
            if (strcmp($message->status, 'failed') !== 0) {
                $message->status = 'sent';
                $message->send_price = $sendPrice;
            }
        // } else {
            //$message->status = 'failed';
        //}

        $message->save();


    }
}
