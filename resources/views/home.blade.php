@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Contacts</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table border="1" cellpadding="5">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Info') }}</th>
                            <th>{{ __('Service Body') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            @if (Auth::user()->isAdmin())
                                <th>{{ __('Action') }}</th>
                            @endif
                        </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->info }}</td>
                            <td>{{ isset($user->service_body_id) ? $user->service_body->name : "" }}</td>
                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td>{{ $user->phone_number }}</td>
                            <td>
                                <button style="display: none;">Message</button>
                                @if (Auth::user()->isAdmin())
                                    <button class="btn btn-sm btn-dark" onclick="location.href='{{ route('admin_profile', ['id' => $user->id]) }}'">{{ __('Edit User') }}</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
