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
                <li class="list-group-item">Stacking Coin: {{ number_format($user->totalStack()) }}</li>
                <li class="list-group-item">
                    <div class="row">
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                        Bonus: <strong>{{ number_format($bonus) }}</strong> NXCC   
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <a href="{{ route('bonus.taking') }}" class="btn btn-primary">Take Bonus</a>
                    </div>
                    </div>
                    
                </li>
            </ul>
            
        </div>
    </div>
    


</div>
@endsection
