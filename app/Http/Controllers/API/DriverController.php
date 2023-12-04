<?php

namespace App\Http\Controllers\API;

use App\Events\ChangeStatusDriverEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverLocationResource;
use App\Http\Resources\DriverResoure;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\User;
use CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function AddDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'fatherName' => 'required',
            'lastName' => 'required',
            'carNumber' => 'required',
            'carType' => 'required',
            'carColor' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        $user = User::create([
            'name' => $request->firstName,
            'phone' => $request->phone,
            'role' => 'driver',
        ]);

        try {
            Driver::create([
                'user_id' => $user->id,
                'firstname' => $request->firstName,
                'fathername' => $request->fatherName,
                'lastname' => $request->lastName,
                'car_number' => $request->carNumber,
                'car_type' => $request->carType,
                'car_color' => $request->carColor,
                'phone' => $request->phone,
            ]);
            return CommonHelper::responseSuccess(true);
        } catch (\Exception $e) {
            Log::error("AddDriver Error", [$e->getMessage()]);
            return CommonHelper::responseError(false);
        }

    }
    public function UpdateDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        $driver = Driver::find($request->id);
        if ($driver) {
            $driver->firstname = $request->firstName;
            $driver->fathername = $request->fatherName;
            $driver->lastname = $request->lastName;
            $driver->car_number = $request->carNumber;
            $driver->car_type = $request->carType;
            $driver->car_color = $request->carColor;
            $driver->email = $request->email;
            $driver->address = $request->address;
            $driver->save();
            return CommonHelper::responseSuccess(true);
        } else {
            return CommonHelper::responseError(false);
        }
    }
    public function DeleteDriver(Request $request)
    {
        $admin = auth()->user();
        if ($admin->role == 'admin') {
            $driver = Driver::find($request->driver_id)->first();
            $driver->delete();
            return CommonHelper::responseSuccess(true);
        } else {
            return CommonHelper::responseError(false);
        }

    }
    public function AddBalanceDriver(Request $request)
    {
        $admin = auth()->user();

        $validator = Validator::make($request->all(), [
            'balance' => 'required',
            'driver_id' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }

        if ($admin->role == 'admin') {
            $driver = Driver::find($request->driver_id);
            $driver->update([
                'balance' => $request->balance,
            ]);
            return CommonHelper::responseSuccess(true);
        } else {
            return CommonHelper::responseError(false);
        }

    }
    public function GetDriver(Request $request)
    {
        $user=auth()->user();
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        if($user){
           $driver = Driver::with('driverloaction')->find($request->driver_id);
           $driver_resource=DriverResoure::make($driver);
           return CommonHelper::responseSuccessWithData(true,$driver_resource);
        }else{
            return CommonHelper::responseError(false);
        }
    }
    public function ChangeStatusDriver(Request $request)
    {
        $update_driver = $request->all();
        $driver_location = DriverLocation::where('driver_id', $update_driver['driver_id'])->first();
        if ($driver_location) {
            $driver_location->update([
                'late' => $update_driver['late'],
                'long' => $update_driver['long'],
                'is_online' =>(boolean)$update_driver['is_online'],
            ]);
            $driverlocation_resource=DriverLocationResource::make($driver_location);
        } else {
           $driver_location = DriverLocation::create([
                'driver_id' => $update_driver['driver_id'],
                'late' => $update_driver['late'],
                'long' => $update_driver['long'],
                'is_online' => (boolean)$update_driver['is_online'],
            ])->get();
            $driverlocation_resource=DriverLocationResource::collection($driver_location);
        }
        broadcast(new ChangeStatusDriverEvent($driverlocation_resource));
        return CommonHelper::responseSuccess(true);
    }
}