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

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
//Auth::routes();
// Route::get('/', function () {
//     return view('home');
// });

Route::get('autologin/{token}', ['as' => 'autologin', 'uses' => '\Watson\Autologin\AutologinController@autologin']);

Route::group(['namespace' => 'Frontend','middleware' => 'App\Http\Middleware\PublisherMiddleware'], function () {

  Route::get('/', 'FrontendController@index');

  Route::resource('maps', 'FrontendMapsController');
  Route::get('map-request', 'MapRequestController@store');

  Route::resource('my-maps', 'FrontendMyMapsController');
  Route::resource('my-slips', 'FrontendSlipsController');

    Route::post('changeHouseStatus', 'FrontendMyMapsController@houseStatus');
    //  Route::post('shareSlip', 'FrontendAssignmentsController@shareSlip');

    //::post('share/{ass_id}/{slip_id}', 'FrontendAssignmentHousesController@share')->name('slip.share');

    // if the variable Share is set to '1' this route will show the slip with ID = slipID



    Route::post('share', 'FrontendMyMapsController@share');




});

Route::group(['namespace' => 'Frontend', 'middleware' => 'auth'], function()
{
  Route::get('assignments/{assignmentID}/slips/{slipID}', 'FrontendAssignmentsController@showSlip');

  Route::post('changeHouseStatusShared', 'FrontendAssignmentsController@houseStatusShared');


});

//Route::group(['namespace' => 'Frontend'], function(){
//  Route::get('assignments/{assignmentID}/slips/{slipID}', 'FrontendAssignmentsController@showSlip');
//});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/territories/public', function(){
    return redirect('/login');
});
