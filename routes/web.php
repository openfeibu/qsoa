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
Route::get('test', 'TestController@test');

Route::get('/', function () {
    return redirect('/admin');
});
// Admin  routes  for user
Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin'
], function () {
    Auth::routes();
    Route::get('password', 'UserController@getPassword');
    Route::post('password', 'UserController@postPassword');
    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/home', 'ResourceController@home');
    Route::get('/dashboard', 'ResourceController@dashboard')->name('dashboard');
    Route::resource('banner', 'BannerResourceController');
    Route::post('/banner/destroyAll', 'BannerResourceController@destroyAll');

    Route::resource('news', 'NewsResourceController');
    Route::post('/news/destroyAll', 'NewsResourceController@destroyAll')->name('news.destroy_all');
    Route::post('/news/updateRecommend', 'NewsResourceController@updateRecommend')->name('news.update_recommend');
    Route::resource('system_page', 'SystemPageResourceController');
    Route::post('/system_page/destroyAll', 'SystemPageResourceController@destroyAll')->name('system_page.destroy_all');
    Route::get('/setting/company', 'SettingResourceController@company')->name('setting.company.index');
    Route::post('/setting/updateCompany', 'SettingResourceController@updateCompany');
    Route::get('/setting/publicityVideo', 'SettingResourceController@publicityVideo')->name('setting.publicity_video.index');
    Route::post('/setting/updatePublicityVideo', 'SettingResourceController@updatePublicityVideo');
    Route::get('/setting/station', 'SettingResourceController@station')->name('setting.station.index');
    Route::post('/setting/updateStation', 'SettingResourceController@updateStation');

    Route::resource('link', 'LinkResourceController');
    Route::post('/link/destroyAll', 'LinkResourceController@destroyAll')->name('link.destroy_all');
    Route::resource('permission', 'PermissionResourceController');
    Route::resource('role', 'RoleResourceController');


    Route::group(['prefix' => 'page','as' => 'page.'], function ($router) {
        Route::resource('page', 'PageResourceController');
        Route::resource('category', 'PageCategoryResourceController');
    });
    Route::group(['prefix' => 'menu'], function ($router) {
        Route::get('index', 'MenuResourceController@index');
    });

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');
    Route::post('/file/{config}/{path?}', 'UploadController@uploadFile')->where('path', '(.*)');
    #主要路由
    Route::resource('airline', 'AirlineResourceController');
    Route::post('/airline/destroyAll', 'AirlineResourceController@destroyAll')->name('airline.destroy_all');
    Route::resource('supplier', 'SupplierResourceController');
    Route::post('/supplier/destroyAll', 'SupplierResourceController@destroyAll')->name('supplier.destroy_all');
    Route::post('/supplier/top_up/{supplier}', 'SupplierResourceController@topUp');
    Route::post('/supplier/fee_deduction/{supplier}', 'SupplierResourceController@feeDeduction');

    Route::resource('airport', 'AirportResourceController');
    Route::post('/airport/destroyAll', 'AirportResourceController@destroyAll')->name('airport.destroy_all');

    Route::resource('contract', 'ContractResourceController');
    Route::post('/airline/destroyAll', 'AirlineResourceController@destroyAll')->name('airline.destroy_all');

    Route::get('bill', 'BillResourceController@index')->name('bill.index');
    Route::get('bill/airline_bill', 'BillResourceController@airlineBill')->name('bill.airline_bill');
    Route::get('bill/supplier_bill', 'BillResourceController@supplierBill')->name('bill.supplier_bill');

    Route::post('/airline/destroyAll', 'AirlineResourceController@destroyAll')->name('airline.destroy_all');
    Route::post('/contract/destroy_image', 'ContractResourceController@destroyImage');
    Route::resource('airline_contract', 'AirlineContractResourceController');
    Route::resource('supplier_contract', 'SupplierContractResourceController');
    Route::post('/media_folder/store', 'MediaResourceController@folderStore')->name('media_folder.store');
    Route::delete('/media_folder/destroy', 'MediaResourceController@folderDestroy')->name('media_folder.destroy');
    Route::put('/media_folder/update/{media_folder}', 'MediaResourceController@folderUpdate')->name('media_folder.update');
    Route::get('/media', 'MediaResourceController@index')->name('media.index');
    Route::put('/media/update/{media}', 'MediaResourceController@update')->name('media.update');
    Route::post('/media/upload', 'MediaResourceController@upload')->name('media.upload');
    Route::delete('/media/destroy', 'MediaResourceController@destroy')->name('media.destroy');
    Route::get('/world_city/list', 'WorldCityResourceController@getList')->name('world_city.list');
    Route::resource('supplier_bill_template_field', 'SupplierBillTemplateFieldResourceController');
    Route::post('/supplier_bill_template_field/destroyAll', 'SupplierBillTemplateFieldResourceController@destroyAll')->name('supplier_bill_template_field.destroy_all');

    Route::resource('supplier_user', 'SupplierUserResourceController');
    Route::post('/supplier_user/destroyAll', 'SupplierUserResourceController@destroyAll')->name('supplier_user.destroy_all');
    Route::resource('airline_user', 'AirlineUserResourceController');
    Route::post('/airline_user/destroyAll', 'AirlineUserResourceController@destroyAll')->name('airline_user.destroy_all');
    // 航空公司账单
    Route::resource('airline_bill', 'AirlineBillResourceController');
    Route::post('/airline_bill/invalid', 'AirlineBillResourceController@invalid')->name('airline_bill.invalid');
    Route::get('/airline_bill/download_word/{airline_bill}', 'AirlineBillResourceController@downloadWord')->name('airline_bill.download_word');
    Route::get('/airline_bill/download_excel/{airline_bill}', 'AirlineBillResourceController@downloadExcel')->name('airline_bill.download_excel');

    Route::get('new_airline_bill', 'AirlineBillResourceController@newAirlineBills')->name('airline_bill.new_airline_bill');
    Route::get('finished_airline_bill', 'AirlineBillResourceController@finishedAirlineBills')->name('airline_bill.finished_airline_bill');
    Route::get('invalid_airline_bill', 'AirlineBillResourceController@invalidAirlineBills')->name('airline_bill.invalid_airline_bill');

    Route::resource('finance_user', 'FinanceUserResourceController');
    Route::post('/finance_user/destroyAll', 'FinanceUserResourceController@destroyAll')->name('finance_user.destroy_all');

    //供应商账单
    Route::get('supplier_bill', 'SupplierBillResourceController@index')->name('supplier_bill.index');
    Route::get('supplier_bill/{supplier_bill}', 'SupplierBillResourceController@show')->name('supplier_bill.show');
    Route::post('/supplier_bill/pass', 'SupplierBillResourceController@pass')->name('supplier_bill.pass');
    Route::post('/supplier_bill/reject', 'SupplierBillResourceController@reject')->name('supplier_bill.reject');
    Route::post('/supplier_bill/invalid', 'SupplierBillResourceController@invalid')->name('supplier_bill.invalid');
    Route::get('new_supplier_bill', 'SupplierBillResourceController@newSupplierBills')->name('supplier_bill.new_supplier_bill');
    Route::get('passed_supplier_bill', 'SupplierBillResourceController@passedSupplierBills')->name('supplier_bill.passed_supplier_bill');
    Route::get('invalid_supplier_bill', 'SupplierBillResourceController@invalidSupplierBills')->name('supplier_bill.invalid_supplier_bill');
    Route::get('bill_supplier_bill', 'SupplierBillResourceController@billSupplierBills')->name('supplier_bill.bill_supplier_bill');

    Route::resource('operation', 'OperationResourceController');
//    Route::resource('trade_recode', 'TradeRecodeResourceController');
//    Route::resource('diary', 'DiaryResourceController');

    Route::resource('message', 'MessageResourceController');

    Route::resource('admin_user', 'AdminUserResourceController');
    Route::post('/admin_user/destroyAll', 'AdminUserResourceController@destroyAll')->name('admin_user.destroy_all');
    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');
    Route::get('logout', 'Auth\LoginController@logout');


});
Route::group([
    'namespace' => 'Supplier',
    'prefix' => 'supplier',
    'as' => 'supplier.',
], function () {
    Auth::routes();
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/home', 'ResourceController@home');
    Route::get('password', 'SupplierUserController@getPassword');
    Route::post('password', 'SupplierUserController@postPassword');

    Route::resource('supplier', 'SupplierResourceController');
    Route::post('/supplier/top_up/{supplier}', 'SupplierResourceController@topUp');
    Route::post('/supplier/fee_deduction/{supplier}', 'SupplierResourceController@feeDeduction');

    Route::resource('supplier_bill_item', 'SupplierBillItemResourceController');

    Route::resource('supplier_bill', 'SupplierBillResourceController');
    Route::get('supplier_bill/pay/{supplier_bill}', 'SupplierBillResourceController@pay');
    Route::post('supplier_bill/pay/{supplier_bill}', 'SupplierBillResourceController@paySubmit');

    Route::get('supplier_bill_import', 'SupplierBillResourceController@import')->name('supplier_bill.import');
    Route::post('/supplier_bill_submit_import', 'SupplierBillResourceController@submitImport')->name('supplier_bill.submit_import');

    Route::resource('message', 'MessageResourceController');

    Route::resource('supplier_user', 'SupplierUserResourceController');
    Route::post('/supplier_user/destroyAll', 'SupplierUserResourceController@destroyAll')->name('supplier_user.destroy_all');

    Route::resource('contract', 'ContractResourceController');
    Route::resource('supplier_contract', 'SupplierContractResourceController');

    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');
    Route::post('/file/{config}/{path?}', 'UploadController@uploadFile')->where('path', '(.*)');
});

