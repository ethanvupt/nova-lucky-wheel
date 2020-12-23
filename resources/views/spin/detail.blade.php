@extends('layouts.app')

@section('content')
<section id="testimonials">
    <div class="container">
        <header class="section-header">
            <h3>{{ $spinEvent->name }} event</h3>
        </header>
        <div class="row justify-content-center">
            <canvas id='myCanvas' width='450' height='450'>
                Canvas not supported, use another browser.
            </canvas>
        </div>
        <div class="row justify-content-center">
            <button class="btn btn-dark" onClick="calculatePrize();">Spin the Wheel</button>
        </div>
    </div>
</section>

<section id="team" class="section-bg">
    <div class="container">
        <div class="section-header">
            <h3>Winning items for this event</h3>
        </div>

        <div class="row">
            @foreach ($items as $item)
            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="member">
                    <img src="{{ Storage::disk('public')->url($item->image) }}" onerror="this.src='https://picsum.photos/300/300?random=1';" class="img-fluid" alt="" style="height: 300px; width: 300px">
                    <div class="member-info">
                        <div class="member-info-content">
                            <h4>{{ $item->name }}</h4>
                            <span>{{ $item->fixed_percent }}%</span>
                            <div class="social">
                                <a href=""><i class="fa fa-twitter"></i></a>
                                <a href=""><i class="fa fa-facebook"></i></a>
                                <a href=""><i class="fa fa-google-plus"></i></a>
                                <a href=""><i class="fa fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    let theWheel = new Winwheel({
        'canvasId': 'myCanvas',
        'numSegments': {{ $items->count() }},
        'outerRadius': 200,
        'innerRadius': 30,
        'textMargin' : 0,
    'imageDirection' : 'S',
        'responsive': true, // This wheel is responsive!
        'segments': [
            @foreach($items as $item) {
                'fillStyle': '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6),
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

    function drawTriangle() {
        // Get the canvas context the wheel uses.
        let ctx = theWheel.ctx;

        ctx.strokeStyle = '#413e66'; // Set line colour.
        ctx.fillStyle = '#413e66'; // Set fill colour.
        ctx.lineWidth = 2;
        ctx.beginPath(); // Begin path.
        ctx.moveTo(200, 5); // Move to initial position.
        ctx.lineTo(260, 5); // Draw lines to make the shape.
        ctx.lineTo(230, 40);
        ctx.lineTo(201, 5);
        ctx.stroke(); // Complete the path by stroking (draw lines).
        ctx.fill(); // Then fill.
    }
</script>
@endsection
