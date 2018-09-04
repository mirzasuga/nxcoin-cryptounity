@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('alert'))
        <div class="alert alert-{{session()->get('alert')['level']}}" role="alert">
            {{ session()->get('alert')['msg'] }}
        </div>
    @endif
<div class="row justify-content-center">


    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="card-header">
                Affiliate Program
            </div>

            <div class="card-body">

                YOUR STAR:
                @for($i=0; $i<$leadership; $i++)
                <span class="fa fa-star" style="font-size:1.5em; color:gold;"></span>
                @endfor
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-header">
                Members
            </div>

            <div class="card-body">

                
                <table class="table table-hover" id="#members">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>STARS</th>
                            <th>NXC BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lines as $line)
                        <tr>
                            <td>{{ $line->name }}</td>
                            <td>{{ $line->email }}</td>
                            <td>
                                <?php $star=$line->getStar(); ?>
                                @for($i=1; $i<$star; $i++)
                                    <span class="fa fa-star" style="font-size:1.5em; color:gold;"></span>
                                @endfor
                            </td>
                            <td>
                            <?php
                                $nxcuser = Cryptounity\NXC\NxcUser::findByWallet($line->wallets()->first()->address);
                            ?>
                            {{ number_format(\Cryptounity\NXC\NxcCoin::total($nxcuser->id),2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                

            </div>
        </div>
    </div>

</div>

@endsection