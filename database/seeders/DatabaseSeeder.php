<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Product;
use App\Models\SmsProvider;
use App\Models\PhoneNumber;
use App\Models\Message;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();


        $plivo = SmsProvider::create([
            'name' => 'Plivo',
            'fail_rate' => 60,
            'receive_price' => 3,
            'send_price' => 25
        ]);

        $twilio = SmsProvider::create([
            'name' => 'Twilio',
            'fail_rate' => 50,
            'receive_price' => 2,
            'send_price' => 30
        ]);

        $crmProd = Product::create([
            'name' => 'ReNet CRM',
            'provider_id' => $plivo->id
        ]);

        $syncProd = Product::create([
            'name' => 'ReNet Sync',
            'provider_id' => $twilio->id
        ]);


        User::factory()->create([
            'name' => 'Patrick Flynn',
            'password' => bcrypt('password'),
            'product_id' => $crmProd->id
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'password' => bcrypt('password'),
            'product_id' => $crmProd->id
        ]);


        User::factory()->create([
            'name' => 'Alice Jones',
            'password' => bcrypt('password'),
            'product_id' => $syncProd->id
        ]);

        User::factory()->create([
            'name' => 'Bob Black',
            'password' => bcrypt('password'),
            'product_id' => $syncProd->id
        ]);

        PhoneNumber::create([
            'number' => '0478538266',
            'user_id' => 1,
            'product_id' => $plivo->id
        ]);

        PhoneNumber::create([
            'number' => '0420364396',
            'user_id' => 2,
            'product_id' => $plivo->id
        ]);

        PhoneNumber::create([
            'number' => '0417272427',
            'user_id' => 3,
            'product_id' => $twilio->id
        ]);

        PhoneNumber::create([
            'number' => '0412345678',
            'user_id' => 4,
            'product_id' => $twilio->id
        ]);

        // Message::create([
        //     'from' => '0420364396',
        //     'to' => '0478538266',
        //     'status' => 'sent'  ,
        //     'body' => 'Hi there, how are you?',
        //     'provider_id' => $plivo->id,
        //     'send_price' => $plivo->send_price,
        //     'receive_price' => $plivo->receive_price,
        //     'product_id' => 2
        // ]);

        // Message::create([
        //     'from' => '0478538266',
        //     'to' => '0417272427',
        //     'status' => 'sent'  ,
        //     'body' => 'Reminder: appointment tomorrow at 9am.',
        //     'provider_id' => $plivo->id,
        //     'send_price' => $plivo->send_price,
        //     'receive_price' => $twilio->receive_price,
        //     'product_id' => 1
        // ]);

        // Message::create([
        //     'from' => '0412123123',
        //     'to' => '0420364396',
        //     'status' => 'sent'  ,
        //     'body' => 'Testing 1,2,3',
        //     'provider_id' => $plivo->id,
        //     'send_price' => 0,
        //     'receive_price' => $plivo->receive_price,
        //     'product_id' => 1
        // ]);
    }
}
