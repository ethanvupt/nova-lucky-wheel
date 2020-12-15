@extends('layouts.app')

@section('body')
<div class="col-6">
    <h1>All Events</h1>
    <ul class="list-group">
        @foreach ($spinEvents as $spinEvent)
        <li class="list-group-item">
            <a href="{{Route('spins-detail', $spinEvent)}}">{{ $spinEvent->name }}</a>
        </li>
        @endforeach
    </ul>
</div>
@endsection
