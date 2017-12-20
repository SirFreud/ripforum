@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <a href="#">
                        {{ $thread->creator->name }}
                    </a> 
                    posted:
                    {{ $thread->title }}
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @foreach ($thread->replies as $reply)
               @include ('threads.reply')
            @endforeach
        </div>
    </div>

    @if (auth()->check())
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form action="{{ route('add_replies', ['channel' => $thread->channel, 'thread' => $thread->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" rows="5" placeholder="Have something to contribute?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>

                </form>
            </div>
        </div>
    @else
        <p class="text-center">Please <a href="{{ route('login') }}">sign in </a>to particiapte in the discussion</p>
    @endif

</div>
@endsection
