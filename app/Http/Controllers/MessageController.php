<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\PhoneNumber;

use App\Events\SmsOutbound;

class MessageController extends Controller
{

    private function buildQueryFromParams() {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Builds the database query from the url params. Messy.

        $filters = array();
        $query = Message::where([]);

        // ?userId=1
        if ($request->userId) {
            $phone = PhoneNumber::where('user_id', $request->userId)->first()->number;
            $query->where('to', $phone)->orWhere('from', $phone);
        }

        // ?from=04XXXXX
        if ($request->from) {
            $filters['from'] = $request->from;
        }

        // ?to=04XXXXXX
        if ($request->to) {
            $filters['to'] = $request->to;
        }

        // ?productId=1
        if ($request->productId) {
            $filters['product_id'] = $request->productId;
        }

        // ?status=failed
        if ($request->status) {
            $filters['status'] = $request->status;
        }

        //Filter out numbers not associated with any tenants
        //not ideal getting all phone numbers for comparison. Should be apparent in db table.

        // ?sent=true
        if ($request->sent) {
            $phone = PhoneNumber::pluck('number');
            $query->whereIn('from', $phone);
        }
        // ?received=true
        if ($request->received) {
            $phone = PhoneNumber::pluck('number');
            $query->whereIn('to', $phone);
        }

        $query->where($filters);

        return MessageResource::collection($query->get());
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        //Check phone number exists, then get associated sms-provider.
        //Store the message locally before dispatch event for smsprovider.

    
        $phone = PhoneNumber::where('number', $request->from)->first();

        if (empty($phone)) {
            return response()->json([
                'error' => 'Phone number of sender does not exist.'
            ], 404);            
        }

        //get productId for this phone number
        $product = $phone->product;
        $productId = $product->id;

        //get current sms provider from product
        $smsProviderId = $product->provider_id;

        // save message
        $message = new Message;
        $message->from = $request->from;
        $message->to = $request->to;
        $message->body = $request->body;
        $message->provider_id = $smsProviderId;
        $message->product_id = $productId;
        $message->save();

        //Dispatch event to trigger listener @ fake SMS provider
        SmsOutbound::dispatch($message);

        return response()->json([
            'success' => "message received by provider"
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
