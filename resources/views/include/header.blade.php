<header class="mt-3 border-b-2">
    <nav class="flex justify-between">
        <div class="logo font-bold text-2xl"><a href="{{route('index')}}">BusLines</a></div>
        <ul class="flex">
            <li class="mr-6">
                @if(Route::currentRouteName()==='index'||Route::currentRouteName()==='search')
                    <span class="text-gray-700 font-medium">Home</span>
                @else
                    <a class="text-blue-500 hover:text-blue-800" href="{{route('index')}}">Home</a>
                @endif
            </li>
            <li class="mr-6">
                @if(Route::currentRouteName()==='bookings')
                    <span class="text-gray-700 font-medium">My Bookings</span>
                @else
                    <a href="{{route('bookings')}}" class="text-blue-500 hover:text-blue-800">My Bookings</a>
                @endif
            </li>
            @if(!Auth::check())
                <li>
                    <a href="{{route('login')}}" class="text-blue-500 hover:text-blue-800">Login</a>
                </li>
            @endif
        </ul>
        @if(Auth::check())
            <div class="flex gap-2">
                <span>{{Auth::user()->name}}</span>
                <span>/</span>
                <a href="{{route('logout')}}" class="text-red-500 hover:text-red-800">Logout</a>
            </div>
        @endif
    </nav>
</header>
