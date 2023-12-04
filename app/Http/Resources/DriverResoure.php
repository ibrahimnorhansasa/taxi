<?php

namespace App\Http\Resources;

use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResoure extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'firstName' => $this->firstname,
            'fatherName' => $this->fathername,
            'lastName' => $this->lastname,
            'phone' => $this->phone,
            'balance' => $this->balance,
            'carNumber' => $this->car_number,
            'carType' => $this->car_type,
            'carColor' => $this->car_color,
            'status' => $this-> deiverlaoction($this->id),
        ];
    }
    public function deiverlaoction($deiver_id)
    {
       $driver_location= DriverLocation::where('driver_id',$deiver_id)->first();
            $driver_resource= DriverLocationResource::make($driver_location);
                return $driver_resource->status;
    }
}