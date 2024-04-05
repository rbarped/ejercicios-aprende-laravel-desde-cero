<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// Ejercicio 1

Route::get('/ejercicio1', function () {
    return "GET OK";
});

Route::post('/ejercicio1', function () {
    return "POST OK";
});


// Ejercicio 2

Route::post('/ejercicio2/a', function (Request $request) { // Define una ruta POST que acepta una función
    return Response::json([ // Devuelve una respuesta JSON
        'name' => $request->get('name'), // Obtiene el valor del parámetro 'name' enviado en la solicitud POST
        'description' => $request->get('description'), // Obtiene el valor del parámetro 'description' enviado en la solicitud POST
        'price' => $request->get('price'), // Obtiene el valor del parámetro 'price' enviado en la solicitud POST
    ]);
});

//Petición POST que envía datos en formato json a la url especificada

// curl -X POST 
// -H "Content-Type: application/json" 
// -H "Accept: application/json" 
// -d '{"name":"keyboard","description":"Test de Prueba","price":89}' 
// http://localhost:8000/ejercicio2/a


Route::post('/ejercicio2/b', function (Request $request) {
    if ($request->get('price') < 0) {
        return Response::json(['message'=> "Price can't be less than 0"])->setStatusCode(422); 
        //setStatusCode sirve para darle un código de estado
    }

    return Response::json([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'price' => $request->get('price'),
    ]);
});

//Petición POST que comprueba si un producto tiene precio negativo y devuelve una respuesta según sea el precio

// $ curl -X POST 
// -H "Content-Type: application/json" 
// -H "Accept: application/json" 
// -d '{"name":"keyboard","description":"Test de Prueba","price":-89}' 
// http://localhost:8000/ejercicio2/b


Route::post('/ejercicio2/c', function (Request $request) {

    // Una forma simple para sacar el discount, con if-else
    $discount = 0;
    if ($request->query('discount') == "SAVE5") {
        $discount = 5;
    } else if ($request->query('discount') == "SAVE10") {
        $discount = 10;
    } else if ($request->query('discount') == "SAVE15") {
        $discount = 15;
    }

    // Otra forma de sacar el discount, con el match de PHP 8
    // $discount = match ($request->query('discount')) {
    //     'SAVE5' => 5,
    //     'SAVE10' => 10,
    //     'SAVE15' => 15,
    //     default => 0,
    // };

    // Calcular precio nuevo
    $price = (100 - $discount) / 100 * $request->get('price');

    // Devolver respuesta
    return Response::json([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'price' => $price,
        'discount' => $discount,
    ]);
});

// Petición POST que aplica un descuento (solo si existe)
// al producto y lo devuelve con el descuento aplicado
// y el precio final

// curl -X POST 
// -H "Content-Type: application/json" 
// -H "Accept: application/json" 
// -d '{"name":"keyboard","description":"Test de Prueba","price":89}' 
// http://localhost:8000/ejercicio2/c?discount=SAVE10
// {"name":"keyboard","description":"Test de Prueba","price":80.10000000000001,"discount":10}