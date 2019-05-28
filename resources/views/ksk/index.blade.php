@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                강신구님을 위한 세미나실 예약 페이지입니다.
            </div>
            <div class="card">
                <div class="card-header">예약 추가</div>

                <div class="card-body">
                    <form action="/ksk" method="POST">
                        <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label">날짜</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="from">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="room" class="col-sm-2 col-form-label">세미나실</label>
                            <div class="col-sm-10">
                                <select name="room" class="form-control">
                                    <option value="3">3인실</option>
                                    <option value="6">6인실</option>
                                    <option value="8">8인실</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label">시작 시</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="start">
                                    @for($i = 0; $i < 24; $i++)
                                        @php
                                            if($i < 10)
                                                $iString = "0" . $i;
                                            else
                                                $iString = $i;
                                        @endphp
                                        <option value="{{ $iString }}00">{{ $iString }}:00</option>
                                        <option value="{{ $iString }}30">{{ $iString }}:30</option>
                                    @endfor
                                        <option value="2400">24:00</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label">종료 시각</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="end">
                                    @for($i = 0; $i < 24; $i++)
                                        @php
                                            if($i < 10)
                                                $iString = "0" . $i;
                                            else
                                                $iString = $i;
                                        @endphp
                                        <option value="{{ $iString }}00">{{ $iString }}:00</option>
                                        <option value="{{ $iString }}30">{{ $iString }}:30</option>
                                    @endfor
                                    <option value="2400">24:00</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">예약 정보</div>

                <div class="card-body">
                    @forelse($reservations as $reservation)
                    @if ($loop->first)
                    <table class="table">
                        <tr>
                            <th>이름</th>
                            <th>전화번호</th>
                            <th>룸</th>
                            <th>시작시간</th>
                            <th>종료시간</th>
                        </tr>
                        @endif
                        <tr>
                            <td>{{ $reservation->name }}</td>
                            <td>{{ $reservation->phone }}</td>
                            <td>{{ $reservation->room }}</td>
                            <td>{{ $reservation->from }}</td>
                            <td>{{ $reservation->to }}</td>
                        </tr>
                        @if($loop->last)
                    </table>
                    @endif
                    @empty
                    No content.
                    @endforelse
                </div>
            </div>

            <div class="card mt-3">
                신청 내역
                확정 내역
                결제 금액
            </div>

        </div>
    </div>
</div>
@endsection
