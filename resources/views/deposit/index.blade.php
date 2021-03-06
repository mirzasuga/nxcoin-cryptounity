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

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">Input Stacking</div>

                <div class="card-body">

                    <form action="{{ route('stacking.create') }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="text" name="amount" class="form-control" placeholder="">
                        </div>
                    
                        <button type="submit" class="btn btn-primary">Stack</button>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <div class="card">
                <div class="card-header">Stacking History</div>

                <div class="card-body">
                    
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>


</div>
@endsection