<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>
<body>
<div class="container max-w-3xl mx-auto">
    @include('include/header')
    <section>
        <h1 class="mt-8 text-2xl font-bold text-center mb-4">Booking Tickets</h1>
        <form action="{{route('doBooking')}}" method="post">
            @csrf
            <input name="route_id" type="hidden" value="{{$route->id}}">
            <input name="user_id" type="hidden" value="{{Auth::user()->id}}">
            <input class="state" name="status" type="hidden" value="PENDING">
            <div class="form-control align-center flex items-center mb-3">
                <label for="from" class="basis-32 text-right">From</label>
                <input disabled value="{{$route->city1->name}}" type="text" name="from" id="from" class="border rounded p-2 ml-4 flex-1">
            </div>
            <div class="form-control align-center flex items-center mb-3">
                <label for="to" class="basis-32 text-right">To</label>
                <input disabled value="{{$route->city2->name}}" type="text" name="to" id="to" class="border rounded p-2 ml-4 flex-1">
            </div>
            <div class="form-control align-center flex items-center mb-3">
                <label for="departure_date" class="basis-32 text-right">Departure Date</label>
                <input disabled value="{{session('date')}}" type="date" class="border rounded p-2 ml-4 flex-1">
                <input hidden value="{{session('date')}}" type="date" name="departure_date" id="departure_date" class="border rounded p-2 ml-4 flex-1">
                <input hidden name="booking_date" class="booking_date" value="" type="date">
            </div>
            <div class="form-control align-center flex items-center">
                <label for="seats" class="basis-32 text-right">Seats</label>
                <input type="number" min="1" max="{{$route->empty_seat(session('date'))}}" name="no_of_seats" id="seats" value="1" class="seats border rounded p-2 ml-4 flex-1">
            </div>
            <div class="form-control justify-center flex items-center mt-8 gap-8">
                <input type="button" value="Book"
                       class="bookButton border rounded py-2 px-8 hover:bg-blue-500 hover:text-gray-100 cursor-pointer">
                <input type="button" value="Book & Pay"
                       class="payButton border rounded py-2 px-8 hover:bg-blue-500 hover:text-gray-100 cursor-pointer">

            </div>
        </form>
    </section>
</div>
<script>
    let emp = {{$route->empty_seat(session('date'))}};
    let state = document.querySelector('.state');
    let form = document.querySelector('form');
    let seats = document.querySelector('.seats');
    seats.addEventListener('change',()=>{
        if(seats.value<1){
            seats.value=1;
        }
        if(seats.value>emp){
            seats.value=emp;
        }
    })
    document.querySelector('.bookButton').addEventListener('click',e=>{
        state.value='PENDING';
        form.submit();
    })
    document.querySelector('.payButton').addEventListener('click',e=>{
        state.value='PAID';
        form.submit();
    })
</script>
</body>
