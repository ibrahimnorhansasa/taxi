<?php

namespace App\Http\Controllers\Api;

use App\Events\ChangeStatusDriverEvent;
use App\Events\TripDeleteEvent;
use App\Events\TripOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverLocationResource;
use App\Http\Resources\TripResource;
use App\Http\Resources\TripSelectedResource;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\Trip;
use App\Models\TripSelect;
use CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{

    public function AddTrip(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $valildator = Validator::make($request->all(), [
                'user_id' => 'required',
                'fromlate' => 'required ',
                'fromlong' => 'required ',
                'tolate' => 'required ',
                'tolong' => 'required ',
                'price' => 'required ',
            ]);
            if ($valildator->fails()) {
                return CommonHelper::responseError($valildator->errors()->first());
            }
            $trip = Trip::create([
                'user_id' => $request->user_id,
                'fromlate' => $request->fromlate,
                'fromlong' => $request->fromlong,
                'tolate' => $request->tolate,
                'tolong' => $request->tolong,
                'price' => $request->price,
            ]);

            broadcast(new TripOrderEvent($trip))->toOthers();
            return CommonHelper::responseSuccess(true);
        } else {
            return CommonHelper::responseError(" Non Authorization ");
        }
    }

    public function DeleteTrip(Request $request)
    {
        $trip = Trip::find($request->trip_id);
        if ($trip) {
            $trip->delete();
            broadcast(new TripDeleteEvent($trip->id))->toOthers();
            return CommonHelper::responseSuccess(true);
        } else {
            return CommonHelper::responseError(false);
        }
    }
    public function AcceptedTrip(Request $request)
    {
        $valildator = Validator::make($request->all(), [
            'driver_id' => 'required',
            'trip_id' => 'required',
        ]);
        if ($valildator->fails()) {
            return CommonHelper::responseError($valildator->errors()->first());
        }
        $trip = Trip::find($request->trip_id);
        $driver = Driver::find($request->driver_id);

        if ($trip && $driver) {
            TripSelect::create([
                'driver_id' => $driver->id,
                'trip_id' => $trip->id,
            ]);
            $trip->status='selected';
            $trip->save();
            broadcast(new TripOrderEvent($trip))->toOthers();

            $driver_location=DriverLocation::where('driver_id',$driver->id)->first();
            $driver_location->status = 'busy';
            $driver_location->save();
            $driverlocation_resource=DriverLocationResource::make($driver_location);
            broadcast(new ChangeStatusDriverEvent($driverlocation_resource))->toOthers();

            return CommonHelper::responseSuccess(true);
        } else {
            return CommonHelper::responseError(false);
        }
    }
    public function EndedTrip(Request $request)
    {
        $user=auth()->user();
        if($user->role =='driver'){
// to change state the driverlocation
            $driver=Driver::where('user_id',$user->id)->first();
            $driver_changestate=DriverLocation::where('driver_id',$driver->id)->first();
            $driver_changestate->status = 'available';            
            $driver_changestate->save();
            $driverlocation_resource=DriverLocationResource::make($driver_changestate);
            broadcast(new ChangeStatusDriverEvent($driverlocation_resource))->toOthers();
// to change state the trip 
        $trip = Trip::find($request->trip_id);
            if($trip){
                $trip->status='endtrip';
                $trip->save();
                return CommonHelper::responseSuccess(true);
            }else{
            return CommonHelper::responseError(false);
        } 
    }
    return CommonHelper::responseError(false);
    }

    public function GetAllNearTrip(Request $request)
    {
        $trip_data= CommonHelper::CalculateDistance($request->lat,$request->log);
        $tripresource = TripResource::collection($trip_data);
      return CommonHelper::responseSuccessWithData(true,$tripresource);
    }

    public function GetTrip(Request $request)
    {
        $trip=TripSelect::where('trip_id',$request->trip_id)->with('trip','driver')->get();
        if($trip){
            $trip_resource=TripSelectedResource::collection($trip);
            return CommonHelper::responseSuccessWithData(true,$trip_resource);
        }else{
            return CommonHelper::responseError(false);
        }
    }
}

