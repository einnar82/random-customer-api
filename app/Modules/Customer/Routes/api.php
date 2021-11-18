<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Modules\Customer\Http\Middleware\CustomerMiddleware;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/customers', [
    'as' => 'customer.index',
    'uses' => 'CustomersController@index'
]);

$router->get('customers/{customer:[0-9]+}', [
    'as' => 'customer.show',
    'uses' => 'CustomersController@show',
    'middleware' => [CustomerMiddleware::class]
]);
