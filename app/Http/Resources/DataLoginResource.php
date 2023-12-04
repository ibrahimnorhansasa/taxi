<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'isAuthanticated' => $this->isAuthanticated,
            'email' => $this->email,
            'roles' => $this->role,
            'token' => $this->CreattypeToken($this->role),

        ];
    }

    public function CreattypeToken($role)
    {
        if ($role == 'driver') {
            return $this->createToken('driver')->plainTextToken;
        } else if($role == 'admin') {
            return $this->createToken('admin')->plainTextToken;
        }else{
            return $this->createToken('user')->plainTextToken;
        }
    }
}