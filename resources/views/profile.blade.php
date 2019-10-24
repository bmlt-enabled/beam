@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('save_profile') }}">
            @csrf
            <table id="profile" border="1" cellpadding="5">
                <tr><th>Email</th><td><input class="form-control" size="50" name="email" type="text" value="{{ $user->email }}"></td></tr>
                <tr><th>Info</th><td><input class="form-control" size="50" name="info" type="text" value="{{ $user->info }}"></td></tr>
                <tr><th>Service Body</th>
                    <td>
                        <select class="form-control" name="service_body_id">
                            @if (!isset($user->service_body_id))
                                <option value="0">-= Select a Service Body =-</option>
                            @endif
                            @foreach ($service_bodies as $service_body)
                                <option value="{{ $service_body->id }}" {{ $service_body->id == $user->service_body_id ? "selected" : "" }}>{{ $service_body->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr><th>Phone Number</th><td><input class="form-control" size="50" name="phone_number" type="text" value="{{ $user->phone_number }}"></td></tr>
                <tr>
                    <th>Email Notifications</th>
                    <td><input class="form-control form-check" name="notifications_flag" type="checkbox" value="1" {{ $user->notifications_flag == 1 ? "checked" : "" }}></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn btn-primary" id="save_button" type="submit">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
@endsection
