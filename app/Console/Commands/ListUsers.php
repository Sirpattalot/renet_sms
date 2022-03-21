<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a list of users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $endpoint = '/api/users';
        
        $req = Request::create($endpoint, 'GET');
        $users = app()->handle($req)->getData();

        
        //convert to arrays for table and filter properties
        foreach ($users as &$userObj) {
            $userObj = (array) $userObj;
            $id = $userObj['id'];
            $number = PhoneNumber::where('user_id', $id)->first()->number;

            //Should have created a user resource here instead of unsetting repetitively.
            $userObj['number'] = $number;
            unset($userObj['email']);
            unset($userObj['id']);
            unset($userObj['created_at']);
            unset($userObj['updated_at']);
            unset($userObj['email_verified_at']);
        }

        //shouldn't hardcode table headings
         $this->table(
             ['Name', 'Product ID', 'Number'],
             $users
         );
    }
}
