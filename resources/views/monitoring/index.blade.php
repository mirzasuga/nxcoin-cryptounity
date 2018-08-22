@extends('layouts.app')

@section('inc_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
@endsection
<style>
#userbalance_paginate > span > a{
    color:#DB6574 !important;
}
.odd {
    background-color:#414347 !important;
}
.even{
    background-color:#2D3035 !important;
}
#userbalance_next,#userbalance_previous {
    color:#DB6574 !important;
}
</style>


@section('content')
<div class="container">

    
    <div class="row justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">User Balance</div>

                <div class="card-body">
                    <table id="userbalance" class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($balances) && count($balances) > 0)
                            @foreach($balances as $b)
                            <tr>
                                <td>{{ $b->username }}</td>
                                <td>{{ $b->email }}</td>
                                <td>{{ $b->balance }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

</div>
@endsection

@section('inc_js')

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#userbalance').DataTable();
} );
</script>

@endsection