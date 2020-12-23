@extends('layouts.app')

@section('content')
<!--==========================
        Services Section
============================-->
<section id="services" class="section-bg">
    <div class="container">
        <header class="section-header">
            <h3>Spins</h3>
            <p>
                Spin events
            </p>
        </header>
        <div class="row">
            @foreach ($spinEvents as $spinEvent)
            <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
                <div class="box">
                    <div class="icon" style="background: #fceef3;"><i class="ion-ios-analytics-outline"
                            style="color: #ff689b;"></i></div>
                    <h4 class="title"><a href="{{ Route('spins.detail', $spinEvent) }}">{{ $spinEvent->name }}</a></h4>
                    <p class="description">
                        Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi
                        sint occaecati cupiditate non provident
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- #services -->

@endsection
