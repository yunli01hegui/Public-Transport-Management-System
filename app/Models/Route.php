<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Route extends Model
{
    public $timestamps = false;
    use HasFactory;
    public function city1(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class,'from_city_id');
    }
    public function city2(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class,'to_city_id');
    }
    public function shuttle_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShuttleType::class,'shuttle_type_id');
    }
    public function distance(): \Illuminate\Support\Collection
    {
        $c1 = $this->city1;
        $c2 = $this->city2;
        return DB::table('distance')->where('city_id_1',$c1->id)->where('city_id_2',$c2->id)->get();
    }
    public function empty_seat($date){
        $bookings = Booking::where('route_id',$this->id)->where('departure_date',date('Y-m-d',strtotime($date)))->get();
        if(isset($bookings[0])){
            $count=0;
            $bookings = $bookings->toArray();
            foreach($bookings as $item){
                if ($item['status']==='PENDING'||$item['status']==='PAID'){
                    $count+=$item['no_of_seats'];
                }
            }
            return $this->shuttle_type->capacity-$count;
        }
        return $this->shuttle_type->capacity;
    }

}
