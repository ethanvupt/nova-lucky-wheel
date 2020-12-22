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
</div>
<canvas id='myCanvas' width='400' height='400'>
    Canvas not supported, use another browser.
</canvas>
<button onClick="calculatePrize();">Spin the Wheel</button>
@endsection

@section('script')
<script>
    let theWheel = new Winwheel({
        'canvasId': 'myCanvas',
        'numSegments': {{ $items->count() }},
        'outerRadius': 170,
        'responsive': true, // This wheel is responsive!
        'segments': [
            @foreach($items as $item) {
                'fillStyle': '#eae56f',
                'text': '{{ $item->name }}'
            },
            @endforeach
        ],
        'animation': // Note animation properties passed in constructor parameters.
        {
            'type': 'spinToStop', // Type of animation.
            'duration': 5, // How long the animation is to take in seconds.
            'spins': 8, // The number of complete 360 degree rotations the wheel is to do.

            // Remember to do something after the animation has finished specify callback function.
            'callbackFinished': 'alertPrize()',

            // During the animation need to call the drawTriangle() to re-draw the pointer each frame.
            'callbackAfter': 'drawTriangle()'
        }
    });

    // This function called after the spin animation has stopped.
    function alertPrize() {
        // Call getIndicatedSegment() function to return pointer to the segment pointed to on wheel.
        let winningSegment = theWheel.getIndicatedSegment();

        // Basic alert of the segment text which is the prize name.
        alert("You have won " + winningSegment.text + "!");
    }

    // Function with formula to work out stopAngle before spinning animation.
    // Called from Click of the Spin button.
    function calculatePrize() {
        $.ajax({
            type: "POST",
            url: "/api/lucky-wheel/{{ $spinEvent->id }}",
            success: function (data) {
                console.log(data);

                // Important thing is to set the stopAngle of the animation before stating the spin.
                theWheel.animation.stopAngle = data;
                // Stop any current animation.
                theWheel.stopAnimation(false);

                // Reset the rotation angle to less than or equal to 360 so spinning again works as expected.
                // Setting to modulus (%) 360 keeps the current position.
                theWheel.rotationAngle = theWheel.rotationAngle % 360;

                // May as well start the spin from here.
                theWheel.startAnimation();
            },
        })
    }

    // Function to draw pointer using code (like in a previous tutorial).
    drawTriangle();

    function drawTriangle() {
        // Get the canvas context the wheel uses.
        let ctx = theWheel.ctx;

        ctx.strokeStyle = 'navy'; // Set line colour.
        ctx.fillStyle = 'aqua'; // Set fill colour.
        ctx.lineWidth = 2;
        ctx.beginPath(); // Begin path.
        ctx.moveTo(170, 5); // Move to initial position.
        ctx.lineTo(230, 5); // Draw lines to make the shape.
        ctx.lineTo(200, 40);
        ctx.lineTo(171, 5);
        ctx.stroke(); // Complete the path by stroking (draw lines).
        ctx.fill(); // Then fill.
    }
</script>
@endsection
