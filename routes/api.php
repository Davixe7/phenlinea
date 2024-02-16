<?php

use App\Invoice;
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
Route::get('visitors', 'API\VisitorController@index');
Route::post('visitors', 'API\VisitorController@store');

Route::get('getclient', 'Admin\WhatsappClientController@getClient');

Route::middleware('auth:api-porteria')->group(function () {
  Route::post('extensions/delivery', 'API\WhatsappController@sendDelivery');
  Route::post('extensions/{name?}/delivery', 'API\WhatsappController@sendDelivery');
  Route::post('notifyDelivery', 'API\WhatsappController@sendDelivery');

  Route::get('plates', function(){
    $visitIds = auth()->user()
              ->visits()->whereNotNull('plate')
              ->selectRaw('MAX(`created_at`) as fecha')
              ->groupBy(['plate', 'admin_id'])
              ->pluck('fecha');

    $visits = auth()->user()->visits()
              ->select(['admin_id', 'plate', 'extension_name'])
              ->whereIn('created_at', $visitIds)
              ->get();

    $visits = $visits->map(fn($v) => $v->plate . " " . $v->extension_name . " visitante")->toArray();

    $plates = auth()->user()->vehicles()->with('extension')->get();
    $plates = $plates->map(fn($v) => $v->plate . " " . $v->extension->name . " residente")->toArray();
    return array_merge($visits, $plates);
  });

  Route::post('whatsapp', 'WhatsappController@logHook')->name('whatsapp.hook');
});

Route::middleware('auth:api')->group(function () {
  Route::get('invoices', 'API\InvoiceController@index');
  Route::get('invoices/search', 'API\InvoiceController@search');
  Route::get('invoices/{invoice:number}', 'API\InvoiceController@show');
  Route::put('invoices/{invoice:number}/pay', 'API\InvoiceController@pay');
});

Route::middleware('auth')->get('/user', function (Request $request) {
  return $request->user();
});

Route::group(['middleware' => 'auth:api-admin,api-porteria'], function () {
  Route::apiResource('apartments', 'API\ApartmentController');
  Route::apiResource('extensions', 'API\ExtensionController');
  Route::apiResource('novelties', 'API\NoveltyController');
  Route::apiResource('visits', 'API\VisitController');
});

Route::group(['middleware' => 'auth:api-extension,api-porteria,api-admin'], function () {
  Route::apiResource('checkins', 'API\CheckinController');
  Route::apiResource('visitors', 'API\VisitorController')->except(['index', 'store']);
  Route::get('extensions/{extension}/visitors', 'API\VisitorController@extensionVisitors');
});

Route::group(['middleware' => 'auth:api-admin,api-extension'], function () {
  Route::apiResource('posts', 'API\PostController');
  Route::apiResource('reminders', 'API\ReminderController');
  Route::apiResource('bills', 'API\BillController');
  Route::apiResource('petitions', 'API\PetitionController');
  Route::get('push', 'PushNotificationController@index')->name('push.index');
  Route::get('extensions/{extension}/checkins', 'API\CheckinController@extensionCheckins');
});

Route::post('login', 'Auth\ApiController@login');
Route::post('admin-login', 'Auth\ApiController@adminLogin');
Route::post('store-login', 'Auth\ApiController@storeLogin');
Route::post('porteria-login', 'Auth\ApiController@porteriaLogin');
Route::post('residents-login', 'Auth\ApiController@extensionLogin');
Route::get('adminslist', 'Auth\Extension\LoginController@adminslist');
Route::get('extensionslist/{admin}', 'Auth\Extension\LoginController@extensionslist');

Route::get('extensions/byphone', 'API\ExtensionController@byphone');

Route::put('extensions/{extension}/resetpassword', 'API\ExtensionController@resetPassword');
Route::post('extensions/{extension}/sendpassword', 'API\ExtensionController@sendPasswordSms');

Route::post('/pqrs', 'PetitionController@store');
