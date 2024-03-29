@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        
                    </h1>
                </div>

                @foreach ($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>
                    @foreach ($activity as $record)
                        {{-- basic polymorphism to prevent from having a ton of conditionals --}}
                        {{-- Two sets of foreach loops to fetch all the activities by date and then display them by type --}}
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @endforeach

                {{-- {{ $threads->links() }} --}}
            </div>
        </div>

    </div>
@endsection