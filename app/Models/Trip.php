<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $guarded = [];
  
    protected $fillable=['user_id','fromlate','fromlong','tolate','tolong','price'];
    
    public function client(){

        return $this->belongsTo(User::class,'user_id','id');
    }
    public function tripselected(){
        return $this->hasOne(TripSelect::class,'trip_id','id');
    }

}
