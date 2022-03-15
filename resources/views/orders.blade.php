@extends('layouts.app')

@section('breadcrumb')
    <div class="page-header">
        <h3 class="page-title">Orders</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Orders</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
    <div id="app">
        <Orders/>
    </div>
@endsection
