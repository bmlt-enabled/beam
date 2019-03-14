@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">New Post</div>
                    <form method="post" action="{{ route('posts-save') }}" class="form-group" id="postsForm">
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <textarea name="message" style="width: 100%; height: 100px;"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-row">
                                <button type="submit" class="btn btn-sm btn-info">Post</button>
                            </div>
                        </div>
                    </form>
                </div>

                @foreach ($posts as $post)
                <div class="card">
                    <div class="card-header"><b>{{ App\User::findOrFail($post->user_id)->name }}</b>: {{ $post->message }} - {{ $post->created_at }}</div>
                    <div class="card-body"></div>
                    <div class="card-footer"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
