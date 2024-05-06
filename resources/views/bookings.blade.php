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
    <section class="my-bookings w-{100%}">
        <h1 class="text-xl mt-8 mb-3 text-center font-bold">My Bokings</h1>
        <table class="mx-auto border">
            <thead>
            <tr class="border">
                <th class="border p-3">Booking No</th>
                <th class="border p-3">Booking Date</th>
                <th class="border p-3">From</th>
                <th class="border p-3">To</th>
                <th class="border p-3">Departure Time</th>
                <th class="border p-3">Seats</th>
                <th class="border p-3">Status</th>
                <th class="border p-3">Seat No</th>
                <th class="border p-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookings as $booking)
                <tr class="border p-2">
                    <td class="border p-3">{{$booking->id}}</td>
                    <td class="border p-3">{{$booking->booking_date}}</td>
                    <td class="border p-3">{{$booking->route->city1->name}}</td>
                    <td class="border p-3">{{$booking->route->city2->name}}</td>
                    <td class="border p-3">{{$booking->departure_date}} {{$booking->route->departure_time}}</td>
                    <td class="border p-3">{{$booking->no_of_seats}}</td>
                    <td class="border p-3">{{$booking->status}}</td>
                    <td class="border p-3">
                        @foreach($booking->no_seats() as $seat_no)
                            <div> No.{{$seat_no->seat_no}}</div>
                        @endforeach
                    </td>
                    <td class="border p-3 ">
                        <a href="{{route('details',$booking->id)}}" class="border p-2 rounded border-blue-600 hover:bg-blue-600 hover:text-gray-100 cursor-pointer">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
</div>
</body>
</html>
