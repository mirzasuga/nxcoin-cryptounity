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
        @if(!empty($wallet))
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">{{ $wallet->name }}</div>

                <div class="card-body">
                    <form action="{{ route('wallet.update') }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $wallet->address }}">
                        </div>
                        <div class="form-group">
                            <label for="">New Wallet Secret Key</label>
                            <input type="password" name="private_key" class="form-control" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

        

        @else
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        
            
            <div class="card">
                    <div class="card-header">Add Wallet</div>

                    <div class="card-body">
                        
                        
                    <form action="{{ route('wallet.create') }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <select name="code" class="form-control" required="required">
                            <option value="NXCC">NXCC WALLET</option>
                        </select>
                        
                        <div class="form-group">
                            <label for="">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="">
                        </div>

                        <div class="form-group">
                            <label for="">Your Password Nxcoin.io</label>
                            <input type="password" name="private_key" class="form-control" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                        
                        
                    </div>
                </div>
                
                
            </div>
        </div>
        @endif


</div>
@endsection
