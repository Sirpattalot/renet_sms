<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Http\Request;

class ListProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a list of existing products';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $endpoint = '/api/products';
        
        //dd($endpoint);
        $req = Request::create($endpoint, 'GET');
        $res = app()->handle($req)->getData();



        $products = $res->data;

        foreach ($products as &$msgObj) {
            $msgObj = (array) $msgObj;
        }

         $this->table(
             ['ID', 'Name', 'Provider ID'],
             $products
         );
    }
}
