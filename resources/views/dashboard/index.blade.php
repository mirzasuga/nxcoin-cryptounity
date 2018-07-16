@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('alert'))
        <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
            {{ session()->get('alert')['msg'] }}
        </div>
    @endif
    <h1>Dashboard</h1>

    <section class="no-padding-top no-padding-bottom">
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="statistic-block block">
                    <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                        <div class="icon"><i class="icon-user-1"></i></div><strong>{{auth()->user()->name}}</strong>
                    </div>
                    
                    </div>
                    <div class="progress progress-template">
                    <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="statistic-block block">
                    <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                        <div class="icon"><i class="icon-user-1"></i></div><strong>Stacking Coin</strong>
                    </div>
                    <div class="number dashtext-1">{{ number_format($user->totalStack()) }}</div>
                    </div>
                    <div class="progress progress-template">
                    <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="statistic-block block">
                    <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                            <div class="icon"><i class="icon-user-1"></i></div><strong>Bonus</strong>
                            <a href="{{ route('bonus.taking') }}" class="btn btn-primary">Take Bonus</a>
                        </div>
                        
                        <div class="number dashtext-1">{{ number_format($bonus) }} NXCC</div>
                        
                        
                
                    </div>
                    <div class="progress progress-template">
                    
                    <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                    </div>
                </div>
                
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="statistic-block block">
                    <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                            <div class="icon"><i class="icon-user-1"></i></div><strong>Profit</strong>
                            <a href="{{ route('profit.taking') }}" class="btn btn-primary">Take Profit</a>
                        </div>
                        
                        <div class="number dashtext-1">{{ number_format($totalProfit,8) }} NXCC</div>
                        
                        
                
                    </div>
                    <div class="progress progress-template">
                    
                    <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                    </div>
                </div>
                
            </div>
            
        </div>
        </div>
    </section>
    <!-- <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            
            <ul class="list-group">
                
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
        
        
    </div> -->
    


</div>
@endsection

@section('incJs')
<script>

</script>
@endsection
