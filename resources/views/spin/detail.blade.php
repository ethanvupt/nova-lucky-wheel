@extends('layouts.app')

@section('content')
<section id="testimonials">
    <div class="container">
        <header class="section-header">
            <h3>{{ $spinEvent->name }} event</h3>
        </header>
        <div class="row justify-content-center" id="spinButton">
            <div class="form-group col-4">
                <input class="form-control form-control-lg mb-3" type="email" id="email" name="email" placeholder="Enter your email to spin" required>
            </div>
        </div>
        <div class="row justify-content-center canvas-container">
            <canvas id='myCanvas' width='900' height='900' onClick="calculatePrize();">
                Canvas not supported, use another browser.
            </canvas>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    let theWheel = new Winwheel({
        'canvasId': 'myCanvas',
        'numSegments': {{ $items->count() }},          // Specify number of segments.
        'outerRadius'       : 420,
        'innerRadius'       : 150,
        'textFontSize'      : 30,
        'textOrientation'   : 'curved',
        'lineWidth'   : 4,
        'textAlignment'     : 'center',
        'textFontFamily'    : 'monospace',
        'textFillStyle'     : 'black',
        'segments': [
            @foreach($items as $item) {
                'strokeStyle' : '#413e66',
                'fillStyle': '',
                'image': '{{ Storage::disk('public')->url($item->image) }}',
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

    // Function to draw pointer using code (like in a previous tutorial).
    drawTriangle();

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
            data: {
                'email': $('#email').val()
            },
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
            error: function (data) {
                alert(data.responseJSON.errors.email[0]);
            }
        })
    }

    function drawTriangle() {
        // Get the canvas context the wheel uses.
        let ctx = theWheel.ctx;

        ctx.strokeStyle = '#000'; // Set line colour.
        ctx.fillStyle = '#413e66'; // Set fill colour.
        ctx.lineWidth = 3;
        ctx.beginPath(); // Begin path.
        ctx.moveTo(420, 5); // Move to initial position.
        ctx.lineTo(480, 5); // Draw lines to make the shape.
        ctx.lineTo(450, 40);
        ctx.lineTo(421, 5);
        ctx.stroke(); // Complete the path by stroking (draw lines).
        ctx.fill(); // Then fill.
    }
</script>
@endsection
