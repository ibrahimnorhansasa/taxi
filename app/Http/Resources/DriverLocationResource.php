<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'driver_id' => $this->driver_id,
            'late'      => $this->late,
            'long'      => $this->long,
            'status'    => $this->status,
            'is_online' => $this->Convert($this->is_online),
        ];
    }
    public function Convert($status)
    {
        if($status == 0){
            return false;
        }else{
            return true;
        }
    }
}
