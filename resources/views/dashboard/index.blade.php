@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('alert'))
        <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
            {{ session()->get('alert')['msg'] }}
        </div>
    @endif
    <h1>Dashboard</h1>

    
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            
            <ul class="list-group">
                <li class="list-group-item">Package: {{$package->name}}</li>
                <li class="list-group-item">Name: {{$user->name}}</li>
                <li class="list-group-item">Stacking Coin: {{ number_format(10000) }}</li>
                <li class="list-group-item">Profit: {{ number_format(100) }}</li>
            </ul>
            
        </div>
    </div>
    


</div>
@endsection
