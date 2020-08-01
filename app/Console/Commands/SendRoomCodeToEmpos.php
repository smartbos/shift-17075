<?php

namespace App\Console\Commands;

use App\Roomcode;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendRoomCodeToEmpos extends Command
{
    private $branches;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-room-code-to-empos';

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

        $this->setBranches();
    }

    private function setBranches() {
        $this->branches = [
            '1' => [
                'id' => config('empos.1.id'),
                'pw' => config('empos.1.pw'),
                'goods_no' => '7931'
            ],
            '2' => [
                'id' => config('empos.2.id'),
                'pw' => config('empos.2.pw'),
                'goods_no' => '7932'
            ],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roomCodes = Roomcode::select('date','code')
            ->where('date', '>', '2020-04-30')
            ->groupBy(['date', 'code'])
            ->get();

        foreach($this->branches as $branch) {
            $client = new Client([
                'cookies' => true
            ]);

            $response = $client->request('POST', 'http://manage.empos.kr/api/member-store', [
                'form_params' => [
                    'm' => 'login',
                    'id' => $branch['id'],
                    'pw' => $branch['pw']
                ]
            ]);

            foreach($roomCodes as $roomCode) {
                $response = $client->request('POST', 'http://manage.empos.kr/api/ticket', [
                    'headers' => [
                        'Referer' => 'http://manage.empos.kr/reservation',
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Host' => 'manage.empos.kr',
                        'Origin' => 'http://manage.empos.kr',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'form_params' => [
                        'no' => '',
                        'm' => 'add',
                        'category' => 'studyroom',
                        'sdate_date' => $roomCode->date,
                        'sdate_time_h' => '0',
                        'sdate_time_m' => '0',
                        'edate_date' => $roomCode->date,
                        'edate_time_h' => '23',
                        'edate_time_m' => '59',
                        'goods_no' => $branch['goods_no'],
                        'tel' => $this->tel($roomCode)
                    ]
                ]);

                $this->info('code for ' . $roomCode->date . ' was sent');

                sleep(1);
            }
        }
    }

    /**
     * @param $roomCode
     */
    private function tel($roomCode)
    {
        return '010-' . Str::substr($roomCode->code, 0, 4) . '-' . Str::substr($roomCode->code, 4, 4);
    }
}
