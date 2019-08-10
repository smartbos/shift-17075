@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">네이버 예약 파일 최종 업로드 시간</div>
                <div class="card-body">
                    @forelse($lastNaverReservationFileUploadedAt as $at)
                        <p @if( ! $at['today']) class="alert-danger" @endif>{{$at['branch']->name}} {{$at['uploadedAt']}}</p>
                    @empty
                        <p>No data yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">오늘 예약자 정보</div>

                <div class="card-body">
                    @forelse($todayReservationGroups as $todayReservationGroup)
                        @forelse($todayReservationGroup as $reservation)
                            @if($loop->first)
                            <h3>{{ $reservation->branch->name }}</h3>
                            @endif
                            <div>
                                <span>{{ $reservation->name }}</span>
                                <span>{{ $reservation->from->format('H:i') }}</span>
                                <span>{{ $reservation->to->format('H:i') }}</span>
                                <span>{{ $reservation->room }}</span>
                            </div>
                        @empty
                            <p>예약이 없습니다.</p>
                        @endforelse
                    @empty
                        <p>예약이 없습니다.</p>
                    @endforelse
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">예약 정보 문자로 입력</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="/reservations" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea class="form-control" name="sms"></textarea>
                        </div>

                        <input type="submit" class="btn btn-primary" value="등록">
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">예약 정보 파일로 입력</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="/reservations" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <select name="branch_id" class="form-control mb-3" required>
                            <option value="">지점 선택</option>
                            <option value="1">연신내점</option>
                            <option value="2">구산역점</option>
                        </select>

                        <div class="form-group">
                            <input type="file" class="form-control-file" name="xls">
                        </div>

                        <input type="submit" class="btn btn-primary" value="업로드">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">코드</div>

                <div class="card-body">
                    @forelse($roomcodes as $code)
                        <p>{{ $code->room_type }}인실 {{$code->code}}</p>
                    @empty
                        <p class="alert-danger">no code today!</p>
                    @endforelse

                    <div>
                        <a href="http://ticket000.com" class="btn btn-primary" target="_blank">코드 생성하기</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">만료된 사물함</div>

                <div class="card-body">
                    @foreach($expiredLockers as $locker)
                        <div>
                            <span class="mr-2">{{ $locker->num }}</span>
                            <span class="mr-2">{{ $locker->username }}</span>
                            <span class="mr-2">{{ $locker->to }}</span>
                            <span class="mr-2">{{ $locker->password }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
