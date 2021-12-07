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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/payrolls/edicion-masiva/{fecha1}/{fecha2}/{obra}', 'PayrollsController@edicionMasiva')->name('payrolls.edicion.masiva');
Route::post('/payrolls/edicion-masiva-guarda-una-nomina', 'PayrollsController@edicionMasicaGuardaUnaNomina')->name('payrolls.edicion.masiva.guarda.una.nomina');




Route::get('/', 'HomeController@index');

Route::resource('users', 'UsersController');
Route::get('/getSupervisors', 'UsersController@getSupervisors');

Route::get('public-works/borrar-obra/{id}', 'PublicWorksController@borrarObra');
Route::resource('public-works', 'PublicWorksController');
Route::post('/public-works/change_status', 'PublicWorksController@search_public_works');

Route::resource('employees', 'EmployeesController');
Route::get('/employees/editPieceworker/{employee_id}', 'EmployeesController@editPieceworker');
Route::get('/employees/getPayrolls/{employee_id}', 'EmployeesController@getPayrolls');
Route::get('/employees/createPayroll/{employee_id}', 'EmployeesController@createPayroll');
Route::get('/employees/createPieceworkerPayroll/{employee_id}', 'EmployeesController@createPieceworkerPayroll');
Route::get('/employees/editPieceworkerPayroll/{payroll_id}', 'EmployeesController@editPieceworkerPayroll');
Route::get('/employees/editPayroll/{payroll_id}', 'EmployeesController@editPayroll');

Route::resource('providers', 'ProvidersController');
Route::get('/providers/getOrders/{provider_id}', 'ProvidersController@getOrders');
Route::get('/providers/createOrder/{provider_id}', 'ProvidersController@createOrder');
Route::post('/providers/storeOrder', 'ProvidersController@storeOrder');
Route::get('/providers/editOrder/{order_id}', 'ProvidersController@editOrder');
Route::post('/providers/updateOrder', 'ProvidersController@updateOrder');
Route::delete('/providers/deleteOrder/{order_id}', 'ProvidersController@deleteOrder');
Route::get('/providers/getConcepts/{order_id}', 'ProvidersController@getConcepts');
Route::get('/providers/getPayments/{order_id}', 'ProvidersController@getPayments');
Route::get('/providers/createPayment/{order_id}', 'ProvidersController@createPayment');
Route::post('/providers/storePayment', 'ProvidersController@storePayment');
Route::get('/providers/editPayment/{payment_id}', 'ProvidersController@editPayment');
Route::post('/providers/updatePayment', 'ProvidersController@updatePayment');
Route::delete('/providers/deletePayment/{payment_id}', 'ProvidersController@deletePayment');
Route::post('/providers/change_status', 'ProvidersController@change_status');
Route::post('/providers/search_orders', 'ProvidersController@search_orders');

Route::resource('payrolls', 'PayrollsController');
Route::post('/payrolls/search_payrolls', 'PayrollsController@search_payrolls');
Route::post('/payrolls/actualizar_bonus', 'PayrollsController@actualizar_bonus');
Route::get('/payrolls/editPieceworkerPayroll/{payroll_id}', 'PayrollsController@editPieceworkerPayroll');
Route::post('/payrolls/export_pdf', 'PayrollsController@export_pdf');
Route::post('/payrolls/export_excel', 'PayrollsController@export_excel');

Route::post('/payrolls/clonar', 'PayrollsController@clonar')->name('payrolls.clonar');
