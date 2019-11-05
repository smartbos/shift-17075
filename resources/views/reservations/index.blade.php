@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">예약 정보 직접 입력</div>

                    <div class="card-body">
                        <form action="/reservations" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <select name="branch_id" class="form-control">
                                    <option value="1">연신내점</option>
                                    <option value="2">구산역점</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="이름">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" placeholder="전화번호 뒤4자리">
                            </div>
                            <div class="form-group">
                                <select name="room" class="form-control">
                                    <option value="세미나실 3인실">세미나실 3인실</option>
                                    <option value="세미나실 6인실">세미나실 6인실</option>
                                    <option value="세미나실 8인실">세미나실 8인실</option>
                                    <option value="세미나실 A">세미나실 A</option>
                                    <option value="세미나실 B">세미나실 B</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="from" placeholder="시작시간 Y-m-d H:i:s">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="to" placeholder="종료시간 Y-m-d H:i:s">
                            </div>

                            <input type="submit" class="btn btn-primary" value="등록">
                        </form>
                    </div>
                </div>
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

                            <select name="branch_id" class="form-control mb-3" required>
                                <option value="">지점 선택</option>
                                <option value="1">연신내점</option>
                                <option value="2">구산역점</option>
                            </select>

                            <div class="form-group">
                                <input type="file" class="form-control-file" name="xls">
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="type" value="1">
                                <label class="form-check-label" for="type">강신구</label>
                            </div>

                            <input type="submit" class="btn btn-primary" value="업로드">
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <form action="/reservations" method="post">
                        @csrf

                        <input type="text" name="name">

                        <input type="text" name="phone">

                        <select>
                            <option value="세미나실 3인실">세미나실 3인실</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                @if($unregisteredCustomers)
                    <div class="card">
                        <div class="card-header">미등록 고객</div>

                        <div class="card-body">
                            <ul>
                                @foreach($unregisteredCustomers as $customer)
                                    <li>{{ $customer->name }} {{ $customer->phone }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <h5>등록하기</h5>
                            <form action="/customers" method="post" class="form-inline">
                                {{ csrf_field() }}
                                <input type="text" name="name" class="form-control mr-1" placeholder="name">
                                <input type="text" name="phone" class="form-control mr-1" placeholder="phone">
                                <input type="submit" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                @endif

                <div class="card mt-5">
                    <div class="card-header">Reservations</div>

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
                                        <th>관리</th>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>{{ $reservation->name }}</td>
                                        <td>{{ $reservation->phone }}</td>
                                        <td>{{ $reservation->room }}</td>
                                        <td>{{ $reservation->from }}</td>
                                        <td>{{ $reservation->to }}</td>
                                        <td class="d-flex">
                                            <a href="/reservations/{{$reservation->id}}/edit" class="btn btn-link btn-sm">수정</a>
                                            <form action="/reservations/{{$reservation->id}}" method="POST" class="del-reservation">
                                                @csrf
                                                @method('DELETE')
                                                <input type="submit" value="삭제" class="btn btn-link btn-sm">
                                            </form>
                                        </td>
                                    </tr>
                                    @if($loop->last)
                                </table>
                            @endif
                        @empty
                            No content.
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        $('form.del-reservation').submit(function(){
            let res = confirm('삭제하시겠습니까?');
            if(!res)
            {
                return false;
            }
        });
    });
</script>
@endsection
