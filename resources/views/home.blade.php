@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="/reservations/create" class="btn btn-primary">예약 정보 추가</a>
                </div>
            </div>

            @if($unregisteredCustomers)
            <div class="card mt-5">
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
        </div>
    </div>
</div>
@endsection
