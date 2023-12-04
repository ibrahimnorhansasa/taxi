<?php
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\LocationFavouriteController;
use App\Http\Controllers\API\PolicyController;
use App\Http\Controllers\API\SupportController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('Login',  [UserController::class,'login']);
Route::post('Register',  [UserController::class,'register']);
Route::post('VerifyPhone',[UserController::class,'verifyRegister']);
Route::post('SendOtpAgain',[UserController::class,'SendOtpAgain']);

Route::post('/getOtp',       [UserController::class,'getOtpRegister']);

//
Route::get('GetTermsOfUseAndPrivacy',     [PolicyController::class,'GetTermsOfUseAndPrivacy']);

Route::group(['middleware' => ['auth:sanctum']],function () {
    
    //support
    Route::post('AddConnectWithUs',[SupportController::class,'AddConnectWithUs']);

    //Policies 
    Route::post('AddTermsOfUseAndPrivacy',[PolicyController::class,'AddTermsOfUseAndPrivacy']);
    Route::post('DeleteTermsOfUseAndPrivacy',[PolicyController::class,'DeleteTermsOfUseAndPrivacy']);
    Route::put('UpdateTermsOfUseAndPrivacy',[PolicyController::class,'UpdateTermsOfUseAndPrivacy']);

    Route::get('show',[UserController::class,'show']);
    Route::post('/updateProfile',[UserController::class,'update']);
    Route::post('/changePassword',[UserController::class,'changePassword']);
    Route::post('Logout',[UserController::class,'logout']);

    // USER LOCATION
    Route::get('GetUserLocations',[LocationFavouriteController::class,'show']);
    Route::post('AddUserLocation',[LocationFavouriteController::class,'store']);
    Route::delete('DeleteUserLocation',[LocationFavouriteController::class,'delete']);

    Route::post('Addtrip', [TripController::class,'Addtrip']);
    Route::delete('DeleteTrip',[TripController::class,'DeleteTrip']);
    Route::post('AcceptedTrip', [TripController::class,'AcceptedTrip']);
    Route::put('EndedTrip',[TripController::class,'EndedTrip']);
    Route::get('GetAllTrip',[TripController::class,'GetAllNearTrip']);
    Route::get('GetTrip',[TripController::class,'GetTrip']);

    Route::post('AddDriver',[DriverController::class,'AddDriver']);
    Route::put('UpdateDriver',[DriverController::class,'UpdateDriver']);
    Route::delete('DeleteDriver',[DriverController::class,'DeleteDriver']);
    Route::post('AddBalanceDriver',[DriverController::class,'AddBalanceDriver']);
    Route::get('GetDriver',[DriverController::class,'GetDriver']);
    Route::post('ChangeStateDriver', [DriverController::class, 'ChangeStatusDriver']);

});


