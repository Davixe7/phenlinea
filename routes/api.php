<?php

use App\BatchMessage;
use App\Http\Controllers\API\v2\AdminController;
use App\Http\Controllers\API\v2\BatchMessageController;
use App\Http\Controllers\API\v2\InvoiceController;
use App\Http\Controllers\API\v2\PorteriaController;
use App\Http\Controllers\Auth\API\LoginController;
use App\Invoice;
use App\Services\PlatesService;
use App\Services\PlatesServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
  Route::group(['middleware'=>['auth:api']], function(){
    Route::apiResource('admins', AdminController::class);
    Route::apiResource('porterias', PorteriaController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('batch-messages', BatchMessageController::class);
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
