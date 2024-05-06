<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
//            ''=>$this->,
            'id'=>$this->id,
            'from_city'=>$this->route->city1->name,
             'to_city'=>$this->route->city2->name,
            'booking_date'=>$this->booking_date,
            'departure_date'=>$this->departure_date.' '.$this->route->departure_time,
            'no_of_seats'=>$this->no_of_seats,
            'status'=>$this->status,
            'user'=>$this->user->name,
            'seat_on'=>$this->no_seats()
        ];
    }
}
