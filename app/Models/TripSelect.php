<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSelect extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable=[];
    public function trip(){

        return $this->belongsTo(Trip::class,'trip_id','id');
    }
    
    public function driver(){

        return $this->belongsTo(Driver::class,'driver_id','id');
    }

}
