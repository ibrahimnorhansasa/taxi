<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userName'=>$this->getUserName($this->user_id),
            'fromLate'=>$this->fromlate,
            'fromLong'=>$this->fromlong,
            'toLate'=>$this->tolate,
            'toLong'=>$this->tolong,
            'price'=>$this->price,
            'created'=> $this->created_at->format('Y-m-d h:i'),
        ];
    }

    public function getUserName($user_id){
        $username= User::find($user_id);
        return $username->name;
    }
}
