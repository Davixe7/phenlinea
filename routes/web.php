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

use Illuminate\Support\Facades\Auth;

Route::get('test', function () {
  $visits = App\Visit::select(['plate', 'extension_name'])->groupBy('plate')->get();
  $visits = $visits->map(fn ($v) => $v->plate . " " . $v->extension_name . " visitante")->toArray();
  $plates = App\Vehicle::with('extension')->get();
  $plates = $plates->map(fn ($v) => $v->plate . " " . $v->extension->name . " residente")->toArray();
  return array_merge($visits, $plates);
});

Route::view('home', 'admin.home')->name('home');

Route::get('logout', function () {
  auth()->logout();
  return redirect('/');
});

Route::get('pqrs/qr', 'PetitionController@qr')->name('pqrs.qr')->middleware('auth:admin');
Route::get('pqrs', 'PetitionController@index')->name('pqrs.index')->middleware('auth:admin');
Route::get('pqrs/{petition}', 'PetitionController@show')->name('pqrs.show');
Route::put('pqrs/{petition}', 'PetitionController@update')->name('pqrs.update');
Route::put('pqrs/{petition}/markAsRead', 'PetitionController@markAsRead')->name('pqrs.markasread');
Route::post('pqrs', 'PetitionController@store')->name('pqrs.store');
Route::get('/unidades/{admin}/pqrs', 'PetitionController@create')->name('pqrs.create');

Route::get('whatsapp/hook', 'WhatsappController@hook')->name('whatsapp.hook');
Route::post('whatsapp/hook', 'WhatsappController@hook')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware(['auth:admin', 'modules:whatsapp'])->group(function () {
  Route::get('comunity', 'WhatsappController@comunity')->name('whatsapp.comunity');
  Route::get('whatsapp', 'WhatsappController@index')->name('whatsapp.index');
  Route::get('whatsapp/logout', 'WhatsappController@logout')->name('whatsapp.logout');
  Route::post('whatsapp/send', 'WhatsappController@sendMessage')->name('whatsapp.send');
  Route::post('whatsapp/comunity', 'WhatsappController@sendComunity')->name('whatsapp.sendcomunity');
  Route::get('whatsapp/status', 'WhatsappController@isOnline');
  Route::get('whatsapp/getQR', 'WhatsappController@getQRurl');
  Route::get('whatsapp/login', 'WhatsappController@login')->name('whatsapp.login');
});

Route::view('/', 'public.landing')->middleware('guest');

Route::view('politica-de-privacidad', 'public.policy');
Route::resource('attachments', 'AttachmentController');

Route::view('configuracion', 'configuration');
Route::get('apk', fn () => response()->download(public_path('app-release.apk'), 'app-release.apk', ['Content-Type' => 'application/vnd.android.package-archive']));

/**
 *  Auth Routes
 */
// Get login forms
Route::get('root/login', 'Admin\Auth\LoginController@showLoginForm');

// Login routes
Route::post('admins/login', 'Auth\Admin\LoginController@login')->name('admins.login');
Route::post('residents/login', 'Auth\Extension\LoginController@login')->name('residents.login');
Route::post('root/login', 'Admin\Auth\LoginController@login')->name('root.login');
//---
Route::get('adminslist', 'Auth\Extension\LoginController@adminslist')->name('residents.adminslist');
Route::get('extensionslist/{admin}', 'Auth\Extension\LoginController@extensionslist')->name('residents.extensionslist');

// AUTH ROUTES
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('login', 'Auth\LoginController@login')->name('login');

Route::post('admins/logout', 'Auth\Admin\LoginController@logout')->name('admins.logout');
Route::post('extensions/logout', 'Auth\Extension\LoginController@logout')->name('extensions.logout');
Route::post('porterias/logout', 'Auth\Porteria\LoginController@logout')->name('porterias.logout');

Route::get('home', 'HomeController@index')->middleware(['phoneverified', 'auth:web,admin,extension'])->name('home');
Route::get('whatsapp_clients/getclient', 'Admin\WhatsappClientController@getClient')->name('whatsapp_clients.getClient');

//Admin routes
Route::name('admin.')->prefix('admin')->middleware('auth:web')->group(function () {
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

  Route::put('invoices/{invoice}', 'Admin\InvoiceController@update')->name('invoices.update');
  Route::get('invoices/upload', 'Admin\InvoiceController@upload')->name('invoices.upload');
  Route::post('invoices/import', 'Admin\InvoiceController@import')->name('invoices.import');

  Route::get('documentos', 'Admin\DocumentosController@index')->name('documentos.index');
  Route::post('documentos', 'Admin\DocumentosController@store')->name('documentos.store');

  Route::get('admins/{admin}/edit-permissions', 'Admin\AdminController@editPermissions')->name('admins.edit-permissions');
  Route::put('admins/{admin}/update-permissions', 'Admin\AdminController@updatePermissions')->name('admins.update-permissions');
});

//Main application routes
Route::middleware(['auth:admin', 'phoneverified', 'suspended'])->group(function () {
  Route::get('extensions/export', 'ExportController@exportCensus');

  Route::resource('novelties', 'NoveltyController');
  Route::resource('extensions', 'ExtensionController');
  Route::prefix('extensions/{extension}')->name('extensions.')->group(function(){
    Route::resource('residents', 'ResidentController');
  });

  Route::resource('residents', 'ResidentController');

  Route::resource('visits', 'VisitController')->only(['index'])->middleware('modules:visits');
  Route::resource('invoices', App\Http\Controllers\InvoiceController::class)->only(['index', 'show', 'update']);
  Route::prefix('extensions/{extension}')->name('extensions.')
  ->group(fn () => Route::resource('vehicles', App\Http\Controllers\VehicleController::class));
  Route::resource('vehicles', App\Http\Controllers\VehicleController::class);

  Route::get('residents/list', 'ResidentController@list')->name('residents.list');

  Route::get('resident-invoice-batches',                          [App\Http\Controllers\ResidentInvoiceBatchController::class, 'index'] )->name('resident_invoice_batches.index');
  Route::get('resident-invoice-batches/upload',                   [App\Http\Controllers\ResidentInvoiceBatchController::class, 'upload'])->name('resident_invoice_batches.upload');
  Route::post('resident-invoice-batches/import',                  [App\Http\Controllers\ResidentInvoiceBatchController::class, 'import'])->name('resident_invoice_batches.import');
  Route::get('resident-invoice-batches/{resident_invoice_batch}', [App\Http\Controllers\ResidentInvoiceBatchController::class, 'show'] )->name('resident_invoice_batches.show');
  Route::put('resident-invoice-batches/{resident_invoice_batch}', [App\Http\Controllers\ResidentInvoiceBatchController::class, 'update'] )->name('resident_invoice_batches.update');
  Route::get('resident-invoice-batches/{resident_invoice_batch}/edit', [App\Http\Controllers\ResidentInvoiceBatchController::class, 'edit'] )->name('resident_invoice_batches.edit');

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

Route::view('consultar-facturas', 'public.resident-invoices.query');
Route::post('consultar-facturas', [App\Http\Controllers\ResidentInvoiceController::class, 'apartmentInvoices'])->name('public.resident-invoices');
Route::get('detalle-factura/{resident_invoice}', [App\Http\Controllers\ResidentInvoiceController::class, 'show']);
Route::get('descargar-factura/{resident_invoice}', [App\Http\Controllers\ResidentInvoiceController::class, 'download'])->name('resident-invoices.download');