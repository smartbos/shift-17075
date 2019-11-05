@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">예약 정보 수정</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="/reservations/{{$reservation->id}}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @method('PUT')

{{ print_r($reservation->branch_id)}}
                            <div class="form-group">
                                <select name="branch_id">
                                    <option value="1" @if($reservation->branch_id == 1) selected @endif>연신내점</option>
                                    <option value="2" @if($reservation->branch_id == 2) selected @endif>구산역점</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="이름" value="{{$reservation->name}}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" placeholder="전화번호 뒤4자리" value="{{$reservation->phone}}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="room" placeholder="세미나실" value="{{$reservation->room}}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="from" placeholder="시작시간 Y-m-d H:i:s" value="{{$reservation->from}}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="to" placeholder="종료시간 Y-m-d H:i:s" value="{{$reservation->to}}">
                            </div>

                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
