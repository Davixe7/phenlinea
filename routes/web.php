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

use App\Admin;
use App\Extension;
use App\Http\Controllers\InvoiceController;
use App\Resident;
use App\ResidentInvoicePayment;
use App\Traits\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('dupes', function(){
  $ids = App\Admin::find(356)->extensions()->pluck('id');
  $res = DB::table('residents')
         ->groupBy('device_resident_id')
         ->selectRaw('COUNT(device_resident_id) as count, device_resident_id')
         ->whereIn('extension_id', $ids)
         ->get();
  return $res->filter(fn($res)=>$res->count > 1);
});

Route::get('count', function(){
  $chinos    = ["35990", "35989", "35988", "35961", "35957", "35956", "35955", "35954", "35953", "35952", "35951", "35950", "35948", "35945", "35941", "35938", "35937", "35936", "35931", "35930", "35929", "35928", "35924", "35923", "35921", "35920", "35919", "35918", "35917", "35914", "35912", "35911", "35910", "35909", "35908", "35907", "35906", "35905", "35904", "35903", "35902", "35901", "35900", "35899", "35898", "35897", "35896", "35895", "35894", "35893", "35892", "35891", "35890", "35889", "35888", "35887", "35886", "35885", "35884", "35883", "35882", "35881", "35880", "35879", "35878", "35877", "35865", "35764", "35763", "35762", "35761", "35760", "35759", "35758", "35757", "35699", "35696", "35686", "35684", "35683", "35680", "35679", "35678", "35677", "35676", "35647", "35550", "35538", "35535", "35534", "35529", "35528", "35527", "35356", "35355", "35354", "35353", "35347", "35346", "35344", "35342", "35338", "35287", "35265", "35159", "35155", "35154", "35153", "35127", "35120", "35023", "35022", "35020", "35019", "35018", "35017", "34716", "34713", "34712", "34711", "34593", "34549", "34542", "34534", "34530", "34488", "33625", "33601", "33600", "33592", "33590", "33589", "33588", "33587", "33586", "33585", "33569", "33461", "33460", "33457", "33456", "33455", "33454", "33453", "33452", "33451", "33415", "33411", "33399", "33388", "33387", "33386", "33385", "33384", "33383", "33382", "33264", "33263", "33262", "33261", "33260", "33259", "33258", "33257", "33256", "33134", "33128", "33123", "33122", "33116", "33112", "33109", "32999", "32998", "32984", "32983", "32982", "32981", "32980", "32966", "32964", "32960", "32957", "32956", "32955", "32954", "32952", "32944", "32941", "32938", "32937", "32929", "32926", "32910", "32893", "32840", "32839", "32838", "32836", "32835", "32834", "32718", "32717", "32715", "32714", "32713", "32706", "32704", "32705", "32602", "32601", "32599", "32581", "32580", "32579", "32578", "32577", "32576", "32569", "32568", "32567", "32566", "32519", "32513", "32512", "32511", "32510", "32509", "32508", "32505", "32499", "32498", "32497", "32496", "32495", "32494", "32493", "32492", "32491", "32378", "32375", "32374", "32373", "32371", "32370", "32368", "32366", "32225", "32222", "32217", "32216", "32212", "32210", "32206", "32205", "32204", "32203", "32202", "32201", "32199", "32167", "32160", "32157", "32153", "32152", "32151", "32144", "32143", "32142", "32127", "32123", "32122", "32116", "32110", "32107", "32106", "32105", "32104", "32103", "32102", "32042", "32041", "32039", "32034", "32032", "32028", "32027", "32026", "32010", "32009", "32008", "32007", "32005", "32003", "32002", "31998", "31997", "31996", "31995", "31993", "31969", "31968", "31967", "31966", "31963", "31962", "31958", "31948", "31947", "31946", "31898", "31897", "31896", "31869", "31867", "31866", "31865", "31864", "31863", "31859", "31854", "31848", "31846", "31840", "30881", "30838", "30796", "30425", "30400", "30399", "30382", "35871", "30831", "30794", "30283", "30281", "30282", "30795", "30880", "30127", "30847", "30081", "30059", "30049", "30047", "30044", "30006", "30005", "30004", "30003", "29999", "29995", "29950", "29928", "29929", "29927", "29919", "29952", "29768", "29766", "29764", "29762", "29760", "29756", "29755", "29754", "30813", "30812", "30811", "30809", "30793", "29728", "29727", "29716", "29713", "29712", "29705", "29684", "28915", "25020", "27728", "23847", "28112", "27350", "28622", "27389", "28714", "27218", "25529", "35875", "27819", "27386", "25213", "27658", "28041", "27950", "27390", "25013", "27215", "28154", "23160", "25900", "23159", "23810", "25198", "28040", "29218", "27447", "28637", "23951", "27233", "22893", "27794", "27378", "23899", "28897", "27405", "28594", "24830", "25393", "24525", "25727", "23880", "28046", "23921", "25379", "24008", "27705", "24897", "23955", "23192", "23677", "27807", "23669", "27478", "25768", "26065", "28635", "28073", "27411", "27216", "27237", "25206", "24054", "27991", "27359", "24855", "23785", "25484", "25010", "28186", "27659", "23150", "25375", "24298", "27400", "28401", "27367", "26070", "24287", "23835", "27374", "23826", "27442", "28139", "28023", "27777", "23676", "27473", "24009", "24878", "28197", "28233", "24999", "28091", "23767", "27212", "30700", "27821", "24928", "27393", "28196", "28164", "27345", "27826", "28189", "23934", "24828", "26088", "27470", "28295", "28361", "27835", "24980", "25482", "28318", "23959", "27717", "27839", "27586", "29190", "27790", "27568", "27465", "28080", "24964", "23153", "30684", "23553", "27575", "227578", "25009", "28317", "25893", "27392", "23937", "24558", "27490", "28165", "27330", "27388", "25685", "27922"];
  $admin     = App\Admin::find(356);
  $residents = App\Resident::whereHas('media')->whereIn('id', $chinos)->count();
  return $residents;
});

