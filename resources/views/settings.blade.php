@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <table border="1">
                            <tr>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Public Key</th>
                                <th>Self Private Key</th>
                                <th>Beam Private Key</th>
                            </tr>
                            @foreach ($beams as $beam)
                            <tr>
                                <td>{{ $beam->name }}</td>
                                <td>{{ $beam->url }}</td>
                                <td>{{ md5('red') }}</td>
                                <td>{{ md5($beam->url) }}</td>
                                <td>{{ md5('blue') }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="card-footer"></div>

                    <div class="card-header">Cache Flush</div>
                    <div class="card-body">
                        <button class="btn btn-sm btn-danger" onclick="location.href='/flush/service_bodies'">Service Bodies</button>
                        <button class="btn btn-sm btn-danger" onclick="location.href='/flush/beamed_users'">Beamed Users</button>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
