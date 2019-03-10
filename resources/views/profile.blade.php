@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('save_profile') }}">
            @csrf
            <table id="profile" border="1" cellpadding="5">
                <tr><th>Email</th><td><input size="50" name="email" type="text" value="{{ $user->email }}"></td></tr>
                <tr><th>Info</th><td><input size="50" name="info" type="text" value="{{ $user->info }}"></td></tr>
                <tr><th>Phone Number</th><td><input size="50" name="phone_number" type="text" value="{{ $user->phone_number }}"></td></tr>
                <tr>
                    <td colspan="2">
                        <button class="btn btn-primary" id="save_button" type="submit">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
@endsection
