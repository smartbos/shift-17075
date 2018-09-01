<?php

namespace App\Http\Controllers;

use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reservations/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rows = (new FastExcel())->import($request->file('xls'));

        // insert
        foreach ($rows as $row) {
            if($row['상태']) {
                $insertData = $this->transform($row);
                if ($row['상태'] == '확정' && $row['결제상태'] == '결제완료') {
                    try {
                        Reservation::create($insertData);
                    } catch (\PDOException $e) {

                    }
                }

                if ($row['상태'] == '취소') {
                    $reservation = Reservation::where($insertData)->first();
                    if ($reservation) {
                        $reservation->delete();
                    }
                }
            }
        }

        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    private function transform($row)
    {
        $result['room'] = $row['상품'];
        $result['name'] = $row['예약자'];

        $result['phone'] = mb_substr($row['전화번호'], - 4, 4);
        $date = $fromDate = $toDate = mb_substr($row['이용일시'], 0, strpos($row['이용일시'], '('));
        $time = mb_substr($row['이용일시'], strpos($row['이용일시'], ')'));

        $timeArray = explode(' ', $time);
        $timeArray2 = explode('~', $timeArray[1]);
        $fromArray = explode(':', $timeArray2[0]);
        $toArray = explode(':', $timeArray2[1]);
        if ($timeArray[0] == '오후') { // 오후 12:00~2:00 | 11:00~12:00 |
            if($fromArray[0] != 12) {
                $fromArray[0] = $fromArray[0] + 12; // 24 | 23
            }

            $toArray[0] = $toArray[0] + 12; // 14 | 24

            if($toArray[0] == 24) {
                $toArray[0] = 0;
                $toDateCarbon = Carbon::createFromFormat('y. m. d.', $toDate);
                $toDate = $toDateCarbon->addDay(1)->format('y. m. d.');
            }
        } else {
            if ($toArray[0] < $fromArray[0]) {
                $toArray[0] = $toArray[0] + 12;
            }
        }

        $fromString = implode(':', $fromArray);
        $toString = implode(':', $toArray);

        //dd($date);
        $result['from'] = Carbon::createFromFormat('y. m. d. H:i', $fromDate . $fromString);
        $result['to'] = Carbon::createFromFormat('y. m. d. H:i', $toDate . $toString);

        return $result;
    }
}
