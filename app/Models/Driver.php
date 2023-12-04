<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    
    protected $guarded = [];


    public function driverloaction(){
        return $this->hasOne(DriverLocation::class,'driver_id','id');
    }
  public function tripselected()
  {
    return $this->hasMany(TripSelect::class,'driver_id','id');
  }

}
