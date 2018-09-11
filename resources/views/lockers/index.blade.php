@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">사물함</div>

                    <div class="card-body">
                        @foreach($lockers as $locker)
                            <div class="p-2">
                                <form action="/lockers/{{ $locker->id }}" method="POST" class="form-inline">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                                    <span class="mr-2" style="width:50px;">{{$locker->num}}</span>
                                    <span class="mr-2"><input type="text" name="username" value="{{$locker->username}}" class="form-control"></span>
                                    <span class="mr-2"><input type="text" name="from" value="{{$locker->from}}" class="form-control" placeholder="YYYYMMDD"></span>
                                    <span class="mr-2">{{$locker->to}}</span>
                                    <span class="mr-2">{{$locker->password}}</span>
                                    <span class="mr-2"><input type="submit" class="btn" value="수정" class="form-control"></span>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
