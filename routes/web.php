<?php

use Illuminate\Support\Facades\Route;
use RicardoPaes\Whaticket\Api;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    // return view("/welcome");
});

// Route::get('/test', function () {
//     $jugafores = App\Jugadore::all();
//     foreach ($jugafores as $key) {
//         App\RelTemporadaJugadore::create([
//             'temporada_id' => 1,
//             'clube_id' => $key->clube_id,
//             'jugadore_id' => $key->id,
//             'ta' => 0,
//             'tr' => 0, 
//             'goles' => 0,
//             'partidos' => 0
//         ]);
//     }
//     return true;
// });

Route::get('/reset-db', function () {

    App\Asiento::truncate();
    App\AsientoDetalle::truncate();
    App\Fixture::truncate();
    App\JugadoresPlanilla::truncate();
    App\Partido::truncate();
    App\RelPartidoNomina::truncate();
    App\RelPlanillaJugadore::truncate();
    App\Transferencia::truncate();
    App\Transferencia::truncate();

    // App\Clube::where('id', '>', 0)->update([
    //     'status' => 1
    // ]);
    // App\RelTemporadaClube::where('id', '>', 0)->update([
    //     'partidos' => 0,
    //     'puntos' => 0,
    //     'golesa' => 0,
    //     'golesc' => 0,
    //     'ta' => 0,
    //     'tr' => 0,
    //     'descansos' => 0,
    // ]);
    
    // App\Jugadore::where('id', '>', 0)->update([
    //     'status' => 1
    // ]);

    // App\RelTemporadaJugadore::where('id', '>', 0)->update([
    //     'temporada_id' => 1,
    //     'ta' => 0,
    //     'tr' => 0,
    //     'goles' => 0,
    //     'partidos' => 0
    // ]);

    App\RelPartidoEvento::truncate();
    App\Jugadore::truncate();
    App\Clube::truncate();
    App\Delegado::truncate();
    App\RelTemporadaClube::truncate();
    App\RelTemporadaJugadore::truncate();

    // App\Models\User::truncate();
    // App\Models\User::create([
    //     'name' => 'admin',
    //     'email' => 'admin@admin.com',
    //     'password' => Hash::make('password'),
    //     'role_id' => 1
    // ]);

    return redirect("/admin");
});

Route::get('/club/{id}', function ($id) {
    return view("app.index", compact('id'));
});
Route::post('/uploads/image', function (Request $request) {
    return "hola";
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

