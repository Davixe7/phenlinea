<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\BatchMessageController;
use App\Http\Resources\Visit as VisitResource;
use App\ResidentInvoicePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('home', 'HomeController@index')->name('home');
Route::view('/', 'public.landing')->middleware('guest');

Route::get('pqrs/qr', 'PetitionController@qr')->name('pqrs.qr')->middleware('auth:admin');
Route::get('pqrs', 'PetitionController@index')->name('pqrs.index')->middleware('auth:admin');
Route::get('pqrs/{petition}', 'PetitionController@show')->name('pqrs.show');
Route::put('pqrs/{petition}', 'PetitionController@update')->name('pqrs.update');
Route::put('pqrs/{petition}/markAsRead', 'PetitionController@markAsRead')->name('pqrs.markasread');
Route::post('pqrs', 'PetitionController@store')->name('pqrs.store');
Route::get('/unidades/{admin}/pqrs', 'PetitionController@create')->name('pqrs.create');
Route::post('whatsapp/hook', 'BatchMessageController@hook')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])->name('whatsapp.hook');

// AUTH ROUTES
Route::get('login', 'Auth\LoginController@showLoginForm')->name('admins.login');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('root/login', 'Admin\Auth\LoginController@showLoginForm');
Route::post('root/login', 'Admin\Auth\LoginController@login')->name('root.login');

Route::post('logout', 'Auth\LoginController@logout');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::post('admins/logout', 'Auth\Admin\LoginController@logout')->name('admins.logout');
Route::post('extensions/logout', 'Auth\Extension\LoginController@logout')->name('extensions.logout');
Route::post('porterias/logout', 'Auth\Porteria\LoginController@logout')->name('porterias.logout');

//Admin routes
Route::name('admin.')->prefix('admin')->middleware('auth:web')->group(function () {
  Route::delete('whatsapp_messages/{batch_message}', 'Admin\BatchMessageController@destroy')->name('batch_messages.delete');
  Route::get('whatsapp_messages', 'Admin\BatchMessageController@index')->name('batch_messages.index');
  Route::get('whatsapp_messages/{batch_message}', 'Admin\BatchMessageController@show')->name('batch_messages.show');

  Route::delete('whatsapp_instances/{admin}', 'Admin\BatchMessageController@clearInstance')->name('whatsapp_instances.clear_instance');

  Route::put('whatsapp_instances/{admin}', 'Admin\BatchMessageController@updateInstance')->name('whatsapp_instances.update');
  Route::get('whatsapp_instances', 'Admin\BatchMessageController@instances')->name('whatsapp_instances.index');
  

  Route::get('whatsapp_clients', 'Admin\WhatsappClientController@index')->name('whatsapp_clients.index');
  Route::get('whatsapp_clients/{whatsapp_client}/scan', 'Admin\WhatsappClientController@scan')->name('whatsapp_clients.scan');
  Route::put('whatsapp_clients/{whatsapp_client}', 'Admin\WhatsappClientController@update')->name('whatsapp_clients.update');
  Route::get('users/list', 'Admin\UserController@list')->name('users.list');
  Route::get('admins/list', 'Admin\AdminController@list')->name('admins.list');

  Route::resource('users',       'Admin\UserController');
  Route::resource('admins',      'Admin\AdminController');
  Route::resource('payments',    'Admin\PaymentController');
  Route::get('porterias/export', 'Admin\PorteriaController@export');
  Route::resource('porterias',   'Admin\PorteriaController');
  Route::resource('extensions',  'ExtensionController');

  Route::get('admins/{admin}/export/', 'ExportController@exportCensus');
  Route::get('export/admins', 'ExportController@exportAdmins')->name('admins.export');

  Route::get('invoices/upload', 'Admin\InvoiceController@upload')->name('invoices.upload');
  Route::put('invoices/{invoice}', 'Admin\InvoiceController@update')->name('invoices.update');
  Route::get('invoices/{invoice}', 'Admin\InvoiceController@show')->name('invoices.show');
  Route::post('invoices/import', 'Admin\InvoiceController@import')->name('invoices.import');

  Route::get('documentos', 'Admin\DocumentosController@index')->name('documentos.index');
  Route::post('documentos', 'Admin\DocumentosController@store')->name('documentos.store');

  Route::get('admins/{admin}/edit-permissions', 'Admin\AdminController@editPermissions')->name('admins.edit-permissions');
  Route::put('admins/{admin}/update-permissions', 'Admin\AdminController@updatePermissions')->name('admins.update-permissions');

  Route::get('devices/{admin}/exportResidents', 'ZhyafController@exportResidents');
  Route::get('devices/{admin}/exportMedia', 'ZhyafController@exportMedia');
  Route::get('devices/{admin}/dropRooms', 'ZhyafController@dropRooms');
  Route::get('devices/{admin}/exportRooms', 'ZhyafController@exportRooms');
});

