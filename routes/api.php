<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use RicardoPaes\Whaticket\Api;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\User;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'partidos'], function () {

    Route::get('/nomina/{planilla_id}', function ($planilla_id) {
        $nomina = App\RelPlanillaJugadore::where('planilla_id', $planilla_id)->with('jugador')->get();
        return $nomina;
    });

    Route::post('/save', function (Request $request) {
        $newpartido = App\Partido::create([
            'description' => $request->description,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'veedor_id' => $request->veedor_id,
            'planilla_a_id' => $request->planilla_a_id,
            'planilla_b_id' => $request->planilla_b_id,
            'hora_comienzo_pt' => $request->hora_comienzo_pt,
            'hora_comienzo_st' => $request->hora_comienzo_st,
            'categoria' => $request->categoria,
            'juez_1' => $request->juez_1,
            'juez_2' => $request->juez_2,
            'juez_3' => $request->juez_3,
            'juez_4' => $request->juez_4
        ]);
        return $newpartido;
    });

    Route::post('/rel/save', function (Request $request) {
        App\RelPartidoNomina::create([
            'partido_id' => $request->partido_id,
            'nomina_id' => $request->nomina_id,
            'delegado_id' => $request->delegado_a,
            'ta' => $request->ta,
            'tr' => $request->tr,
            'g1t' => $request->g1t,
            'g2t' => $request->g2t,
            'result1t' => $request->result1t,
            'result2t' => $request->result2t,
            'evaluacion' => $request->evaluacion,
            'jugador_id' => $request->jugador_id
        ]);
        return true;
    });

});

Route::group(['prefix' => 'jugadores'], function () {

    Route::get('planilla/find/jugadores/{clube_id}', function ($clube_id) {
        $jugadores= App\Jugadore::where('clube_id', $clube_id)->get();
        return $jugadores;
    });

    Route::get('find/{jugador_id}', function ($jugador_id) {
        $jugador= App\Jugadore::find($jugador_id);
        return $jugador;
    });

    Route::post('planilla/save', function (Request $request) {
        $ult_planilla= App\JugadoresPlanilla::where('clube_id', $request->clube_id)->where('categoria_jugadores', $request->categoria_jugadores)->where('activo', 1)->first();
        if ($ult_planilla) {
            $ult_planilla->activo=0;
            $ult_planilla->save();
        }
            $planilla= App\JugadoresPlanilla::create([
                'clube_id'=> $request->clube_id,
                'categoria_jugadores'=> $request->categoria_jugadores,
                'fecha_entrega'=> $request->fecha_entrega,
                'veedor_id'=> $request->veedor_id,
                'delegado_id'=> $request->delegado_id,
                'men_pagadas'=> $request->men_pagadas,
                'subtotal'=> $request->subtotal,
                'deuda'=> $request->deuda,
                'total'=> $request->total,
                'observacion'=> $request->observacion,
                'hora_entrega'=>$request->hora_entrega,
                'activo'=>1
            ]);
        return $planilla;
    });

    Route::post('rel/planilla/save', function (Request $request) {

        $tits= json_decode($request->titulares);

        foreach ($tits as $item) {
            $rel_planilla= App\RelPlanillaJugadore::create([
                'planilla_id'=>1,
                'jugador_id'=> $item->jugador_id,
                'titular'=> 1,
                'mensualidad'=> $item->mensualidad
            ]);
        }
        $sups= json_decode($request->suplentes);
        foreach ($sups as $item) {
            $rel_planilla= App\RelPlanillaJugadore::create([
                'planilla_id'=>1,
                'jugador_id'=> $item->jugador_id,
                'titular'=> 0,
                'mensualidad'=> $item->mensualidad
            ]);
        }
       
        return true;
    });

    Route::post('rel/planilla/jugs/save', function(Request $request) {
        $rel_planilla= App\RelPlanillaJugadore::create([
            'planilla_id'=>$request->planilla_id,
            'jugador_id'=> $request->jugador_id,
            'titular'=> $request->titular,
            'mensualidad'=> $request->mensualidad
        ]);
        return $rel_planilla;
    });


});


