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

Route::get('/', ['as' => 'home', 'uses' => function () {
    return view('home');
}]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/servers', 'ServersController@index')->name('servers');
Route::get('/servers/add', 'ServersController@add')->name('servers/add');
Route::post('/servers/insert', 'ServersController@insert')->name('servers/insert');
Route::post('/servers/update', 'ServersController@update')->name('servers/update');
Route::get('/servers/change/{serverid}', 'ServersController@change')->name('servers/change');
Route::get('/servers/delete/{serverid}', 'ServersController@delete')->name('servers/delete');
Route::post('/servers/remove', 'ServersController@remove')->name('servers/remove');

Route::get('/servergroups', 'ServerGroupsController@index')->name('servergroups');
Route::get('/servergroups/add', 'ServerGroupsController@add')->name('servergroups/add');
Route::post('/servergroups/insert', 'ServerGroupsController@insert')->name('servergroups/insert');
Route::post('/servergroups/update', 'ServerGroupsController@update')->name('servergroups/update');
Route::get('/servergroups/change/{servergroupid}', 'ServerGroupsController@change')->name('servergroups/change');
Route::get('/servergroups/delete/{servergroupid}', 'ServerGroupsController@delete')->name('servergroups/delete');
Route::post('/servergroups/remove', 'ServerGroupsController@remove')->name('servergroups/remove');

Route::get('/serverthresholdprofiles', 'ServerThresholdProfileController@index')->name('serverthresholdprofiles');
Route::get('/serverthresholdprofiles/add', 'ServerThresholdProfileController@add')->name('serverthresholdprofiles/add');
Route::post('/serverthresholdprofiles/insert', 'ServerThresholdProfileController@insert')->name('serverthresholdprofiles/insert');
Route::post('/serverthresholdprofiles/update', 'ServerThresholdProfileController@update')->name('serverthresholdprofiles/update');
Route::get('/serverthresholdprofiles/change/{profileid}', 'ServerThresholdProfileController@change')->name('serverthresholdprofiles/change');
Route::get('/serverthresholdprofiles/delete/{profileid}', 'ServerThresholdProfileController@delete')->name('serverthresholdprofiles/delete');
Route::post('/serverthresholdprofiles/remove', 'ServerThresholdProfileController@remove')->name('serverthresholdprofiles/remove');

Route::get('/databasethresholdprofiles', 'DatabaseThresholdProfileController@index')->name('databasethresholdprofiles');
Route::get('/databasethresholdprofiles/add', 'DatabaseThresholdProfileController@add')->name('databasethresholdprofiles/add');
Route::post('/databasethresholdprofiles/insert', 'DatabaseThresholdProfileController@insert')->name('databasethresholdprofiles/insert');
Route::post('/databasethresholdprofiles/update', 'DatabaseThresholdProfileController@update')->name('databasethresholdprofiles/update');
Route::get('/databasethresholdprofiles/change/{profileid}', 'DatabaseThresholdProfileController@change')->name('databasethresholdprofiles/change');
Route::get('/databasethresholdprofiles/delete/{profileid}', 'DatabaseThresholdProfileController@delete')->name('databasethresholdprofiles/delete');
Route::post('/databasethresholdprofiles/remove', 'DatabaseThresholdProfileController@remove')->name('databasethresholdprofiles/remove');

Route::get('/serverthresholdassignment', 'ServerThresholdAssignmentController@index')->name('serverthresholdassignment');
Route::get('/serverthresholdassignment/manage/{serverid}', 'ServerThresholdAssignmentController@manage')->name('serverthresholdassignment/manage');

Route::get('/dbconnections', 'DbConnectionController@index')->name('dbconnections');
Route::get('/dbconnections/add', 'DbConnectionController@add')->name('dbconnections/add');
Route::post('/dbconnections/insert', 'DbConnectionController@insert')->name('dbconnections/insert');
Route::post('/dbconnections/update', 'DbConnectionController@update')->name('dbconnections/update');
Route::get('/dbconnections/change/{servergroupid}', 'DbConnectionController@change')->name('dbconnections/change');
Route::get('/dbconnections/delete/{servergroupid}', 'DbConnectionController@delete')->name('dbconnections/delete');
Route::post('/dbconnections/remove', 'DbConnectionController@remove')->name('dbconnections/remove');

Route::get('/notifications', 'NotificationsController@index')->name('notifications');