//Main application routes
Route::middleware(['auth:admin', 'phoneverified', 'suspended'])->group(function () {
  Route::get('extensions/export', 'ExportController@exportCensus');

  Route::resource('extensions', 'ExtensionController');

  Route::prefix('extensions/{extension}')->name('extensions.')->group(function(){
    Route::resource('residents', 'ResidentController');
    Route::resource('vehicles', App\Http\Controllers\VehicleController::class);
    Route::get('invoices', 'ResidentInvoiceController@index');
    Route::get('balance', 'ResidentInvoiceController@balance')->name('balance');
  });

  Route::resource('novelties', 'NoveltyController');
  Route::resource('batch-messages', BatchMessageController::class);
  Route::resource('residents', 'ResidentController');
  Route::resource('visits', 'VisitController')->only(['index']);
  Route::resource('invoices', 'InvoiceController')->only(['index', 'show', 'update']);
  Route::resource('resident-invoice-batches', 'ResidentInvoiceBatchController');
  Route::resource('vehicles', App\Http\Controllers\VehicleController::class);

  Route::post('batch-messages/authenticate', 'BatchMessageController@authenticate');
  Route::get('whatsapp/create_instance', 'WhatsappController@getInstanceId');
  Route::get('whatsapp/get_qrcode', 'WhatsappController@getQrCode');
  Route::post('whatsapp/authenticate', 'WhatsappController@authenticate');
  Route::get('whatsapp/logout', 'WhatsappController@logout');

  Route::prefix('extensions/{extension}')->name('extensions.')->group(fn () => Route::resource('vehicles', 'VehicleController'));
  Route::get('extensions/import', 'ExtensionController@getImport')->name('extensions.getImport')->middleware('can:import,App\Extension');
  Route::post('extensions/import', 'ExtensionController@import')->name('extensions.import');
});

//Resident routes
Route::middleware(['auth:admin,extension', 'phoneverified', 'suspended'])->group(function () {
  Route::resource('posts', 'PostController');
  Route::resource('docs', 'DocController')->parameters(['docs' => 'post'])->middleware('modules:documents');
  Route::resource('petitions', 'PetitionController');
  Route::resource('reminders', 'ReminderController');
  Route::resource('bills', 'BillController');
  Route::view('account-suspended', 'suspended')->name('suspended');
  Route::view('modulo-deshabilitado', 'disabled_module')->name('modules.disabled');
});

Route::view('politica-de-privacidad', 'public.policy');
Route::get('apk', fn () => response()->download(public_path('app-release.apk'), 'app-release.apk', ['Content-Type' => 'application/vnd.android.package-archive']));

Route::get('test', function () {
  $visits = App\Visit::select(['plate', 'extension_name'])->groupBy('plate')->get();
  $visits = $visits->map(fn ($v) => "$v->plate $v->extension_name visitante")->toArray();
  $plates = App\Vehicle::with('extension')->get();
  $plates = $plates->map(fn ($v) => "$v->plate $v->extension->name residente")->toArray();
  return array_merge($visits, $plates);
});

Route::view('consultar-facturas', 'public.resident-invoices.query')->name('public.resident-invoices.query');
Route::post('consultar-facturas', [App\Http\Controllers\Public\ResidentInvoiceController::class, 'balance'])->name('public.resident-invoices.balance');
Route::get('descargar-factura/{resident_invoice}', [App\Http\Controllers\ResidentInvoiceController::class, 'download'])->name('resident-invoices.download');
Route::get('/pago/{id}', function(Request $request){
  $payment = ResidentInvoicePayment::find($request->id);
  // return view('pdf.recibo', compact('payment'));
  return Pdf::loadView('pdf.recibo', compact('payment'))->download('recibo-caja.pdf');
});
