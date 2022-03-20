<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Http\Request;

class ListProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'providers:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a list of sms providers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $endpoint = '/api/smsproviders';
        
        //dd($endpoint);
        $req = Request::create($endpoint, 'GET');
        $res = app()->handle($req)->getData();



        $providers = $res->data;

        foreach ($providers as &$providerObj) {
            $providerObj = (array) $providerObj;
        }
        
         $this->table(
             ['ID', 'Name', 'Send price', 'Receive price', 'Fail rate'],
             $providers
         );
    }
}
