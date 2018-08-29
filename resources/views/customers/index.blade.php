@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">고객</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>이름</th>
                                <th>전화번호</th>
                            </tr>
                            @forelse($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
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
                    <div class="card-header">고객 추가</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="/customers" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="name">
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" placeholder="phone">
                            </div>

                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
