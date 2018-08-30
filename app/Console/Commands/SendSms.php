<?php

namespace App\Console\Commands;

use App\Reservation;
use App\SmsSender;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SMS 발송';

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
        /*
         * 0분, 20분, 30분, 50분에 실행
         *
         * 0분 - 해당 시간 1시간 후에 시작하는 '강신구'님 예약이 있는지 확인. 있으면 전송.
         * 20분 - 해당 시각 30분에 시작하는 예약이 있는지 확인. 있으면 전송.
         * 30분 - 해당 시간 1시간 후의 30분에 시작하는 '강신구'님 예약이 있는지 확인하고 전송.
         * 50분 - 해당 시각 1시간 후에 0분에 시작하는 예약이 있으면 전송.
         */

        $reservation = new Reservation();
        $reservations = $reservation->toSendSms();

        Log::channel('bugsnag')->info($reservations);

        $smsSender = new SmsSender();
        $smsSender->send($reservations);
    }
}
