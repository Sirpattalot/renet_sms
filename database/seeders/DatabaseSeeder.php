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
            'name' => 'Lucy Hume',
            'password' => bcrypt('password'),
            'product_id' => $syncProd->id
        ]);

        PhoneNumber::create([
            'number' => '0478538265',
            'user_id' => 1,
            'product_id' => $twilio->id
        ]);

        PhoneNumber::create([
            'number' => '0420364395',
            'user_id' => 2,
            'product_id' => $plivo->id
        ]);

        Message::create([
            'from' => '0420364395',
            'to' => '0478538265',
            'status' => 'sent'  ,
            'body' => 'Hi there, how have you been?',
            'provider_id' => $twilio->id,
            'send_price' => $plivo->send_price,
            'receive_price' => $twilio->receive_price,
            'product_id' => 2
        ]);

        Message::create([
            'from' => '0478538265',
            'to' => '0420364395',
            'status' => 'sent'  ,
            'body' => 'Been well thanks, how about yourself?',
            'provider_id' => $plivo->id,
            'send_price' => $twilio->send_price,
            'receive_price' => $plivo->receive_price,
            'product_id' => 1
        ]);

        Message::create([
            'from' => '00000',
            'to' => '0420364395',
            'status' => 'sent'  ,
            'body' => 'Blahblah',
            'provider_id' => $plivo->id,
            'send_price' => 0,
            'receive_price' => $plivo->receive_price,
            'product_id' => 1
        ]);
    }
}