Route::get('query-residents', function(Request $request){
  $residents = [];
  if( $request->device_resident_id ){
    $residents = App\Resident::whereDeviceResidentId($request->device_resident_id)->get();
    //$residents = App\Admin::find(356)->residents()->where('residents.name', 'like', "%" . $request->name . "%")->get();
  }
  return view('query', compact('residents'));
})->name('query-residents');

Route::delete('residentes/{resident}', function(Resident $resident){
  $resident->delete();
  return to_route('query-residents', ['name'=>$resident->name]);
})->name('residentes.eliminar');

Route::post('update-resident-zhyaf_id', function(Request $request){
  $resident = App\Resident::find( $request->resident_id );
  $resident->update(['device_resident_id'=>$request->device_resident_id]);
  return to_route('query-residents', ['name'=>$resident->name]);
})->name('update-resident-zhyafid');

Route::get('import-faces', function(){
  $admin = Admin::find(356);
  $devices = new Devices();
  $devices->importFaces($admin);
});

Route::view('home', 'admin.home')->name('home');
Route::view('/', 'public.landing')->middleware('guest');

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

Route::middleware(['auth:admin', 'modules:whatsapp'])->group(function () {
  Route::get('whatsapp/login', 'WhatsappController@login')->name('whatsapp.login');
  Route::get('whatsapp/logout', 'WhatsappController@logout')->name('whatsapp.logout');
  Route::get('whatsapp/getQR', 'WhatsappController@getQR');
  Route::get('whatsapp/status', 'WhatsappController@isOnline');
  Route::get('comunity', 'WhatsappController@comunity')->name('whatsapp.comunity');
  Route::get('whatsapp', 'WhatsappController@index')->name('whatsapp.index');
  Route::post('whatsapp/send', 'WhatsappController@sendMessage')->name('whatsapp.send');
  Route::post('whatsapp/comunity', 'WhatsappController@sendComunity')->name('whatsapp.sendcomunity');
});
Route::get('whatsapp/hook', 'WhatsappController@hook')->name('whatsapp.hook');
Route::post('whatsapp/hook', 'WhatsappController@hook')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

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
  Route::get('whatsapp_messages', 'Admin\BatchMessageController@index')->name('batch_messages.index');
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
});

//Main application routes
Route::middleware(['auth:admin', 'phoneverified', 'suspended'])->group(function () {
  Route::get('extensions/export', 'ExportController@exportCensus');

  Route::resource('novelties', 'NoveltyController');
  Route::resource('extensions', 'ExtensionController');
  Route::prefix('extensions/{extension}')->name('extensions.')->group(function(){
    Route::resource('residents', 'ResidentController');
    Route::get('invoices', 'ResidentInvoiceController@index');
  });

  Route::resource('residents', 'ResidentController');

  Route::resource('visits', 'VisitController')->only(['index'])->middleware('modules:visits');

  Route::resource('invoices', InvoiceController::class)->only(['index', 'show', 'update']);

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

Route::get('batches', 'BatchMessageController@index');
Route::get('/extensions/{extension}/cuenta', 'ResidentInvoiceController@balance');
Route::view('politica-de-privacidad', 'public.policy');
Route::get('apk', fn () => response()->download(public_path('app-release.apk'), 'app-release.apk', ['Content-Type' => 'application/vnd.android.package-archive']));

Route::get('devices/exportResidents', 'ZhyafController@exportResidents');
Route::get('devices/exportRooms', 'ZhyafController@exportRooms');
Route::get('devices/{admin}/exportMedia', 'ZhyafController@exportMedia');

Route::get('test', function () {
  $visits = App\Visit::select(['plate', 'extension_name'])->groupBy('plate')->get();
  $visits = $visits->map(fn ($v) => $v->plate . " " . $v->extension_name . " visitante")->toArray();
  $plates = App\Vehicle::with('extension')->get();
  $plates = $plates->map(fn ($v) => $v->plate . " " . $v->extension->name . " residente")->toArray();
  return array_merge($visits, $plates);
});

Route::view('consultar-facturas', 'public.resident-invoices.query');
Route::post('consultar-facturas', [App\Http\Controllers\ResidentInvoiceController::class, 'apartmentInvoices'])->name('public.resident-invoices');
Route::get('detalle-factura/{resident_invoice}', [App\Http\Controllers\ResidentInvoiceController::class, 'show']);
Route::get('descargar-factura/{resident_invoice}', [App\Http\Controllers\ResidentInvoiceController::class, 'download'])->name('resident-invoices.download');
Route::get('/pago/{id}', function(Request $request){
  $payment = ResidentInvoicePayment::find($request->id);
  return view('pdf.recibo', compact('payment'));
});