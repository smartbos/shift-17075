<?php
/**
 * Created by PhpStorm.
 * User: hyunseoklee
 * Date: 2018. 8. 29.
 * Time: 오전 5:17
 */

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SmsSender
{
    public function send(Collection $reservations)
    {
        $client = new \GuzzleHttp\Client();

        foreach ($reservations as $reservation) {
            $customer = Customer::where('name', $reservation->name)
                ->where('phone_last', $reservation->phone)
                ->first();

            $now = Carbon::now();

            $roomcode = Roomcode::where('date', $now->format('Ymd'))
                ->where('room_type', mb_substr($reservation->room, 5, 1))
                ->first();

            $content = "[일공공] {$now->format('m월 d일')}
{$reservation->from->format('H:i')}~{$reservation->to->format('H:i')} 
출입코드 {$roomcode->code}
입장 및 이용방법https://goo.gl/aCyaTD";

            if ($customer) {
                $res = $client->request('POST', 'http://biz.moashot.com/EXT/URLASP/mssendUTF.asp', [
                    'form_params' => [
                        'uid' => 'ilgonggong',
                        'pwd' => 'Lhs81@ahdk',
//                'commType' => '1',
//                'commCode' => md5('Lhs81@ahdk'),
                        'sendType' => '3',
                        'title' => $reservation->name,
                        'toNumber' => $customer->phone,
                        'contents' => $content,
                        'fromNumber' => '01029563707',
//            'nType' => '2',
//            'indexCode' => '100',
//            'returnUrl' => 'http://sms.bookcafe100.com/smsCallback'
                    ]
                ]);
            }
        }

    }
}
