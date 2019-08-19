<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testToSendSms()
    {
        $reserveTime1 = Carbon::create(2019, 5, 21, 12);
        $reserveTime2 = Carbon::create(2019, 5, 21, 13);

        $cronTime1 = (clone $reserveTime1)->subMinute(10);
        $cronTime2 = (clone $reserveTime2)->subMinute(10);

        factory(Reservation::class)->create([
            'name' => 'a',
            'from' => $reserveTime1,
        ]);

        factory(Reservation::class)->create([
            'name' => 'b',
            'from' => $reserveTime2,
        ]);

        factory(Reservation::class)->create([
            'name' => 'c',
            'from' => $reserveTime2,
        ]);

        Carbon::setTestNow($cronTime1);

        $reservation = new Reservation();
        $reservations = $reservation->toSendSms();

        $this->assertEquals('a', $reservations->first()->name);

        Carbon::setTestNow($cronTime2);

        $reservation = new Reservation();
        $reservations = $reservation->toSendSms();

        $this->assertCount(2, $reservations);
        $this->assertEquals('b', $reservations->first()->name);
        $this->assertEquals('c', $reservations->last()->name);
    }
}
