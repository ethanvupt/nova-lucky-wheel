@extends('layouts.app')

@section('body')
<div class="col-6">
    <a href="{{ Route('spins') }}">Go back</a>
    <h1>{{ $spinEvent->name }} - Lucky Wheel</h1>
    <h2>Wining Items</h2>
    <ul class="list-group">
        @foreach ($items as $item)
        <li class="list-group-item">
            {{ $item->name }}
            <span class="badge badge-primary badge-pill">{{ $item->fixed_percent }}%</span>
        </li>
        @endforeach
    </ul>
    <button class="btn btn-primary" id="spin">Spin</button>
</div>
@endsection

@section('script')
<script>
    $("#spin").click(function () {
        $.ajax({
            type: "POST",
            url: "/lucky-wheel/{{ $spinEvent->id }}",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function (data) {
                alert(data);
            },
        })
    });
</script>
@endsection
