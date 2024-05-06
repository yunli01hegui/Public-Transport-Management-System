<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Http\Resources\ShuttleTypeResource;
use App\Models\City;
use App\Models\ShuttleType;
use App\Models\User;
use Illuminate\Http\Request;

class ShuttleTypeController extends Controller
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
            $shuttles=ShuttleType::all();
            return response()->json([
                'msg'=>'Success',
                'data'=>ShuttleTypeResource::collection($shuttles)
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
