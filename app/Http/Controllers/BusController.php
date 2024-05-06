<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\City;
use App\Models\Route;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Array_;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class BusController extends Controller
{
    public function index(Request $request)
    {//默认今天所有路线
        $routes = Route::where('valid_to', null)->where('departure_time', ">", date('H:i:s', time() + 30 * 60))->get();
        $date = date('Y-m-d', time());
        $request->session()->put('date', $date);
        $cities = City::all();
        return view('index', compact('routes', 'cities'));
    }

    public function search(Request $request)
    {
        $val = Validator::make($request->all(), [
            'date' => 'required',
            'toCity' => 'required|exists:App\Models\City,name',
            'fromCity' => 'required|exists:App\Models\City,name'
        ]);
        if ($val->fails()) {
            return back()->withErrors([
                'search' => 'Search Error'
            ])->withInput();
        }
        $city1 = City::where('name', $request->get('fromCity'))->get()[0];
        $city2 = City::where('name', $request->get('toCity'))->get()[0];
        $request->session()->put('date', $request->get('date'));//日期存入session
        $cities = City::all();
        if ($city1->name === $city2->name) {//两个数据禁止相同
            return back()->withErrors([
                'search' => 'Search Error'
            ])->withInput();
        }
        if (strtotime($request->get('date')) < strtotime(date('Y-m-d', time()))) {//过去的时间
            return back()->withErrors([
                'search' => 'Search Error'
            ])->withInput();
        } else if (strtotime($request->get('date')) === strtotime(date('Y-m-d', time()))) {//今天
            $routes = Route::where('valid_to', null)->where('from_city_id', $city1->id)->where('to_city_id', $city2->id)->where('departure_time', ">", date('H:i:s', time() + 30 * 60))->get();
            return view('index', compact("routes", 'cities'));
        } else {//今天之后
            $routes = Route::where('valid_to', null)->where('from_city_id', $city1->id)->where('to_city_id', $city2->id)->get();
            return view('index', compact("routes", 'cities'));
        }

    }

    public function booking($id)
    {
        $route = Route::find($id);
        return view('booking')->with([
            'route' => $route
        ]);
    }

    public function bookings()
    {
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            if (time() >= (strtotime($booking->booking_date) + 1 * 60) && $booking->status === 'PENDING') {
                $booking->status = 'REJECTED';
                $booking->save();
            }
            if (time() >= (strtotime($booking->departure_date . " " . $booking->route->departure_time) + $booking->route->duration * 60) && $booking->status === 'PAID') {
                $booking->status = 'FINISHED';
                $booking->save();
            }
        }
        return view('bookings')->with([
            'bookings' => Auth::user()->bookings
        ]);
    }

    public function doBooking(Request $request)
    {
        $val = Validator::make($request->all(), [
            'no_of_seats' => 'required|Integer|min:1',
            'departure_date' => 'required'
        ]);
        if ($val->fails()) {
            return redirect()->back()->withInput()->withErrors($val);
        }
        $request->merge([
            'booking_date' => date('Y-m-d H:i:s', time())
        ]);
        $newBooking = Booking::create($request->all());

        $route = Route::find($request->get('route_id'));
        $count = $route->shuttle_type->capacity;
        $all_seats = [];
        for ($a = 1; $a <= $count; $a++) {
            $all_seats[] = $a;
        }
        $same_route_bookings = Booking::where('route_id', $route->id)->where('departure_date', $request->get('departure_date'))->get();
        foreach ($same_route_bookings as $booking) {
            if ($booking->status === 'PAID' || $booking->status === 'PENDING') {
                $booking_seats = BookingSeat::where('booking_id', $booking->id)->get();
                foreach ($booking_seats as $booking_seat) {
                    array_splice($all_seats, array_search($booking_seat->seat_no, $all_seats), 1);
                }
            }
        }
        for ($a = 0; $a < $request->get('no_of_seats'); $a++) {
            BookingSeat::create([
                'booking_id' => $newBooking->id,
                'seat_no' => $all_seats[$a]
            ]);
        }
        return redirect(route('bookings'));
    }

    public function details($id)
    {
        $booking = Booking::find($id);
        return view('booking-details')->with([
            'booking' => $booking
        ]);
    }

    public function changeBooking(Request $request, $id)
    {
        $booking = Booking::find($id);
        BookingSeat::where('booking_id', $id)->delete();
        $booking->no_of_seats = $request->get('no_of_seats');
        $booking->status = $request->get('status');
        $booking->save();

        $route = Route::find($booking->route_id);
        $count = $route->shuttle_type->capacity;
        $all_seats = [];
        for ($a = 1; $a <= $count; $a++) {
            $all_seats[] = $a;
        }
        $same_route_bookings = Booking::where('route_id', $route->id)->where('departure_date', $booking->departure_date)->get();
        foreach ($same_route_bookings as $the_booking) {
            if ($booking->status === 'PAID' || $booking->status === 'PENDING') {
                $booking_seats = BookingSeat::where('booking_id', $the_booking->id)->get();
                foreach ($booking_seats as $booking_seat) {
                    array_splice($all_seats, array_search($booking_seat->seat_no, $all_seats), 1);
                }
            }
        }

        for ($a = 0; $a < $request->get('no_of_seats'); $a++) {
            BookingSeat::create([
                'booking_id' => $booking->id,
                'seat_no' => $all_seats[$a]
            ]);
        }
        return redirect()->back();
    }

    public function deleteBooking($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->delete();
        }
        return redirect(route('bookings'));

    }
}
