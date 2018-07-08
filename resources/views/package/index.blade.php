@extends('layouts.app')

@section('content')
<div class="container">
@if (Session::has('alert'))
    <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
        {{ session()->get('alert')['msg'] }}
    </div>
@endif
    <div class="row justify-content-center">
        
        <!-- packages card -->
        @foreach( $packages as $pack )
        <div class="col-md-4 package-card">
            <div class="card">
                <div class="card-header">{{ $pack->name }}</div>

                <div class="card-body">
                    
                    
                    <form class="text-center" action="{{ route('package.subscribe') }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <input type="hidden" name="package_id" value="{{ $pack->id }}">

                        <div class="form-group">
                            <label for=""><strong>Profit/<small>Day</small>: {{ $pack->profit_percent }} %</strong></label>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Minimum deposit: {{ number_format($pack->min_deposit) }}</strong></label>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Maximum deposit: {{ number_format($pack->max_deposit) }}</strong></label>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Termination Fee: {{ number_format($pack->termination_fee) }}%</strong></label>
                        </div>
                        @if( $pack->multiple_account )
                        <div class="form-group">
                            <label for=""><strong>Allow Multiple Account</strong></label>
                        </div>
                        @endif
                        @if( $pack->access_all_feature )
                        <div class="form-group">
                            <label for=""><strong>Access to all features</strong></label>
                        </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                    
                    
                </div>
            </div>
        </div>
        @endforeach
        <!-- end packages card -->
    </div>
</div>
@endsection
