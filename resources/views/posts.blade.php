@extends('layouts.app')

@section('content')
<script type="text/javascript">
    $(window).bind( 'hashchange', function() { highlightCard() });
    $(function() { highlightCard() });

    function highlightCard() {
        $(window.location.hash + "-post-card").addClass("postHighlight");
        $(".postHighlight").animate({"background-color": $(".card-header").css("background-color")}, 5000);
    }
</script>
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
                                <button type="submit" class="btn btn-md btn-info">Post</button>
                            </div>
                        </div>
                    </form>
                </div>

                @foreach ($posts as $post)
                <div class="card post">
                    <a id="{{ $post->id }}"></a>
                    <div class="card-header">
                        <p>{{ $post->created_at }}: <b>{{ $post->user->name }}</b> [{{ isset($post->user->info) ? $post->user->info . ", " : "" }}{{ isset($post->user->service_body) ? $post->user->service_body->name : "" }}]</p>
                        <p>{!!html_entity_decode($post->message)!!}</p>
                    </div>
                    <div class="card-body">
                        @foreach ($comments as $comment)
                            @if ($post->id == $comment->parent_id)
                                <div>
                                    <p>{{ $comment->created_at }}: <b>{{ App\User::findOrFail($comment->user_id)->name }}</b> [{{ isset($comment->user->info) ? $comment->user->info . ", " : "" }}{{ isset($comment->user->service_body) ? $comment->user->service_body->name : "" }}]</p>
                                    <p>{!!html_entity_decode($comment->message)!!}</p>
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
                                <button type="submit" class="btn btn-md btn-primary">Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        $( document ).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
