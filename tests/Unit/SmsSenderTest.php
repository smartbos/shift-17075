<?php

namespace Tests\Unit;

use App\Customer;
use App\Reservation;
use App\Roomcode;
use App\SmsSender;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmsSenderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $reservation = factory(Reservation::class)->create([
            'phone' => '3707'
        ]);
        $collection = new Collection();
        $collection->push($reservation);

        $roomcode = factory(Roomcode::class)->create(
            [
                'room_type' => $reservation->room,
                'branch_id' =>  $reservation->branch_id
            ]
        );

        $customer =  factory(Customer::class)->create([
           'name' => $reservation->name,
           'phone' => '01029563707',
           'phone_last' => '3707'
        ]);

        $smsSender = new SmsSender();
        $res = $smsSender->send($collection);
        dd(json_decode($res->getBody()));
    }
}
