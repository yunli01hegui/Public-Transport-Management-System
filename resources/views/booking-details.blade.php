<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Booking Details</title>
</head>
<body>
<div class="container max-w-3xl mx-auto">
    @include('include/header')
    <section class="booking-details">
        <h1 class="text-xl font-bold text-center mt-12 mb-8">Booking Details</h1>
        <div class="info flex gap-4 justify-start">
            <div class="flex flex-col gap-2 font-bold">
                <span class="text-right">Booking ID:</span>
                <span class="text-right">Booking Date:</span>
                <span class="text-right">From:</span>
                <span class="text-right">To:</span>
                <span class="text-right">Departure Time:</span>
                <span class="text-right">Seat available:</span>
                <span class="text-right">Seats:</span>
                <span class="text-right">Status:</span>
                <span class="text-right">Seats No:</span>
            </div>
            <div class="flex flex-col gap-2">
                <span>{{$booking->id}}</span>
                <span>{{$booking->booking_date}}</span>
                <span>{{$booking->route->city1->name}}</span>
                <span>{{$booking->route->city2->name}}</span>
                <span>{{$booking->departure_date}} {{$booking->route->departure_time}}</span>
                <span>{{$booking->route->empty_seat(session('date'))}}</span>
                <span>{{$booking->no_of_seats}}</span>
                <span>{{$booking->status}}</span>
                <span>
                    @foreach($booking->no_seats() as $seat_no)
                        <span> No.{{$seat_no->seat_no}}</span>
                    @endforeach
                </span>
            </div>
        </div>
        <hr class="mt-4">
        @if($booking->status=="PENDING")
            <form method="post" class="data mt-8 flex gap-2 items-center justify-center">
                @csrf
                <label>Change Seat Count To:
                    <input name="no_of_seats" min="1" max="{{$booking->route->empty_seat($booking->departure_date)}}"
                           class="seats border rounded p-1" type="number" value="{{$booking->no_of_seats}}">
                    <input hidden class="status" name="status" value="{{$booking->status}}" type="text">
                </label>
                <input type="button" value="Save"
                       class="saveButton cursor-pointer border p-1 rounded border-blue-600 enabled:hover:bg-blue-600 enabled:hover:text-gray-100 disabled:text-gray-300 disabled:border-gray-100"
                       disabled>
                <input type="button" value="Pay"
                       class="payButton cursor-pointer border p-1 rounded border-blue-600 enabled:hover:bg-blue-600 enabled:hover:text-gray-100 disabled:text-gray-300 disabled:border-gray-100">
                <input type="button" value="Cancel"
                       class="cancelButton cursor-pointer border p-1 rounded border-blue-600 enabled:hover:bg-blue-600 enabled:hover:text-gray-100 disabled:text-gray-300 disabled:border-gray-100">
            </form>
        @endif
    </section>
    <div
        class="hidden cover fixed top-0 left-0 grid place-content-center items-center w-[100vw] h-[100vh] bg-[rgba(0,0,0,0.15)]">
        <div class="grid place-content-center  h-[200px] p-10 bg-white">
            <div class="text-center">
                <div class="text-xl mb-5">Are you sure you want to cancel your order?</div>
                <input
                    class="border-blue-200 bg-blue-500 text-white cursor-pointer pr-3 pl-3 pt-1 pb-1 bg border noButton"
                    type="button" value="no">
                <input class="bg-red-600 cursor-pointer pr-3 pl-3 pt-1 pb-1 border border-red-200 text-white yesButton"
                       type="button" value="yes">
            </div>
        </div>
    </div>
</div>
<script>
    let $ = e => document.querySelector(e);
    let sourceSeats = {{$booking->no_of_seats}};
    let emp = {{$booking->route->empty_seat($booking->departure_date)}};
    if ('{{$booking->status}}' == "PAID") {
        $('.payButton').disabled = true;
    }
    $('.seats').addEventListener('change', () => {
        if ($('.seats').value < 0 || $('.seats').value > emp) {
            $('.seats').value = sourceSeats;
        }
        if ($('.seats').value == sourceSeats || '{{$booking->status}}' == "PAID" || '{{$booking->status}}' == "CANCELED") {
            $('.saveButton').disabled = true;
        } else {
            $('.saveButton').disabled = false;
        }

    })
    $('.saveButton').addEventListener('click', () => {
        $('form').action = '{{route('changeBooking',$booking->id)}}';
        $('form').submit();
    })
    $('.payButton').addEventListener('click', () => {
        $('form').action = '{{route('changeBooking',$booking->id)}}';
        $(".status").value = 'PAID'
        $('form').submit();
    })
    $('.yesButton').addEventListener('click', () => {
        $('form').action = '{{route('changeBooking',$booking->id)}}';
        $('.seats').value = 0;
        $('.status').value = 'CANCELED'
        $('form').submit();
    })
    $('.cancelButton').addEventListener('click', () => {
        $('.cover').classList.remove('hidden')
    })
    $('.noButton').addEventListener('click', () => {
        $('.cover').classList.add('hidden')
    })
</script>
</body>
</html>
