<?php

use App\Http\Controllers\API\v2\ExtensionController;
use App\Http\Controllers\API\v2\ResidentController;
use App\Http\Controllers\API\v2\admin\AdminController;
use App\Http\Controllers\API\v2\admin\BatchMessageController;
use App\Http\Controllers\API\v2\admin\InvoiceController;
use App\Http\Controllers\API\v2\admin\PorteriaController;
use App\Http\Controllers\API\v2\BatchMessageController as V2BatchMessageController;
use App\Http\Controllers\API\v2\InvoiceController as V2InvoiceController;
use App\Http\Controllers\API\v2\NoveltyController;
use App\Http\Controllers\API\v2\PetitionController;
use App\Http\Controllers\API\v2\VehicleController;
use App\Http\Controllers\API\v2\VisitController;
use App\Http\Controllers\Auth\API\LoginController;
use App\Services\PlatesService;
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

Route::middleware('auth')->get('user', fn ()=>auth()->user());

Route::prefix('v2')->group(function(){
  Route::post('login', [LoginController::class, 'login']);
  Route::post('admin-login', [LoginController::class, 'adminLogin']);
  Route::group(['middleware'=>['auth:api']], function(){
    Route::apiResource('admins', AdminController::class);
    Route::apiResource('porterias', PorteriaController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('batch-messages', BatchMessageController::class);
  });
  Route::group(['middleware'=>['auth:api-admin']], function(){
    Route::get('user', fn()=>auth()->user());
    Route::apiResource('extensions', ExtensionController::class);
    Route::apiResource('residents', ResidentController::class);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('visits', VisitController::class)->only(['index']);
    Route::apiResource('batch_messages', V2BatchMessageController::class);
    Route::apiResource('novelties', NoveltyController::class);
    Route::apiResource('petitions', PetitionController::class);
    Route::apiResource('invoices', V2InvoiceController::class);
  });
});

Route::group(['middleware' => 'auth:porteria,api-admin'], function () {
  Route::get('extensions/{extension}/residents', 'API\ExtensionController@residents');
  Route::post('extensions/delivery', 'API\WhatsappController@sendDelivery');
  Route::post('extensions/{name?}/delivery', 'API\WhatsappController@sendDelivery');
  Route::post('notifyDelivery', 'API\WhatsappController@sendDelivery');
  Route::get('plates', [PlatesService::class, 'fetchPlates']);
  Route::apiResource('apartments', 'API\ApartmentController');
  Route::apiResource('extensions', 'API\ExtensionController');
  Route::apiResource('novelties', 'API\NoveltyController');
  Route::apiResource('visits', 'API\VisitController');
  Route::apiResource('checkins', 'API\CheckinController');
  Route::apiResource('visitors', 'API\VisitorController')->except(['index', 'store']);
  Route::get('extensions/{extension}/visitors', 'API\VisitorController@extensionVisitors');

  Route::apiResource('posts', 'API\PostController');
  Route::apiResource('reminders', 'API\ReminderController');
  Route::apiResource('bills', 'API\BillController');
  Route::apiResource('petitions', 'API\PetitionController');
  Route::get('push', 'PushNotificationController@index')->name('push.index');
  Route::get('extensions/{extension}/checkins', 'API\CheckinController@extensionCheckins');
});

Route::middleware('auth:api')->group(function () {
  Route::get('invoices', 'API\InvoiceController@index');
  Route::get('invoices/search', 'API\InvoiceController@search');
  Route::get('invoices/{invoice:number}', 'API\InvoiceController@show');
  Route::put('invoices/{invoice:number}/pay', 'API\InvoiceController@pay');
});

Route::get('visitors', 'API\VisitorController@index');
Route::post('visitors', 'API\VisitorController@store');
Route::post('pqrs', 'PetitionController@store');
Route::post('login', 'Auth\API\LoginController@login');
Route::post('admin-login', 'Auth\API\LoginController@adminLogin');
Route::post('porteria-login', 'Auth\API\LoginController@porteriaLogin');
Route::get('adminslist', 'Auth\Extension\LoginController@adminslist');
Route::get('extensionslist/{admin}', 'Auth\Extension\LoginController@extensionslist');
Route::get('extensions/byphone', 'API\ExtensionController@byphone');
