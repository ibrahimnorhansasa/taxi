<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;
use CommonHelper;
use Illuminate\Support\Facades\Auth;
use Validator;

class SupportController extends Controller
{
 public function AddConnectWithUs(Request $request)
 {
    $user=auth()->user();
    $status= Support::create([
        'subject' =>$request->subject,
        'body'    =>$request->body,
        ]);
    return CommonHelper::responseSuccess(true);

}
}