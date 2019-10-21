<?php
/**
 * Created by PhpStorm.
 * User: hyunseoklee
 * Date: 2018. 8. 29.
 * Time: 오전 5:17.
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

            /* TODO
             * 기본적으로 3인실 코드를 보낸다.
             * 같은 날 다른 방 예약이 있으면 해당실 코드를 보낸다.
             * 해당실 코드가 없으면 그냥 3인실 코드를 보낸다.
             *
             * 같은 날 다른 방 예약이 있는지 관리자에게 문자로 알려주는 기능을 작성한다.
             */
            $roomcode = Roomcode::where('date', $now->format('Ymd'))
                ->where('room_type', $reservation->room)
                ->where('branch_id', $reservation->branch_id)
                ->first();

            $branch = Branch::find($reservation->branch_id);

            $content = "[{$branch->name}] {$now->format('m월 d일')}
{$reservation->from->format('H:i')}~{$reservation->to->format('H:i')} 
출입코드 {$roomcode->code}
{$branch->instruction_link}";

            if ($customer) {
//                $res = $client->request('POST', 'http://biz.moashot.com/EXT/URLASP/mssendUTF.asp', [
//                    'form_params' => [
//                        'uid' => 'ilgonggong',
//                        'pwd' => 'Lhs81@ahdk',
////                'commType' => '1',
////                'commCode' => md5('Lhs81@ahdk'),
//                        'sendType' => '3',
//                        'title' => $reservation->name,
//                        'toNumber' => $customer->phone,
//                        'contents' => $content,
//                        'fromNumber' => '01029563707',
////            'nType' => '2',
////            'indexCode' => '100',
////            'returnUrl' => 'http://sms.bookcafe100.com/smsCallback'
//                    ],
//                ]);

                $res = $client->request('POST', 'https://apis.aligo.in/send/', [
                    'form_params' => [
                        'key' => 'k74g9i3eg6gnuga9kifsj3nfiur9tb0j',
                        'user_id' => 'smartbosslee',
                        'sender' => '01029563707',
                        'receiver' => $customer->phone,
                        'msg' => $content,
                        'title' => $reservation->name,
                        'testmode_yn' => 'N'
                    ]
                ]);

                return $res;
            }
        }
    }
}
