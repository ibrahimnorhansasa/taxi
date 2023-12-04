<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripSelectedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Trip' => TripResource::make($this->whenLoaded('trip')),
            'Driver'=>DriverResoure::make($this->whenLoaded('driver')),
            'created'=> TripResource::make($this->whenLoaded('trip'))->created_at->format('Y-m-d h:i'),
            'accepted' => $this->created_at->format('Y-m-d h:i'),
            'end'=> TripResource::make($this->whenLoaded('trip'))->updated_at->format('Y-m-d h:i'),
        ];
    }
}
