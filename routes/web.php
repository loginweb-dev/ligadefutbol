<?php

use Illuminate\Support\Facades\Route;
use RicardoPaes\Whaticket\Api;
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
    return redirect("/admin/profile");
});

Route::get('/reset-db', function () {

    App\Asiento::truncate();
    App\AsientoDetalle::truncate();
    App\Fixture::truncate();
    App\JugadoresPlanilla::truncate();
    App\Partido::truncate();
    App\RelPartidoNomina::truncate();
    App\RelPlanillaJugadore::truncate();
    App\Transferencia::truncate();

    App\Clube::where('id', '>', 0)->update([
        'status' => 1
    ]);
    App\RelTemporadaClube::where('id', '>', 0)->update([
        'partidos' => 0,
        'puntos' => 0,
        'golesa' => 0,
        'golesc' => 0,
        'ta' => 0,
        'tr' => 0,
        'descansos' => 0,
        'temporada_id' => 1
    ]);
    
    App\Jugadore::where('id', '>', 0)->update([
        'status' => 1
    ]);
    App\RelTemporadaJugadore::truncate();

    return redirect("/admin");
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

