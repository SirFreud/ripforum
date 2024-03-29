<reply :attributes="{{ $reply }}" inline-template v-cloak> {{-- allows you to use the Laravel data manipulations rather than recoding everything in JS --}}
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner) }}">
                        {{ $reply->owner->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}
                </h5>

                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
                   {{--  <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                        {{ csrf_field() }}
                        
                    </form> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea> 
                </div>
                    <button class="btn btn-xs btn-primary" @click="update">Update</button>
                    <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs btn-primary mr-1" @click="editing = true">Edit Reply</button>
                {{-- this one line of vue.js does the same thing as the form below --}}
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete Reply</button>
               {{--  <form method="POST" action="/replies/{{ $reply->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-xs">Delete Reply</button>
                </form> --}}
            </div>
        @endcan
    </div>
</reply>
