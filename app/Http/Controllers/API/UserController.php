<?php

namespace App\Http\Controllers\API;

use CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataLoginResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Otp;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function register(Request $request)
    {
        $valildator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
        if ($valildator->fails()) {
            return CommonHelper::responseError($valildator->errors()->first());
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'role' => 'user',
            ]);

            if (isset($user->id)) {
                $this->sendOTP($user);

                return CommonHelper::responseSuccessAuth('check your phone to complete register');

            } else {
                return CommonHelper::responseErrorAuth('please try again later');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info(" Register : ", [$e->getMessage()]);
            return CommonHelper::responseError('The account is used');

        }
    }
    //verfiied code
    public function verifyRegister(Request $request)
    {
        $valildator = Validator::make($request->all(), [
            'phone' => 'required',
            'code' => 'required',
        ]);
        if ($valildator->fails()) {
            return CommonHelper::responseError($valildator->errors()->first());
        }
        $codeVerfied = Otp::where('phone', $request->phone)->where('code', $request->code)
            ->where('process', 0)->first();
        if (isset($codeVerfied)) {
            $user = User::where('phone', $request->phone)->first();
            $user->phone_verified_at = now();
            $user->isAuthanticated = true;
            $user->save();
            $userResource = DataLoginResource::make($user);
            Otp::where('phone', $request->phone)->where('code', $request->code)
                ->where('process', 0)->delete();

            return CommonHelper::responseSuccessWithData('user account has been activated successfully', $userResource);

        } else {
            return CommonHelper::responseErrorAuth('the code not correct');
        }
    }
    //login
    public function login(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $this->sendOTP($user);

            return CommonHelper::responseSuccessAuth('please check your phone to active to active your account ');

        } else {
            return CommonHelper::responseErrorAuth('please check your data and try again');
        }
    }
    //update

    public function update(Request $request)
    {
        $user = Auth::user()->id;
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);
        return CommonHelper::responseSuccessWithData('user account has been activated successfully', $user);

    }

    //show user profile

    public function show()
    {
        $user = Auth::user();
        return CommonHelper::responseSuccessWithData('user account has been activated successfully', $user);
    }

    //logout 
    public function logout()
    {
        $user = auth()->user()->id;
        $user->tokens()->delete();
        return CommonHelper::responseSuccess('logout successful');
    }

    public function SendOtpAgain(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (isset($user)) {
            $codeSended = Otp::where('phone', $request->phone)
                ->where('process', 0)->first();

            if (isset($codeSended)) {
                $codeSended->delete();
            }
            $this->sendOTP($user);
            return CommonHelper::responseSuccessAuth(true);
        } else {
            return CommonHelper::responseErrorAuth(false);
        }
    }

    public function sendOTP(User $user)
    {
        $otpCode = rand(1000, 9999);
        $otp = Otp::updateOrCreate([
            'phone' => $user->phone,
            'code' => $otpCode,
            'process' => 0,
        ]);

        $this->SendOtpCode($otpCode, $user->phone);
    }

    public function SendOtpCode($verificationCode, $phone)
    {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                $phone,
                // to
                array(
                    "from" => getenv("TWILIO_PHONE"),
                    'body' => 'Your verification code is: ' . $verificationCode
                )
            );
        $message->sid;
    }
}