Route::group([
    'namespace' => 'Airline',
    'prefix' => 'airline',
    'as' => 'airline.',
], function () {
    Auth::routes();
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/home', 'ResourceController@home');
    Route::get('password', 'AirlineUserController@getPassword');
    Route::post('password', 'AirlineUserController@postPassword');

    Route::resource('airline', 'AirlineResourceController');
    Route::resource('contract', 'ContractResourceController');
    Route::resource('airline_contract', 'AirlineContractResourceController');

    Route::get('supplier_bill', 'SupplierBillResourceController@index')->name('supplier_bill.index');
    Route::get('supplier_bill/{supplier_bill}', 'SupplierBillResourceController@show')->name('supplier_bill.show');
    Route::post('/supplier_bill/pass', 'SupplierBillResourceController@pass')->name('supplier_bill.pass');
    Route::post('/supplier_bill/reject', 'SupplierBillResourceController@reject')->name('supplier_bill.reject');
    Route::post('/supplier_bill/invalid', 'SupplierBillResourceController@invalid')->name('supplier_bill.invalid');
    Route::get('new_supplier_bill', 'SupplierBillResourceController@newSupplierBills')->name('supplier_bill.new_supplier_bill');
    Route::get('passed_supplier_bill', 'SupplierBillResourceController@passedSupplierBills')->name('supplier_bill.passed_supplier_bill');
    Route::get('invalid_supplier_bill', 'SupplierBillResourceController@invalidSupplierBills')->name('supplier_bill.invalid_supplier_bill');
    Route::get('bill_supplier_bill', 'SupplierBillResourceController@billSupplierBills')->name('supplier_bill.bill_supplier_bill');

    Route::resource('airline_bill', 'AirlineBillResourceController');
    Route::post('/airline_bill/invalid', 'AirlineBillResourceController@invalid')->name('airline_bill.invalid');
    Route::get('/airline_bill/download_word/{airline_bill}', 'AirlineBillResourceController@downloadWord')->name('airline_bill.download_word');
    Route::get('/airline_bill/download_excel/{airline_bill}', 'AirlineBillResourceController@downloadExcel')->name('airline_bill.download_excel');

    Route::get('airline_bill_import', 'AirlineBillResourceController@import')->name('airline_bill.import');
    Route::post('/airline_bill_submit_import', 'AirlineBillResourceController@submitImport')->name('airline_bill.submit_import');

    Route::get('new_airline_bill', 'AirlineBillResourceController@newAirlineBills')->name('airline_bill.new_airline_bill');
    Route::get('finished_airline_bill', 'AirlineBillResourceController@finishedAirlineBills')->name('airline_bill.finished_airline_bill');
    Route::get('invalid_airline_bill', 'AirlineBillResourceController@invalidAirlineBills')->name('airline_bill.invalid_airline_bill');

    Route::get('airline_bill/pay/{airline_bill}', 'AirlineBillResourceController@pay');
    Route::post('airline_bill/pay/{airline_bill}', 'AirlineBillResourceController@paySubmit');

    Route::resource('message', 'MessageResourceController');

    Route::resource('airline_user', 'AirlineUserResourceController');
    Route::post('/airline_user/destroyAll', 'AirlineUserResourceController@destroyAll')->name('airline_user.destroy_all');

    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');
    Route::post('/file/{config}/{path?}', 'UploadController@uploadFile')->where('path', '(.*)');
});

