<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PolicyResource;
use CommonHelper;
use Illuminate\Http\Request;
use App\Models\Policy;
use Illuminate\Support\Facades\Validator;


class PolicyController extends Controller
{

    public function AddTermsOfUseAndPrivacy(Request $request)
    {
        $user=auth()->user();
        $validator = Validator::make($request->all(), [
            'isPrivacy' => 'required',
            'title'     => 'required',
            'text'      => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        if($user->role =='admin'){
            Policy::create([
                'isPrivacy'=> (boolean)$request->isPrivacy,
                'title'    => $request->title,
                'text'     => $request->text,
            ]);
            return CommonHelper::responseSuccess(true);
        }else{
            return CommonHelper::responseError(false);
        }
    }
    public function UpdateTermsOfUseAndPrivacy(Request $request)
    {
        $user=auth()->user();
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required',
            'isPrivacy' => 'required',
            'title'     => 'required',
            'text'      => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        if($user->role=='admin'){
            Policy::where('id',$request->policy_id)->update([
                'isPrivacy' => $request->isPrivacy,
                'title'     => $request->title,
                'text'      => $request->text,
            ]);
            return CommonHelper::responseSuccess(true);
        }else{
            return CommonHelper::responseError(false);
        }
    }

    public function DeleteTermsOfUseAndPrivacy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
            Policy::where('id',$request->policy_id)->delete();
            return CommonHelper::responseSuccess(true);
    }

    public function GetTermsOfUseAndPrivacy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isPrivacy' => 'required',
        ]);
        if ($validator->fails()) {
            return CommonHelper::responseError($validator->errors()->first());
        }
        $policies= Policy::where('isPrivacy',$request->isPrivacy)->get();
           $policies_resource=PolicyResource::collection($policies);
        return CommonHelper::responseSuccessWithData(true,$policies_resource);
    }
    
}