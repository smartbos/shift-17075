<?php

namespace App\Console\Commands;

use App\Roomcode;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateRoomcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp:roomcode';

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
        $rooms = [
            [
                'branch_id' => 1,
                'room_type' => '세미나실 3인실'
            ],
            [
                'branch_id' => 1,
                'room_type' => '세미나실 6인실'
            ],
            [
                'branch_id' => 1,
                'room_type' => '세미나실 8인실'
            ],
            [
                'branch_id' => 2,
                'room_type' => '세미나실 A'
            ],
            [
                'branch_id' => 2,
                'room_type' => '세미나실 B'
            ],
        ];

        $date = Carbon::createFromDate(2020, 1, 1);
        $end = Carbon::createFromDate(2020, 12, 31);

        for ($date; $date <= $end; $date->addDay()) {
            $code = implode("", [
                '0',
                rand(0, 9),
                rand(0, 9),
                rand(0, 9),
                rand(0, 9),
                rand(0, 9),
                rand(0, 9),
                rand(0, 9),
            ]);

            foreach($rooms as $room) {
                Roomcode::create([
                    'date' => $date->format('Ymd'),
                    'code' => $code,
                    'room_type' => $room['room_type'],
                    'branch_id' => $room['branch_id']
                ]);
            }
        }
    }
}
