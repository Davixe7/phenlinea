<?php
use Illuminate\Http\Request;
use App\Http\Resources\VisitPorteria;
use App\Extension;
use GuzzleHttp\Client;

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
  Route::get('whatsapp/hook', 'WhatsappController@hook')->name('whatsapp.hook');
Route::post('whatsapp/hook', 'WhatsappController@hook')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware('auth:admin')->group(function(){
    Route::get('whatsapp', 'WhatsappController@index')->name('whatsapp.index');
    Route::get('whatsapp/logout', 'WhatsappController@logout')->name('whatsapp.logout');
    Route::post('whatsapp/send', 'WhatsappController@sendMessage')->name('whatsapp.send');
    Route::get('whatsapp/status', 'WhatsappController@isOnline');
    Route::get('whatsapp/getQR', 'WhatsappController@getQRurl');
});

Route::view('/', 'public.landing')->middleware('guest');

  oute::view('politica-de-privacidad', 'public.policy');
  Route::resource('attachments', 'AttachmentController');

  Route::get('consultar-factura', 'FacturaController@byCode')->name('facturas.query');
  Route::get('ver-factura/{factura}', 'FacturaController@show');
  Route::get('descargar/{factura}', 'FacturaController@download')->name('facturas.download');

  Route::get('stores', 'StoreController@index');
  Route::get('stores/{store}', 'StoreController@show');

  Route::view('configuracion', 'configuration');
  Route::get('apk', function () {return response()->download(public_path('app-release.apk'),'app-release.apk',['Content-Type' => 'application/vnd.android.package-archive']);});

  /**
   *  Auth Routes
   */
  // Get login forms
  Route::get('freelancers/login', 'Auth\Freelancer\LoginController@showLoginForm')->name('freelancers.getLogin');
  Route::get('residents/login', 'Auth\Extension\LoginController@showLoginForm')->name('residents.getLogin');
  Route::get('stores/login', 'Auth\Store\LoginController@showLoginForm')->name('stores.getLogin');
  Route::get('root/login', 'Admin\Auth\LoginController@showLoginForm');
  
  // Login routes
  Route::post('admins/login', 'Auth\Admin\LoginController@login')->name('admins.login');
  Route::post('freelancers/login', 'Auth\Freelancer\LoginController@login')->name('freelancers.login');
  Route::post('residents/login', 'Auth\Extension\LoginController@login')->name('residents.login');
  Route::post('stores/login', 'Auth\Store\LoginController@login')->name('stores.login');
  Route::post('root/login', 'Admin\Auth\LoginController@login')->name('root.login');
  //---
  Route::get('adminslist', 'Auth\Extension\LoginController@adminslist')->name('residents.adminslist');
  Route::get('extensionslist/{admin}', 'Auth\Extension\LoginController@extensionslist')->name('residents.extensionslist');

  // Logout routes
  Route::post('admins/logout', 'Auth\Admin\LoginController@logout')->name('admins.logout');
  Route::post('freelancers/logout', 'Auth\Freelancer\LoginController@logout')->name('freelancers.logout');
  Route::post('extensions/logout', 'Auth\Extension\LoginController@logout')->name('extensions.logout');
  Route::post('porterias/logout', 'Auth\Porteria\LoginController@logout')->name('porterias.logout');
  Route::post('stores/logout', 'Auth\Store\LoginController@logout')->name('stores.logout');

  Auth::routes();
  Route::get('home', 'HomeController@index')->middleware(['phoneverified', 'auth:web,admin,extension'])->name('home');

  Route::view('email/verify', 'admin.auth.verify')->middleware('auth')->name('verification.notice');
  Route::post('phone/verify', 'PhoneVerificationController@verifyPhone')->name('verifyphone');

  Route::get('home', 'HomeController@index')->middleware('phoneverified')->name('home');
  Route::get('user', 'Auth\UserController@index')->middleware('auth');
  
  //Route::view('email/verify', 'auth.verify')->name('confirmphone');
  Route::post('admins/{admin}/sendpassword', 'AdminController@sendPasswordSms');
  Route::get('admin/admins/{admin}/payments', 'Admin\AdminController@getPayments');
  Route::get('admins/{admin}/payments', 'AdminController@payments')->name('admins.payments');

  //Admin routes
  Route::name('admin.')->prefix('admin')->middleware('auth:web')->group(function () {
    Route::get('users/list', 'Admin\UserController@list')->name('users.list');
    Route::get('admins/list', 'Admin\AdminController@list')->name('admins.list');
    Route::get('freelancers/list', 'Admin\FreelancerController@list')->name('freelancers.list');

    Route::resource('users', 'Admin\UserController');
    Route::resource('admins', 'Admin\AdminController');
    Route::resource('payments', 'Admin\PaymentController');
    Route::resource('porterias', 'Admin\PorteriaController');
    Route::resource('extensions', 'ExtensionController');
    Route::resource('freelancers', 'Admin\FreelancerController');

    Route::get('admins/{admin}/export/', 'ExportController@exportCensus');
    Route::get('export/admins', 'ExportController@exportAdmins')->name('admins.export');

  Route::put('invoices/{invoice}', 'Admin\InvoiceController@update')->name('invoices.update');
  Route::get('invoices/upload', 'Admin\InvoiceController@upload')->name('invoices.upload');
  Route::post('invoices/import', 'Admin\InvoiceController@import')->name('invoices.import');

    Route::get('documentos', 'Admin\DocumentosController@index')->name('documentos.index');
    Route::post('documentos', 'Admin\DocumentosController@store')->name('documentos.store');

    Route::get('admins/{admin}/edit-permissions', 'Admin\AdminController@editPermissions')->name('admins.edit-permissions');
    Route::put('admins/{admin}/update-permissions', 'Admin\AdminController@updatePermissions')->name('admins.update-permissions');

    Route::get('quota-sms', function (Request $request) {
      $admins = App\Admin
        ::select(['id', 'name'])
        ->withCount([
          'notifications' => function ($query) use ($request) {
            $query
              ->whereType('bulk')
              ->select(DB::raw('SUM(count) as total'))
              ->whereBetween('date', [$request->dateFrom, $request->dateTo]);
          }
        ])
        ->orderBy('name')
        ->get();

      return view('super.smsquota', compact('admins'));
    })->name('smsQuota');
  });

  //Main application routes
  Route::middleware(['auth:admin', 'phoneverified', 'suspended'])->group(function () {
    Route::resource('extensions', 'ExtensionController');
    Route::resource('residents', 'ResidentController');
    Route::get('residents/list', 'ResidentController@list')->name('residents.list');

    Route::get('messages', 'API\SmsController@index')->name('messages.index');
    Route::get('sms/log', 'API\SmsController@history')->name('sms.log');
    Route::post('bulk', 'API\SmsController@bulkMessage');

    Route::get('push', 'PushNotificationController@create')->name('push.create');
    Route::post('push', 'PushNotificationController@store')->name('push.store');
    Route::delete('push/{push_notification_log}', 'PushNotificationController@destroy')->name('push.destroy');
    
    Route::get('facturas/upload', 'FacturaController@upload');
    Route::post('facturas/import', 'FacturaController@import')->name('facturas.import');

    Route::get('invoices', 'InvoiceController@index')->name('invoices.index');
    Route::get('invoices/{invoice}', 'InvoiceController@show')->name('invoices.show');
    Route::put('invoices/{invoice}', 'InvoiceController@update')->name('invoices.update');
    
    Route::resource('visits', 'VisitController')->only(['index'])->middleware('modules:visits');
    Route::post('visitors', 'VisitorController@store')->name('visitors.store');
    Route::get('extensions/{extension}/visitors', 'VisitorController@index')->name('visitors.index');
    Route::delete('visitors/{visitor}', 'VisitorController@destroy')->name('visitors.delete');

    Route::get('extensions/import', 'ExtensionController@getImport')->name('extensions.getImport')->middleware('can:import,App\Extension');
    Route::post('extensions/import', 'ExtensionController@import')->name('extensions.import');
    Route::get('admins/{admin}/extensions/export', 'ExportController@exportCensus');
  });

  //Resident & User routes
  Route::middleware(['auth:admin,extension', 'phoneverified', 'suspended'])->group(function () {
    Route::resource('posts', 'PostController');
    Route::resource('docs', 'DocController')->parameters(['docs' => 'post'])->middleware('modules:documents');
    Route::resource('petitions', 'PetitionController');
    Route::resource('reminders', 'ReminderController');
    Route::resource('bills', 'BillController');

    Route::get('facturas/{factura}', 'FacturaController@show')->name('factura.show');
    Route::get('mis-facturas', 'FacturaController@index');

    Route::post('messages/generate-instance', 'API\SmsController@generateInstance')->name('messages.createInstance');
    Route::put('posts/{post}/deletepicture', 'PostController@deletePicture')->name('posts.deletepicture');
    Route::put('posts/{post}/deleteattachment', 'PostController@deleteAttachment')->name('posts.deleteattachment');
    Route::put('petitions/{petition}/deletepicture', 'PetitionController@deletePicture');
    Route::put('reminders/{reminder}/deletepicture', 'ReminderController@deletePicture')->name('reminders.deletepicture');
    Route::put('bills/{bill}/deletepicture', 'BillController@deletePicture')->name('bills.deletepicture');

    Route::view('account-suspended', 'suspended')->name('suspended');
    Route::view('modulo-deshabilitado', 'disabled_module')->name('modules.disabled');
  });

  //Freelancer routes
  Route::middleware('auth:freelancer')->group(function () {
    Route::get('admins',    'AdminController@index')->name('admins.list');
    Route::get('admins/{admin}', 'AdminController@show')->name('admins.show');
  });

  //Stores routes
  Route::middleware('auth:web,store')->group(function () {
    Route::get('stores/get-qr', function () {
      $store = auth()->user();
      ob_end_clean();
      return response()->download($store->qr['path'], 'qr.svg', ['Content-Type' => 'image/svg+xml']);
    });
    Route::put('stores/{store}', 'StoreController@update')->middleware('can:update,store');
    Route::put('stores/{store}/pictures', 'StoreController@deletepicture');
    Route::put('stores/{store}/reset-password', 'StoreController@resetPassword');
    Route::resource('products', 'ProductController');
    Route::delete('/products/{product}/deletePicture', 'ProductController@deletePicture');
  });