<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = ['_token'];
    public $timestamps = false;
    public function route(){
        return $this->belongsTo(Route::class,'route_id');
    }
    public function no_seats(){
        return BookingSeat::where('booking_id',$this->id)->get();
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