Route::group([
    'namespace' => 'Finance',
    'prefix' => 'finance',
    'as' => 'finance.',
], function () {
    Auth::routes();
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/home', 'ResourceController@home');
    Route::get('password', 'AirlineUserController@getPassword');
    Route::post('password', 'AirlineUserController@postPassword');

    Route::resource('airport', 'AirportResourceController');
    Route::post('/airport/destroyAll', 'AirportResourceController@destroyAll')->name('airport.destroy_all');

    Route::resource('supplier', 'SupplierResourceController');
    Route::post('/supplier/top_up/{supplier}', 'SupplierResourceController@topUp');

    Route::resource('finance_user', 'FinanceUserResourceController');
    Route::post('/finance_user/destroyAll', 'FinanceUserResourceController@destroyAll')->name('finance_user.destroy_all');

    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');
    Route::post('/file/{config}/{path?}', 'UploadController@uploadFile')->where('path', '(.*)');
});
/*
Route::group([
    'namespace' => 'Pc',
    'as' => 'pc.',
], function () {
    Auth::routes();
    Route::get('/user/login','Auth\LoginController@showLoginForm');
    Route::get('/','HomeController@home')->name('home');


    Route::get('email-verification/index','Auth\EmailVerificationController@getVerificationIndex')->name('email-verification.index');
    Route::get('email-verification/error','Auth\EmailVerificationController@getVerificationError')->name('email-verification.error');
    Route::get('email-verification/check/{token}', 'Auth\EmailVerificationController@getVerification')->name('email-verification.check');
    Route::get('email-verification-required', 'Auth\EmailVerificationController@required')->name('email-verification.required');

    Route::get('verify/send', 'Auth\LoginController@sendVerification');
    Route::get('verify/{code?}', 'Auth\LoginController@verify');

});
*/
//Route::get('
///{slug}.html', 'PagePublicController@getPage');
/*
Route::group(
    [
        'prefix' => trans_setlocale() . '/admin/menu',
    ], function () {
    Route::post('menu/{id}/tree', 'MenuResourceController@tree');
    Route::get('menu/{id}/test', 'MenuResourceController@test');
    Route::get('menu/{id}/nested', 'MenuResourceController@nested');

    Route::resource('menu', 'MenuResourceController');
   // Route::resource('submenu', 'SubMenuResourceController');
});
*/