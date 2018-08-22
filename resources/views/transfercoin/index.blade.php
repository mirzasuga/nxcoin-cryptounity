@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('alert'))
        <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
            {{ session()->get('alert')['msg'] }}
        </div>
    @endif
    <!-- <h1>Wallet</h1> -->
    <form action="{{ route('tf.send') }}" method="POST" role="form">
        <legend>Form title</legend>
        {{ csrf_field() }}
        <div class="form-group">
            <label for="">Address</label>
            <input type="hidden" class="form-control" value="10" name="amount">
            <input type="text" class="form-control" name="address" id="" placeholder="Input field">
        </div>

        

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection
