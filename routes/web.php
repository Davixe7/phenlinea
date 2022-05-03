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

Route::get('servicios', function(){
    $stores = App\Store::all();
    return view('public.stores', ['stores'=>$stores]);
});

Route::view('politica-de-privacidad', 'public.policy');

Route::get('/', function(){
  if( auth()->check() ){ return redirect()->route('home'); }
  $logos = collect(Storage::allFiles('public/logos'));
  $logos = $logos->map(function($logo){
      return str_replace('public', 'storage', $logo);
  });
  return view('public.landing', ['logos'=>$logos]);
});

Route::middleware('auth:web')->get('archivos', function(){
    return view('archivos', 'super.files');
});

Route::get('https://porterias.phenlinea.com', function(){ return 'okay'; })->name('pwa.index');
Route::get('descargar/{factura}', 'FacturaController@download')->name('facturas.download');
Route::get('consultar-factura', 'FacturaController@byCode')->name('facturas.query');
Route::get('ver-factura/{factura}', 'FacturaController@show');

Route::resource('products', 'ProductController');

Route::get('sms/log', 'API\SmsController@history')->name('sms.log');
Route::post('admins/{admin}/sendpassword', 'AdminController@sendPasswordSms');
Route::post('extensions/{extension}/sendpassword', 'ExtensionController@sendPasswordSms');
Route::post('bulk', 'API\SmsController@bulkMessage');

/**
 *  Auth Routes
 */
  // Get login forms
Route::get('stores/login', 'Auth\Store\LoginController@showLoginForm')->name('stores.getLogin');
Route::get('residents/login', 'Auth\Extension\LoginController@showLoginForm')->name('residents.getLogin');
Route::get('admins/login', 'Auth\Admin\LoginController@showLoginForm')->name('admins.getLogin');
Route::get('porterias/login', 'Auth\Porteria\LoginController@showLoginForm')->name('porterias.getLogin');
Route::get('freelancers/login', 'Auth\Freelancer\LoginController@showLoginForm')->name('freelancers.getLogin');
// Login & Logout routes
Route::post('stores/login', 'Auth\Store\LoginController@login')->name('stores.login');
Route::post('admins/login', 'Auth\Admin\LoginController@login')->name('admins.login');
Route::post('residents/login', 'Auth\Extension\LoginController@login')->name('extensions.login');
Route::post('freelancers/login', 'Auth\Freelancer\LoginController@login')->name('freelancers.login');
Route::post('porterias/login', 'Auth\Porteria\LoginController@login')->name('porterias.login');

Route::post('stores/logout', 'Auth\Store\LoginController@logout')->name('stores.logout');
Route::post('admins/logout', 'Auth\Admin\LoginController@logout')->name('admins.logout');
Route::post('porterias/logout', 'Auth\Porteria\LoginController@logout')->name('porterias.logout');
Route::post('freelancers/logout', 'Auth\Freelancer\LoginController@logout')->name('freelancers.logout');
Route::post('extensions/logout', 'Auth\Extension\LoginController@logout')->name('extensions.logout');

Route::get('home', 'HomeController@index')->middleware('phoneverified')->name('home');

Route::middleware('auth')->get('user', 'Auth\UserController@index');

/**
 * Application Admin Routes
*/

Auth::routes(['verify'=>true]);
Route::post('phone/verify', 'PhoneVerificationController@verifyPhone')->name('verifyphone');
Route::view('email/verify', 'auth.verify')->name('confirmphone');

Route::get('root/login', 'Admin\Auth\LoginController@showLoginForm');
Route::post('root/login', 'Admin\Auth\LoginController@login')->name('root.login');

Route::resource('attachments', 'AttachmentController');
Route::get('admin/admins/{admin}/payments', 'Admin\AdminController@getPayments');
Route::get('admins/{admin}/payments', 'AdminController@payments')->name('admins.payments');
Route::get('ads', 'AdController@index')->name('ads.index');
Route::get('clasificados', 'AdController@index');

Route::name('admin.')->prefix('admin')->middleware('auth:web')->group(function(){
  // Get JSON Resources for VueJS Components
  Route::get('users/list', 'Admin\UserController@list')->name('users.list');
  Route::get('admins/list', 'Admin\AdminController@list')->name('admins.list');
  Route::get('porterias/list', 'Admin\PorteriaController@list')->name('porterias.list');
  Route::get('freelancers/list', 'Admin\FreelancerController@list')->name('freelancers.list');
  Route::get('slides/list', 'Admin\SlideController@list')->name('slides.list');

  Route::resource('users', 'Admin\UserController');
  Route::resource('admins', 'Admin\AdminController');
  Route::resource('payments', 'Admin\PaymentController');
  Route::resource('porterias', 'Admin\PorteriaController');
  Route::resource('extensions', 'ExtensionController');
  Route::resource('freelancers', 'Admin\FreelancerController');
  Route::resource('slides', 'Admin\SlideController');
  Route::resource('stores', 'Admin\StoreController');
  Route::put('stores/{store}/update-status', 'Admin\StoreController@updateStatus');
  Route::resource('classifieds', 'Admin\ClassifiedController');

  Route::put('stores/{store}/pictures', 'Admin\StoreController@deletepicture');

  Route::get('admins/{admin}/export/', 'ExportController@exportCensus');
  Route::put('stores/{store}/resetpassword/', 'Admin\StoreController@resetpassword');
  Route::get('export/admins', 'ExportController@exportAdmins')->name('admins.export');

  Route::get('invoices/upload', 'Admin\InvoiceController@upload')->name('invoices.upload');
  Route::post('invoices/import', 'Admin\InvoiceController@import')->name('invoices.import');

  Route::get('documentos', 'Admin\DocumentosController@index')->name('documentos.index');
  Route::post('documentos', 'Admin\DocumentosController@store')->name('documentos.store');

  Route::get('admins/{admin}/edit-permissions', 'Admin\AdminController@editPermissions')->name('admins.edit-permissions');
  Route::put('admins/{admin}/update-permissions', 'Admin\AdminController@updatePermissions')->name('admins.update-permissions');
});

//Main application routes
Route::middleware(['auth:admin','phoneverified','suspended'])->group(function(){
  Route::get('admins/{admin}/extensions/export', 'ExportController@exportCensus');
  Route::get('census/list', 'CensusController@list')->name('census.list');
  Route::resource('census', 'CensusController');
  Route::get('census/create', 'CensusController@create')->name('census.create');
  Route::get('extensions/list', 'ExtensionController@list')->name('extensions.list');
  Route::get('extensions/import', 'ExtensionController@getImport')->name('extensions.getImport')->middleware('can:import,App\Extension');
  Route::post('extensions/import', 'ExtensionController@import')->name('extensions.import');
  Route::put('extensions/{extension}/resetpassword', 'ExtensionController@resetPassword');
  Route::get('facturas/upload', 'FacturaController@upload');
  Route::post('facturas/import', 'FacturaController@import')->name('facturas.import');
  Route::resource('residents', 'ResidentController');
  Route::get('residents/list', 'ResidentController@list')->name('residents.list');
  Route::get('visits', function(){
    $visits = VisitPorteria::collection( auth()->user()->visits()->orderBy('created_at','DESC')->get() );
    return view('admin.visits', ['visits' => $visits]);
  })->name('visits.index')->middleware('modules:visits');
  Route::get('invoices', 'InvoiceController@index')->name('invoices.index');
  Route::get('invoices/{invoice}', 'InvoiceController@show')->name('invoices.show');
  Route::put('invoices/{invoice}', 'InvoiceController@update')->name('invoices.update');
  
  Route::get('push', 'PushNotificationController@create')->name('push.create');
  Route::post('push', 'PushNotificationController@store')->name('push.store');
  Route::delete('push/{push_notification_log}', 'PushNotificationController@destroy')->name('push.destroy');
  Route::get('extensions/{extension}/visitors', 'VisitorController@index')->name('visitors.index');
  Route::post('visitors', 'VisitorController@store')->name('visitors.store');
  Route::delete('visitors/{visitor}', 'VisitorController@destroy')->name('visitors.delete');
});

