<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Http\Request;

class ChangeProductProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:changeprovider 
                            { product : ID of product} 
                            { provider : ID of provider }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the SMS provider of a product';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $productId = $this->argument('product');
        $providerId = $this->argument('provider');

        $endpoint = '/api/products/' . $productId . '/provider';
        
        $req = Request::create($endpoint, 'PUT', ['provider_id' => $providerId]);
        $res = app()->handle($req)->getData();
        $this->info(json_encode($res));
    }
}
