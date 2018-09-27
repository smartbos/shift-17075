<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Webklex\IMAP\Facades\Client;

class CheckGmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = Client::account('default');
        $client->connect();
        $folders = $client->getFolders();
//        foreach($folders as $folder) {
//            dd($folder);
//        }
//        exit;
        $inbox = $client->getFolder('INBOX');
//        dd($inbox);
//        $mails = $inbox->query()->since(Carbon::now()->format('d.m.Y'))->limit(10)->get();
        dd($inbox->messages()->all()->get());
    }
}
