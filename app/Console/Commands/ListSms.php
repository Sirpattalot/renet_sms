<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\Models\Message;

class ListSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:list
                            { --billing : whether to list billing details }
                            { --sent : filter by sent }
                            { --received : filter by received }
                            { --to= : filter by to phone number }
                            { --from= : filter by from phone number }
                            { --productId= : filter by productId }
                            { --userId= : filter by userId }
                            { --status= : filter by message status }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List sms messages.';

    // Shouldn't hardcode table headings like this.
    private function getTableHeadings() {
        $tableHeadings = ['From', 'To', 'Status', 'Body', 'Created'];

        if ($this->option('billing')) {

            $tableHeadings = ['From', 'To', 'Send Price', 'Receive Price', 'Created'];

            if ($this->option('to')) {
                $tableHeadings = ['From', 'To', 'Receive Price', 'Created'];
            }

            if ($this->option('from')) {
                $tableHeadings = ['From', 'To', 'Send Price', 'Created'];
            }
        }

        return $tableHeadings;
    }

    // build api endpoint url from command options
    private function getApiEndpoint($commandOptions) {
        $endpoint = 'api/messages';
        $queryParams = '';

        //map command options to url params.
        foreach($commandOptions as $key => $val) {
            if (!empty($val)) {

                //converting bool to string results in 1 or 0. Not true/false.
                if (is_bool($val)) {
                    $val = $val ? 'true' : 'false';
                }

                $queryParams .= '&' . $key . '=' . $val;
            }   
        }
        //should be done within the loop. How to get index within foreach loop if you're using the $key
        if (!empty($queryParams)) {
            $queryParams = '?' . substr($queryParams, 1);
        }

        return $endpoint . $queryParams;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tableHeadings = $this->getTableHeadings();
        $commandOptions = $this->options();
        $apiEndpoint = $this->getApiEndpoint($commandOptions);

        $req = Request::create($apiEndpoint, 'GET');
        $res = app()->handle($req)->getData();

        //do proper error handling
        if (!isset($res->data)) {
            $this->error('error');
            return;
        }

        $messages = $res->data;

        if (count($messages) <= 0) {
            $this->error('no messages');
            return;
        }

        $sendTotal = 0;
        $receiveTotal = 0;

        $hasReceivePriceColumn = isset($messages[0]->receive_price);
        $hasSendPriceColumn = isset($messages[0]->send_price);

        //convert rows to array for table and calculate totals.
        foreach ($messages as &$msgObj) {
            if ($hasSendPriceColumn) {
                $sendTotal += $msgObj->send_price;
            }
            if ($hasReceivePriceColumn) {
                $receiveTotal += $msgObj->receive_price;
            }
            $msgObj = (array) $msgObj;
        }

        $this->table(
            $tableHeadings,
            $messages
        );

        if ($this->option('billing')) {
            $this->info('Subtotal sent: $' . number_format(($sendTotal /100), 2, '.', ' '));
            $this->info('Subtotal received: $' . number_format(($receiveTotal /100), 2, '.', ' '));    
        }
    }
}