Route::middleware(['auth:admin,porteria', 'phoneverified', 'suspended'])->group(function(){
  Route::resource('extensions', 'ExtensionController');
  Route::get('extensions/list', 'ExtensionController@list')->name('extensions.list');
  Route::get('novelties', 'NoveltyController@index')->name('novelties.index');
  Route::get('novelties/list', 'NoveltyController@list')->name('novelties.list');
  Route::put('novelties/{novelty}/markasread', 'NoveltyController@markAsRead')->name('novelties.markAsRead')->middleware('can:markAsRead,App\Novelty');
  Route::get('sms', function(){
    $log = \App\Http\Resources\NotificationResource::collection(auth()->user()->notifications()->whereType('bulk')->orderBy('created_at', 'DESC')->get());
    $extensions = auth()->user()->extensions;
    return view('admin.sms.index', ['log' => $log, 'extensions' => $extensions]);
  })->name('sms.index');
});

Route::middleware(['auth:admin,extension', 'phoneverified', 'suspended'])->group(function(){
  Route::get('posts/list', 'PostController@list')->name('posts.list');
  Route::put('posts/{post}/deletepicture', 'PostController@deletePicture')->name('posts.deletepicture');
  Route::put('posts/{post}/deleteattachment', 'PostController@deleteAttachment')->name('posts.deleteattachment');
  Route::resource('posts', 'PostController');

  Route::get('docs/list', 'DocController@list');
  Route::resource('docs', 'DocController')->parameters(['docs'=>'post'])->middleware('modules:documents');

  Route::get('petitions/list', 'PetitionController@list')->name('petitions.list');
  Route::put('petitions/{petition}/deletepicture', 'PetitionController@deletePicture');
  Route::resource('petitions', 'PetitionController');

  Route::put('reminders/{reminder}/deletepicture', 'ReminderController@deletePicture')->name('reminders.deletepicture');
  Route::get('reminders/list', 'ReminderController@list')->name('reminders.list');
  Route::resource('reminders', 'ReminderController');

  Route::put('bills/{bill}/deletepicture', 'BillController@deletePicture')->name('bills.deletepicture');
  Route::get('bills/list', 'BillController@list')->name('bills.list');
  Route::resource('bills', 'BillController');

  Route::get('facturas/{factura}', 'FacturaController@show')->name('factura.show');
  Route::get('mis-facturas', 'FacturaController@index');
});

Route::middleware('auth:freelancer')->group(function(){
  Route::get('admins',    'AdminController@index')->name('admins.list');
  Route::get('admins/{admin}', 'AdminController@show')->name('admins.show');
});

Route::get('adminslist', 'Auth\Extension\LoginController@adminslist')->name('residents.adminslist');
Route::get('extensionslist/{admin}', 'Auth\Extension\LoginController@extensionslist')->name('residents.extensionslist');

/**
 * Commerces Module
 */
Route::middleware('auth:store')->group(function(){
  Route::get('stores/get-qr', function(){
    $store = auth()->user();
    ob_end_clean();
    return response()->download($store->qr['path'], 'qr.svg', ['Content-Type'=>'image/svg+xml']);
  });
  Route::put('stores/{store}', 'StoreController@update')->middleware('can:update,store');
  Route::put('stores/{store}/pictures', 'StoreController@deletepicture');
  Route::put('stores/{store}/reset-password', 'StoreController@resetPassword');
});
Route::get('stores', 'StoreController@index');
Route::get('stores/{store}', 'StoreController@show');

// Other Routes
Route::view('account-suspended', 'suspended')->name('suspended');
Route::view('configuracion', 'configuration');
Route::view('modulo-deshabilitado', 'disabled_module')->name('modules.disabled');

Route::get('apk', function(){
  return response()->download(
    public_path('app-release.apk'),
    'app-release.apk',
    ['Content-Type'=>'application/vnd.android.package-archive']
  );
});

Route::view('push-login', 'extensions_login');



Route::get('logaut', function(){
    Auth::logout();
});