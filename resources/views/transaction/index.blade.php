@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('alert'))
        <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
            {{ session()->get('alert')['msg'] }}
        </div>
    @endif
    <!-- <h1>Wallet</h1> -->
    

    <div class="row justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">Transaction History</div>

                <div class="card-body">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Notes</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach( $transactions as $trans )
                            <tr>
                                <td>{{ $trans->id }}</td>
                                <td>{{ number_format(round($trans->amount,2) ) }}</td>
                                <td>{{ ucwords($trans->sender->name) }}</td>
                                <td>{{ ucwords($trans->user->name) }}</td>
                                <td>{{ $trans->notes }}</td>
                                <td>{{ $trans->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection