<?php

namespace App\Http\Controllers;


use App\Http\Resources\CityResource;
use App\Http\Resources\RouteResource;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
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
            $cities=City::all();
            return response()->json([
                'msg'=>'Success',
                'data'=>CityResource::collection($cities)
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
          $v=Validator::make($request->all(),
              [
                  'name'=>['required','unique:cities'],
              ]);

         if ($v->fails()){
             return response()->json(['msg'=>'City already exists'],422);
         }
         $city=new City();
         $city->name=$request->get('name');
         if ($city->save()){
             return response()->json(['msg'=>'Created successfully','data'=>CityResource::make($city)],200);
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
        //
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
