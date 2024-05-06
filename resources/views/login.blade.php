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
<div class="w-screen bg-gray-300 h-screen grid place-items-center">
    <div>
        <h1 class="text-3xl font-bold mb-8">Please Login</h1>
        @error('msg') <div class="text-red-600">{{$message}}</div> @enderror
        <form action="{{route('doLogin')}}" method="post">
            @csrf
            <input value="{{old('email')}}" name="email" type="email" placeholder="Email" class="border-2 border-gray-300 p-2 rounded-lg">
            <input name="password" type="password" placeholder="Password" class="border-2 border-gray-300 p-2 rounded-lg">
            <button class="bg-blue-500 text-white p-2 rounded-lg">Login</button>
        </form>
    </div>
</div>
</body>

</html>
