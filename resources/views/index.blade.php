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
    @error('search')
        <div class="bg-red-500 mt-2 mb-2 p-3 items-center text-white text-xl grid">{{$message}}</div>
    @enderror
    @include('include/header')
    <section class="search border-b-2 pb-5 mb-8">
        <div class="mt-10">
            <h1 class="text-xl font-bold mb-4">Search for a bus</h1>
            <form action="{{route('search')}}" class="flex">
                <input name="fromCity" type="text" list="cities" placeholder="From"
                       class="border-2 border-gray-300 p-1 rounded-lg">
                <input name="toCity" type="text" list="cities" placeholder="To"
                       class="ml-3 border-2 border-gray-300 p-1 rounded-lg">
                <input name="date" value="{{session('date')}}" type="date" placeholder="Departure date"
                       class="ml-3 border-2 border-gray-300 p-1 rounded-lg">
                <input name="onSearch" class="onSearch hidden" type="checkbox" checked value="false">
                <button class="searchButton ml-3 bg-blue-500 text-white p-2 rounded-lg">Search</button>
                <datalist id="cities">
                    @foreach($cities as $c)
                    <option value="{{$c->name}}"></option>
                    @endforeach
                </datalist>
            </form>
        </div>
    </section>
    <section class="bus-info">
        @foreach($routes as $route)
            <div class="router flex justify-between mt-{5em} border-b-2 py-3">
                <div class="flex flex-col">
                    <span>{{$route->city1->name}}</span>
                    <span class="text-sm text-gray-400">Departure time: {{$route->departure_time}}</span>
                </div>
                <div class="flex flex-col items-center">
                    <span
                        class="text-gray-400 text-sm">Seat available: {{$route->empty_seat(session('date'))}} / {{$route->shuttle_type->capacity}}</span>
                    <span class="">--------------------------&gt;</span>
                    <span
                        class="text-gray-400 text-sm">{{$route->distance()[0]->distance}} km  ${{$route->price}}</span>
                </div>
                <div class="flex flex-col">
                    <span>{{$route->city2->name}}</span>
                    <span
                        class="text-sm text-gray-400">Arrival time: {{date('H:i:s',(strtotime($route->departure_time)+$route->duration*60))}}</span>
                </div>
                @if($route->empty_seat(session('date'))>0)
                    <a href="{{route('booking',$route->id)}}" class="self-center border border-blue-500 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-lg cursor-pointer">Book</a>
                @else
                    <a class="self-center border text-white bg-gray-300 px-4 py-2 rounded-lg">Book</a>
                @endif
            </div>
    </section>
    @endforeach
</div>
</body>
</html>