Route::post('asiento/save', function(Request $request){
    $asiento=App\Asiento::create([
        'tipo'=> $request->tipo,
        'detalle'=> $request->detalle,
        'monto'=> $request->monto,
        'editor_id'=> $request->editor_id,
        'planilla_id'=> $request->planilla_id,
        'clube_id'=> $request->clube_id,
        'jugador_id'=> $request->jugador_id,
        'observacion'=> $request->observacion,
        'estado'=> $request->estado,
        'monto_pagado'=> $request->monto_pagado,
        'monto_restante'=> $request->monto_restante
    ]);

    return $asiento;
});

Route::get('asientos/get/planilla/{planilla_id}', function($planilla_id){
    $asientos=App\Asiento::where('planilla_id', $planilla_id)->get();
    return $asientos;
});

Route::get('find/asiento/{asiento_id}', function($asiento_id){
    $asiento=App\Asiento::find($asiento_id);
    return $asiento;
});

Route::post('send/message', function(Request $request){
    $api = new Api(setting('chatbot.url'), setting('chatbot.token'));
    $api->sendMessage($request->telefono, $request->msj, setting('chatbot.whatsapp'));
    return true;
});

Route::post('send/text', function(Request $request){
    $api = new Api('https://api.appxi.net', 'd13aaed2-3751-46c0-8934-7f7bc00709f2');
    $api->sendMessage($request->telefono, $request->message, 1);
    return true;
});

Route::get('get/all/clubes', function(){
    return App\Clube::with('user', 'jugadores')->get();
});

Route::get('find/club/telefono/{telefono}', function($telefono){
    $equipo= App\Clube::where('wpp', $telefono)->first();
    return $equipo;
});

Route::get('find/club/user/{user_id}', function($user_id){
    $equipo= App\Clube::where('user_id', $user_id)->first();
    return $equipo;
});

//Restablecer Password-------
Route::post('credenciales', function(Request $request){
    $user=User::where('phone',$request->phone)->first();
    if ($user!=null) {
        $user->password=Hash::make($request->password);
        $user->save();
    }
    return $user;
});

//Mensaje con Whaticket
Route::post('/whaticket/send', function (Request $request) {
    $message = $request->message;
    $phone = $request->phone;
    $api = new Api(setting('chatbot.url'), setting('chatbot.token'));
    $api->sendMessage($phone, $message, setting('chatbot.whatsapp'));
    return true;
});

//Set Setting ID Whaticket
Route::post('update/wt/id', function(Request $request){
	$wt=$request->whaticket_id;
    DB::table('settings')->where('key', 'chatbot.whatsapp')->update(['value'=>$wt]);
    return true;
});

//Guardar Transferencia Jugador
Route::post('transferencia/jugador', function(Request $request){
    $transferencia= App\Transferencia::create([
        'jugadore_id'=>$request->jugadore_id,
        'clube_id_origen'=>$request->clube_id_origen,
        'clube_id_destino'=>$request->clube_id_destino,
        'observacion'=>$request->observacion,
        // 'precio'=>$request->precio
    ]);
    $jugador=App\Jugadore::find($request->jugadore_id);
    $jugador->clube_id=$request->clube_id_destino;
    $jugador->save();
    return $transferencia;
});

//Crear Delegado
Route::post('create/delegado', function(Request $request){
    $delegado= App\Delegado::create([
        'clube_id'=>$request->clube_id,
        'name'=>$request->name
    ]);
    return $delegado;
});

//Todos los delegados
Route::get('all/delegados', function(){
    return App\Delegado::where('created_at','!=', null)->orderBy('created_at', 'desc')->get();
});

//Crear Jugador
Route::post('create/jugador', function(Request $request){
    $jugador= App\Jugadore::create([
        'name'=>$request->name,
        'polera'=>$request->polera,
        'edad'=>$request->edad,
        'nacido'=>$request->nacido,
        // 'jug_categoria'=>$request->jug_categoria,
        'clube_id'=>$request->clube_id,
        // 'foto'=>$request->foto,
        // 'color_carnet'=>$request->color_carnet,
        'phone'=>$request->phone
        
    ]);
    return $jugador;
});