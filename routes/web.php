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

Route::get(
	'/campaign/{name}', 
	[
		'as' => 'campaign_router', 
		'uses' => 'CampaignController@campaign_router'
	]
)->where(['name' => '[a-z_\-]+']);
