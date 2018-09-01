<?php

namespace Tests\Unit;

use App\Reservation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSms()
    {
        $reservation = resolve(Reservation::class);

        $sms = "[네이버 예약] 예약 확정 안내
[Web발신]
일공공, 새로운 예약이 확정되었습니다. 예약 내역을 확인해 보세요.

- 예약번호: 27529087
- 예약자명: 이현석
- 전화번호: 010-2956-3707
- 예약상품: 세미나실 3인실
- 이용기간: 2018.09.01.(토) 오후 11:00~오전 0:00
- 네이버페이 결제상태: 결제완료
- 결제수단: 실시간계좌이체
- 결제금액: 세미나실 3인실(1) 4,000원 = 4,000원
* 예약 내역 자세히 보기:";

        $reservation->storeUsingSms($sms);
    }
}
