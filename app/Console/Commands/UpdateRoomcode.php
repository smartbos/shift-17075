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
        $date = Carbon::createFromDate(2019, 12, 16);
        $end = Carbon::createFromDate(2019, 12, 31);

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

            Roomcode::where('date', $date->format('Y-m-d'))
                ->update([
                    'code' => $code
                ]);
        }
    }
}
