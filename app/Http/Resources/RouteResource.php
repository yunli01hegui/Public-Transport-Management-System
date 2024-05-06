<?php

namespace App\Http\Resources;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date= $request->get('date')?$request->get('date'):date('Y-m-d');
        return [
//          ""=>$this->,
            "id"=>$this->id,
            "from_city"=>$this->city1->name,
            "to_city"=>$this->city2->name,
            "capacity"=>$this->shuttle_type->capacity,
            "seat_available"=>$this->empty_seat($date),
            "price"=>$this->price,
            "departure_time"=>$this->departure_time,
            "duration"=>$this->duration,
            "arrival_time"=>date('H:i:s',(strtotime($this->departure_time)+$this->duration*60)),
            "distance"=>$this->distance()[0]->distance,
            "valid_to"=>$this->valid_to
        ];
    }
}
