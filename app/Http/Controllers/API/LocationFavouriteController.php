<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationFavouriteResource;
use Illuminate\Http\Request;
use App\Models\LocationFavourite;
use CommonHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationFavouriteController extends Controller
{
    public function show()
    {
        $user = Auth::user()->id;
        $result = LocationFavourite::where('user_id', $user)->get();
        $locationfav_res = LocationFavouriteResource::collection($result);

        return CommonHelper::responseSuccessWithData(true,$locationfav_res);

    }


    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'late' => 'required',
            'long' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }

        $location_favourite = LocationFavourite::where('late', $request->late)->where('long', $request->long)->where('user_id', $user_id)->first();

        if (isset($location_favourite)) {
            return CommonHelper::responseError('You Are select it before');
        } else {
            $status = LocationFavourite::create([
                'user_id' => $user_id,
                'name'    => $request->name,
                'late'    => $request->late,
                'long'    => $request->long,
            ]);
            return CommonHelper::responseSuccess(true);
        }
    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        LocationFavourite::where('id', $request->location_id)->delete();
        return CommonHelper::responseSuccess(true);
    }

}