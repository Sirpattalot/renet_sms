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
        
        $req = Request::create($endpoint, 'GET');
        $res = app()->handle($req)->getData();
        $providers = $res->data;

        //convert stdClasses to Arrays for table
        foreach ($providers as &$providerObj) {
            $providerObj = (array) $providerObj;
        }

        //shouldn't hardcode table headings
         $this->table(
             ['ID', 'Name', 'Send price', 'Receive price', 'Fail rate'],
             $providers
         );
    }
}
