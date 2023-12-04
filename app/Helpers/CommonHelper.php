<?php
// namespace CommonHelper;

use App\Models\Trip;
use Illuminate\Support\Facades\Response;

class CommonHelper
{
    public static function responseError($message)
    {
        return Response::json(array('message' => $message), 500);
    }
    public static function responseWithData($data, )
    {
        return Response::json(array('message' => 'success', 'data' => $data), 200);
    }
    public static function responseSuccess($message)
    {
        return Response::json(array('message' => $message), 200);
    }
    public static function responseSuccessWithData($message, $data)
    {
        return Response::json($data, 200);
    }
    public static function responseSuccessAuth($message)
    {
        return Response::json(array('success' => true, 'message' => $message), 200);
    }
    public static function responseErrorAuth($message)
    {
        return Response::json(array('success' => false, 'message' => $message), 500);
    }

    public static function CalculateDistance($lat, $lon)
    {
        $allTrip = Trip::all();
        $trip_less_ten=array();
        foreach ($allTrip as $trip) {
            $distance = sqrt(pow($lat - $trip->fromlong, 2) + pow($lon - $trip->fromlong, 2)) * 100;
            if($distance <= 10000 && $trip->status =='available') {
                $trip_less_ten[]=$trip;
            }   
        }
        return $trip_less_ten;
    }

}