@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
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
                <div class="card-header">사물함</div>

                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
