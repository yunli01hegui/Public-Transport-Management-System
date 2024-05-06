<?php

namespace App\Http\Controllers;

use App\Http\Resources\RouteResource;
use App\Models\Booking;
use App\Models\City;
use App\Models\Distance;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
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
       if ($isToken){
           if ($request->get('from_city')&&$request->get('to_city')){
               $from_city_id=City::where('name',$request->get("from_city"))->first()->id;
               $to_city_id=City::where('name',$request->get("to_city"))->first()->id;
               $route=Route::where('from_city_id',$from_city_id)->where('to_city_id',$to_city_id)->get();
           }else{
               $route=Route::all();
           }
           return response()->json([
               'msg'=>'Success',
               'data'=>RouteResource::collection($route)
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
        $token=$request->get("remember_token");
        if (!$token){
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
        $isToken=User::where('remember_token',$token)->first();
        if ($isToken){
            $route=Route::where('from_city_id',$request->get('from_city_id'))->where('to_city_id',$request->get('to_city_id'))->where('departure_time',$request->get('departure_time'))->first();
            if ($route){
                return response()->json([
                    'msg'=>'The departure time of the route already exists'
                ],422);
            }
            $route=new Route();
            $distance=new Distance();
            $route->from_city_id=$request->get('from_city_id');
            $route->to_city_id=$request->get('to_city_id');
            $route->shuttle_type_id=$request->get('shuttle_type_id');
            $route->price=$request->get('price');
            $route->departure_time=$request->get('departure_time');
            $route->duration=$request->get('duration');
            $route->valid_to=null;
            $isDistance=Distance::where('city_id_1',$request->get('from_city_id'))->where('city_id_2',$request->get('to_city_id'))->first();
            if (!$isDistance){
                $distance->city_id_1=$request->get('from_city_id');
                $distance->city_id_2=$request->get('to_city_id');
                $distance->distance=$request->get('distance');
                $distance->save();
            }
            if ($route->save()){
                return response()->json(['msg'=>'Created successfully','data'=>RouteResource::make($route)],200);
            }
        }else{
            return response()->json(['msg'=>'Invalid token'],401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function cancelRoute(Request $request,string $id){
        $token=$request->get("remember_token");
        if (!$token){
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
        $isToken=User::where('remember_token',$token)->first();
        if ($isToken){
            $route=Route::find($id);
            if(!$route){
                return response()->json(['msg'=>'Not found'],404);
            }
            $route->valid_to=date('Y-m-d');
            if ($route->save()){
                $bookings=Booking::where('route_id',$route->id)->get();
                foreach ($bookings as $b){
                    if ($b->status==="PENDING"||$b->status==="PAID"){
                        $b->status="REJECTED";
                        $b->save();
                    }
                }
                return response()->json(['msg'=>'Canceled successfully'],200);
            }
        }else{
            return response()->json(['msg'=>'Invalid token'],401);
        }
    }
    public function startRoute(Request $request,string $id){
        $token=$request->get("remember_token");
        if (!$token){
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
        $isToken=User::where('remember_token',$token)->first();
        if ($isToken){
            $route=Route::find($id);
            if(!$route){
                return response()->json(['msg'=>'Not found'],404);
            }
            $route->valid_to=null;
            if ($route->save()){
                return response()->json(['msg'=>'Started successfully'],200);
            }
        }else{
            return response()->json(['msg'=>'Invalid token'],401);
        }
    }
    public function isDistance(Request $request){
        $distance=Distance::where('city_id_1',$request->get('from_city_id'))->where('city_id_2',$request->get('to_city_id'))->first();
        if ($distance){
            return response()->json(['is_distance'=>false],200);
        }else{
            return response()->json(['is_distance'=>true],200);
        }
    }
}
