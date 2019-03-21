@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Settings</div>


                    <div class="card-header">Cache Flush</div>
                    <div class="card-body">
                        <button class="btn btn-sm btn-danger" onclick="location.href='/flush/service_bodies'">Service Bodies</button>
                        <button class="btn btn-sm btn-danger" onclick="location.href='/flush/beamed_users'">Beamed Users</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
