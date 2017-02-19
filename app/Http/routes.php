<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home', array('url' => route('calculate')));
});

Route::post('/', ['as' => 'calculate', function () {
    return view(
        'home',
        array(
            'url' => route(Route::current()->getName()),
            'result' => 'Now we have a result'
        )
    );
}]);
