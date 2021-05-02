<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/currencies', [CurrencyController::class, 'getCurrencies']);

Route::post('/convert', [CurrencyController::class, 'convertCurrencies']);

Route::post('/saveConversion', [CurrencyController::class, 'saveConversion']);

Route::post('/getConversions', [CurrencyController::class, 'getConversions']);


//Route::get('/convert/swop/currencies', function (Request $request) {
//
//    $response = Http::withHeaders([
//        'Authorization' => 'ApiKey ' . env('SWOP_KEY')
//    ])->get('https://swop.cx/rest/rates');
//
//    return $response->json();
//});

//Route::get('/convert/swop/{to}', function ($to, Request $request) {
//
//    $response = Http::withHeaders([
//        'Authorization' => 'ApiKey ' . env('SWOP_KEY')
//    ])->get('https://swop.cx/rest/rates/EUR/' . $to);
//
//    return $response->json();
//});
//
//Route::get('/convert/rapidapi/{to}', function ($to, Request $request) {
//
//    $response = Http::withHeaders([
//        'x-rapidapi-key' => env('RAPID_KEY')
//    ])->get('https://currency-exchange.p.rapidapi.com/listquotes');
//
//    return $response->json();
//});

