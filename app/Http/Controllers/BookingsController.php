<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\RouteResource;
use App\Models\Booking;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token=$request->get("remember_token");
        if (!$token){
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
        $isToken=User::where('remember_token',$token)->first();
        $bookings=Booking::all();
        if ($isToken){
            foreach ($bookings as $booking){
                if(time()>=(strtotime($booking->booking_date)+30*60)&&$booking->status==='PENDING'){
                    $booking->status = 'REJECTED';
                    $booking->save();
                }
                if(time()>=(strtotime($booking->departure_date." ".$booking->route->departure_time)+$booking->route->duration*60)&&$booking->status==='PAID'){
                    $booking->status = 'FINISHED';
                    $booking->save();
                }
            }
            return response()->json([
                'msg'=>'Success',
                'data'=>BookingResource::collection($bookings)
            ]);
        }else{
            return response()->json(['msg'=>'Invalid token'],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id,Request $request)
    {
        $token=$request->get("remember_token");
        if (!$token){
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
        $isToken=User::where('remember_token',$token)->first();
        if ($isToken){
            $booking=Booking::find($id);
            if (!$booking){
                return response()->json(['msg'=>'Not found'],404);
            }
           $booking->status='REJECTED';
            if ($booking->save()){
                return response()->json(['msg'=>'Canceled successfully'],200);
            }
        }else{
            return response()->json(['msg'=>'Invalid token'],401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
