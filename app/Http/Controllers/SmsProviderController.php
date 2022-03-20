<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsProvider;
use App\Http\Resources\SmsProviderResource;

class SmsProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SmsProviderResource::collection(SmsProvider::paginate(20));
    }
}
