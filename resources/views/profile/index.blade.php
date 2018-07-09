@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('alert'))
        <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
            {{ session()->get('alert')['msg'] }}
        </div>
    @endif
<div class="row justify-content-center">
<style>
            .star-rating {
            line-height:32px;
            font-size:1.25em;
            }

            .star-rating .fa-star{color: yellow !important;}
            </style>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="card">
        <div class="card-header">
        <div class="row">
            <div class="col-md-8">
            Profile
            </div>
            <div class="col-md-4">
            LEADERSHIP: 
            <div class="star-rating">
                @for($i=0; $i < $leadership; $i++)
                <span class="fa fa-star"></span>
                @endfor
            </div>
            </div>
        </div>
        </div>

        <div class="card-body">
            
            
            
            
            <form>
                
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    
                        <div class="form-group">
                            <label for="">Name: </label>
                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="">Email: </label>
                            <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="">Link Referral</label>
                            <input type="text" class="form-control" value="{{ route('register').'?ref='.$user->username }}" readonly>
                        </div>
                    </div>
                </div>
            
                
            </form>
            
            
            
        </div>
    </div>
</div>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="card">
        <div class="card-header">Wallet NXCC</div>

        <div class="card-body">
            
            
            
            
        <form action="{{ route('wallet.update') }}" method="POST" role="form">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="">Address</label>
                <input type="text" name="address" readonly class="form-control" value="{{ $wallet->address }}">
            </div>
            <div class="form-group">
                <label for="">Wallet Secret Key</label>
                <input type="password" name="private_key" class="form-control" placeholder="">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
            
            
            
        </div>
    </div>
</div>
@endsection