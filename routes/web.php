<?php

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

$router->get('/api/cars/search/{vehicle}[/{conservation}[/{brand}[/{model}[/{city}[/{value1}[/{value2}[/{year1}[/{year2}[/{user}]]]]]]]]]',
    "CarsController@searchCars"
);

$router->get("/api/car/search/{cod}",
    "CarsController@searchOneCar"
);
