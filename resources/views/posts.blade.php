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
                    <div class="card-header">
                        <button
                            data-toggle="tooltip"
                            data-placement="bottom"
                            alt="test 123">{{ $post->user->name }}
                        </button>
                        [{{ isset($post->user->service_body) ? $post->user->service_body->name : "" }}]: {{ $post->message }} - {{ $post->created_at }}</div>
                    <div class="card-body">
                        @foreach ($comments as $comment)
                            @if ($post->id == $comment->parent_id)
                                <div>
                                    <b>... {{ App\User::findOrFail($comment->user_id)->name }}</b>: {{ $comment->message }} - {{ $comment->created_at }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <form method="post" action="{{ route('posts-comment-save', ['parent_id'=>$post->id]) }}" class="form-group" id="commentsForm">
                            @csrf
                            <div class="form-row">
                                <textarea name="message" style="width: 100%; height: 25px;"></textarea>
                            </div>
                            <div class="form-row float-right" style="margin-top: 10px;">
                                <button type="submit" class="btn btn-sm btn-primary">Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
