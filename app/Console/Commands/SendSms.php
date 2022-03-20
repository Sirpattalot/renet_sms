<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\Models\PhoneNumber;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send 
                            "{body : body of message}" 
                            {from : from phone number}
                            {recipients* : list of space-separated phone numbers}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS';


    //Checks that the 'from' number is actually associated with a tenant/user
    private function isFromNumberValid($from) {
        $sender = PhoneNumber::where('number', $from)->first();
        if (empty($sender)) {
            return false;
        }
        return true;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recipients = $this->argument('recipients');
        $from = $this->argument('from');
        $body = $this->argument('body');

        //This is validated on API endpoint, but why hit API when it can be checked here?
        if (!$this->isFromNumberValid($from)) {
            $this->error('No tenant with number ' . $from);
            $this->newline();
            return;
        }

        $this->info('Message body: ' . $body);
        $this->newLine();

        foreach ($recipients as $recipient) {
            $this->info('Sending to ' . $recipient . '...');

            $req = Request::create('api/messages', 'POST', [
                'from' => $from,
                'to' => $recipient,
                'body' => $body
            ]);

            $res = app()->handle($req)->getData();
            $this->info(json_encode($res));
            $this->newLine();
        }

    }
}
