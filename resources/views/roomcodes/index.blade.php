@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">출입 코드</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>일시</th>
                                <th>코드</th>
                                <th>룸</th>
                            </tr>
                            @forelse($roomcodes as $code)
                                <tr>
                                    <td>{{ $code->date }}</td>
                                    <td>{{ $code->code }}</td>
                                    <td>{{ $code->room_type }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Nothing</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">출입 코드 추가</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="/roomcodes" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <select name="room_type" class="form-control">
                                    <option value="3">3인실</option>
                                    <option value="6">6인실</option>
                                    <option value="8">8인실</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="code" placeholder="code">
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="date" placeholder="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>

                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">출입 코드 파일로 추가</div>

                    <div class="card-body">
                        <form action="/roomcodes" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="file" class="form-control" name="file" placeholder="file">
                            </div>

                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
