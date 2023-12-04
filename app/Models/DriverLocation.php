<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'driver_id', 'late','long','is_online',
        
    ];


    public function driver(){

        return $this->belongsTo(Driver::class,'driver_id','id');

    }

}
