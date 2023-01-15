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

// rutas PARTIDOS -----------------------------------------------------------------------
Route::group(['prefix' => 'partidos'], function () {
    Route::post('save', function(Request $request){
        $misave =  App\Partido::create($request->all());

        //planilla a 
        $asiento = App\Asiento::create([
            'tipo'=> 'Ingreso',
            'cat_asiento_id'=> 2,
            'monto'=> setting('partidos.arbitraje'),
            'observacion'=> 'Cobro de Arbitraje',
            'estado'=> 'Pendiente',
            'editor_id' => $misave->editor_id,
            'planilla_id' => $misave->planilla_a_id,
            'monto_pagado' => 0,
            'monto_restante' => setting('partidos.arbitraje')
        ]);
        App\AsientoDetalle::create([
            'asiento_id'=> $asiento->id,
            'monto_pagado'=> 0,
            'user_id'=> $misave->editor_id
        ]);

        //planilla b
        $asiento = App\Asiento::create([
            'tipo'=> 'Ingreso',
            'cat_asiento_id'=> 2,
            'monto'=> setting('partidos.arbitraje'),
            'observacion'=> 'Cobro de Arbitraje',
            'estado'=> 'Pendiente',
            'editor_id' => $misave->editor_id,
            'planilla_id' => $misave->planilla_b_id,
            'monto_pagado' => 0,
            'monto_restante' => setting('partidos.arbitraje')
        ]);
        App\AsientoDetalle::create([
            'asiento_id'=> $asiento->id,
            'monto_pagado'=> 0,
            'user_id'=> $misave->editor_id
        ]);
        return $misave;
    });

    Route::get('/nomina/{planilla_id}', function ($planilla_id) {
        $nomina = App\RelPlanillaJugadore::where('planilla_id', $planilla_id)->with('jugador')->get();
        return $nomina;
    });

    Route::post('/update', function (Request $request) {
        $newpartido = App\Partido::where("id", $request->miid)->with("RelPartidoEvento")->first();
        $newpartido->hora_comienzo_pt = $request->hora_comienzo_pt;
        $newpartido->hora_comienzo_st = $request->hora_comienzo_st;
        $newpartido->juez_1 = $request->juez_1;
        $newpartido->juez_2 = $request->juez_2;
        $newpartido->juez_3 = $request->juez_3;
        $newpartido->juez_4 = $request->juez_4;
        $newpartido->status = 2;
        $newpartido->description = $request->description;        
        $newpartido->save();
        $newpartido = App\Partido::find($request->miid);
        return $newpartido;
    });

    Route::post('/rel/save', function (Request $request) {
        $new = App\RelPartidoNomina::create($request->all());
        return $new;
    });

    Route::post('/eventos/save', function (Request $request) {
        $new = App\RelPartidoEvento::create($request->all());
        return $new;
    });

    Route::post('/update/puntos', function (Request $request) {
        $newpartido = App\Partido::find($request->partido_id);
        $loop_a = App\RelPartidoNomina::where("partido_id", $request->partido_id)->where("nomina_id", $newpartido->planilla_a_id)->get();
        $gol_a = 0;
        $ta_a = 0;
        $tr_a = 0;
        
        // recorrer equipo A
        foreach ($loop_a as $item) {
            $jugador = App\TemporadaJugadore::where("jugadore_id", $item->jugador_id)-first();
            
            //tarjetas amarillas
            if ($item->ta > 0) {
                $ta_a += $item->ta;
                $jugador->ta +=  $item->ta;
                $asiento = App\Asiento::create([
                    'tipo'=> 'Ingreso',
                    'cat_asiento_id'=> 3,
                    'monto'=> setting('partidos.ta'),
                    'jugador_id'=> $item->jugador_id,
                    'observacion'=> 'Cobro de Tarjeta Amarilla',
                    'estado'=> 'Pendiente',
                    'editor_id' => $newpartido->editor_id,
                    'planilla_id' => $newpartido->planilla_a_id
                ]);
                App\AsientoDetalle::create([
                    'asiento_id'=> $asiento->id,
                    'monto_pagado'=> 0,
                    'user_id'=> $newpartido->editor
                ]);
            }

            //tarjetas rojas
            if ($item->tr > 0) {
                $tr_a += $item->tr;
                $jugador->tr +=  $item->tr;
                $asiento = App\Asiento::create([
                    'tipo'=> 'Ingreso',
                    'cat_asiento_id'=> 4,
                    'monto'=> setting('partidos.tr'),
                    'jugador_id'=> $item->jugador_id,
                    'observacion'=> 'Cobro de Tarjeta Roja',
                    'estado'=> 'Pendiente',
                    'editor_id' => $newpartido->editor,
                    'planilla_id' => $newpartido->planilla_a_id
                ]);
                App\AsientoDetalle::create([
                    'asiento_id'=> $asiento->id,
                    'monto_pagado'=> 0,
                    'user_id'=> $newpartido->editor
                ]);
            }

            //Goles
            if ($item->g1t > 0 || $item->g2t > 0) {
                $gol_a += ($item->g1t + $item->g2t);                
                $jugador->goles += ($item->g1t + $item->g2t);
                $jugador->partidos += 1;
            }

            //save temporada jugador
            $jugador->save();
        }

        // recorrer equipo A
        $loop_b = App\RelPartidoNomina::where("partido_id", $request->partido_id)->where("nomina_id", $newpartido->planilla_b_id)->get();
        $gol_b = 0;
        $ta_b = 0;
        $tr_b = 0;
        foreach ($loop_b as $item) {
            $jugador = App\TemporadaJugadore::where("jugadore_id", $item->jugador_id)-first();
            //tarjetas amarillas
            if ($item->ta > 0) {
                $ta_b += $item->ta;
                $jugador->ta +=  $item->ta;
                $asiento = App\Asiento::create([
                    'tipo'=> 'Ingreso',
                    'cat_asiento_id'=> 3,
                    'monto'=> setting('partidos.ta'),
                    'jugador_id'=> $item->jugador_id,
                    'observacion'=> 'Cobro de Tarjeta Amarilla',
                    'estado'=> 'Pendiente',
                    'editor_id' => $newpartido->editor,
                    'planilla_id' => $newpartido->planilla_b_id
                ]);
                App\AsientoDetalle::create([
                    'asiento_id'=> $asiento->id,
                    'monto_pagado'=> 0,
                    'user_id'=> $newpartido->editor
                ]);
            }
           
             //tarjetas rojas
            if ($item->tr > 0) {
                $tr_b += $item->tr;
                $jugador->tr +=  $item->tr;
                $asiento = App\Asiento::create([
                    'tipo'=> 'Ingreso',
                    'cat_asiento_id'=> 4,
                    'monto'=> setting('partidos.tr'),
                    'jugador_id'=> $item->jugador_id,
                    'observacion'=> 'Cobro de Tarjeta Roja',
                    'estado'=> 'Pendiente',
                    'editor_id' => $newpartido->editor,
                    'planilla_id' => $newpartido->planilla_b_id
                ]);
                App\AsientoDetalle::create([
                    'asiento_id'=> $asiento->id,
                    'monto_pagado'=> 0,
                    'user_id'=> $newpartido->editor
                ]);
            }
            
            //Goles
            if ($item->g1t > 0 || $item->g2t > 0) {
                $gol_b += ($item->g1t + $item->g2t);                
                $jugador->goles += ($item->g1t + $item->g2t);
                $jugador->partidos += 1;
            }

            //save temporada jugador
            $jugador->save();
        }
        
        //actualizar puntos de los equipos A y B por temoporadas
        $nomina_a = App\JugadoresPlanilla::find($newpartido->planilla_a_id);
        $nomina_b = App\JugadoresPlanilla::find($newpartido->planilla_b_id);
        $club_a = App\RelTemporadaClube::where("club_id", $nomina_a->clube_id)->where("temporada_id", $nomina_a->temporada_id)->first();
        $club_b = App\RelTemporadaClube::find("club_id", $nomina_b->clube_id)->where("temporada_id", $nomina_a->temporada_id)->first();
        if ($gol_a > $gol_b) {
            $newpartido->ganador = $gol_a;
            $newpartido->perdedor = $gol_b;            
            $club_a->puntos += setting('partidos.ganador');            
            $club_a->golesa += $gol_a;            
            $club_a->golesc += $gol_b; 
            $club_b->puntos += setting('partidos.perdedor');
           
        } else  if ($gol_a < $gol_b) {
            $newpartido->ganador = $gol_b;
            $newpartido->perdedor = $gol_a;
            $club_a->puntos += setting('partidos.perdedor');
            $club_b->puntos += setting('partidos.ganador');
            $club_b->golesa += $gol_b;            
            $club_b->golesc += $gol_a; 
        }else{
            $newpartido->ganador = null;
            $newpartido->perdedor = null;
            $newpartido->status = 4;
            $club_a->puntos += setting('partidos.empate');
            $club_b->puntos += setting('partidos.empate');
            $club_a->golesa += $gol_a;            
            $club_a->golesc += $gol_b; 
            $club_b->golesa += $gol_b;            
            $club_b->golesc += $gol_a; 
        }

        //actualizar cant partidos
        $club_a->partidos += 1; 
        $club_b->partidos += 1; 
              
        //actualizar TARJERAS
        $club_a->ta += $ta_a;
        $club_a->tr += $tr_a;
        $club_b->ta += $ta_b;
        $club_b->tr += $tr_b;

        //guardar 
        $newpartido->save();
        $club_a->save();
        $club_b->save();
        return true;
    });

    Route::post('/save/asientos', function (Request $request) {
        $newasi = App\Asiento::create($request-all());
        return $newasi;
    });
});

// rutas juGADORES -----------------------------------------------------------------------
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
        $ult_planilla= App\JugadoresPlanilla::where('clube_id', $request->clube_id)->where('activo', 'Aprobado')->first();

        if ($ult_planilla) {
            $ult_planilla->activo='Inactivo';
            $ult_planilla->save();
        }
            $temporada= App\Temporada::where('status', 1)->first();
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
                'activo'=>'Entregado',
                'user_id'=>$request->user_id,
                'temporada_id'=>$temporada->id
            ]);
            $planilla2= App\JugadoresPlanilla::where('id', $planilla->id)->with('clubes', 'delegado', 'user')->first();
        return $planilla2;
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

// rutas fixtures -------------------------------------------------------
Route::group(['prefix' => 'features'], function () {
    Route::post('save', function(Request $request){
        $misave =  App\Fixture::create($request->all());
        return $misave;
    });
    
    Route::post('descansa', function(Request $request){
        $misave =  App\RelTemporadaClube::find("club_id", $request->club_id);
        $misave->descansos += 1;
        $misave();
        return true;
    });
});



Route::post('asiento/save', function(Request $request){
    $asiento=App\Asiento::create([
        'tipo'=> $request->tipo,
        'cat_asiento_id'=> $request->cat_asiento_id,
        'monto'=> $request->monto,
        'editor_id'=> $request->editor_id,
        'planilla_id'=> $request->planilla_id,
        // 'clube_id'=> $request->clube_id,
        'jugador_id'=> $request->jugador_id,
        'observacion'=> $request->observacion,
        'estado'=> $request->estado,
        'monto_pagado'=> $request->monto_pagado,
        'monto_restante'=> $request->monto_restante
    ]);

    $detalle=App\AsientoDetalle::create([
        'asiento_id'=> $asiento->id,
        'monto_pagado'=> $request->monto_pagado,
        'user_id'=> $request->editor_id
    ]);

    return $asiento;
});

Route::get('asientos/get/planilla/{planilla_id}', function($planilla_id){
    $asientos=App\Asiento::where('planilla_id', $planilla_id)->with('categorias', 'jugadores', 'clubes')->get();
    return $asientos;
});

Route::get('find/asiento/{asiento_id}', function($asiento_id){
    $asiento=App\Asiento::where('id', $asiento_id)->with('detalles', 'categorias')->first();
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
//Restablecer Password encontrando usuario por equipo
Route::post('reset/credenciales/club', function(Request $request){
    $club=App\Clube::where('id', $request->clube_id)->with('user')->first();
    if ($club->user!=null) {
        $club->user->password=Hash::make($request->password);
        $club->user->save();
    }
    return $club->user;
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
        'name'=>$request->name,
        'phone'=>$request->phone
    ]);
    return $delegado;
});

//Todos los delegados
Route::get('all/delegados', function(){
    return App\Delegado::where('created_at','!=', null)->orderBy('created_at', 'desc')->get();
});

//DElegados por equipo
Route::get('delegados/find/club/{equipo_id}', function($equipo_id){
    return App\Delegado::where('clube_id', $equipo_id)->get();
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

//Save Decision Planilla
Route::post('save/decision/planilla', function(Request $request){
    $planilla= App\JugadoresPlanilla::find($request->planilla_id);
    $planilla->observacion= $request->observacion;
    $planilla->activo= $request->decision;
    $planilla->save();
    return true;
});

//Get Cat Asientos I u O
Route::get('get/cat/asientos/{tipo}', function($tipo){
    return App\AsientoCategoria::where('ingreso_egreso', $tipo)->get();
});

//Get Jugadores Planilla
Route::get('get/jugadores/planilla/{planilla_id}', function($planilla_id){
    return App\RelPlanillaJugadore::where('planilla_id', $planilla_id)->with('jugador')->get();
});

//Find Cat Asiento
Route::get('find/cat/asientos/{categoria_id}', function($categoria_id){
    return App\AsientoCategoria::find($categoria_id);
});

//Update Asiento and Create Asiento Detalle
Route::post('update/asiento', function(Request $request){

    $asiento=App\Asiento::find($request->asiento_id);
    $asiento->monto_restante= $request->monto_restante;
    $asiento->monto_pagado= ($asiento->monto_pagado+$request->monto_pagado);
    $asiento->estado= $request->estado;
    $asiento->observacion= $request->observacion;
    $asiento->save();

    $detalle= App\AsientoDetalle::create([
        'asiento_id'=> $request->asiento_id,
        'monto_pagado'=> $request->monto_pagado,
        'user_id'=> $request->user_id
    ]);

    return true;
});

//Find User ID
Route::get('find/user/id/{user_id}', function($user_id){
    return User::find($user_id);
});

//Update Cantidad Jugadores Deudores
Route::post('update/cant/jugs/deudores', function(Request $request){
    $planilla=App\JugadoresPlanilla::find($request->planilla_id);
    $planilla->cant_jugs_deudores=$request->cant_jugs_deudores;
    $planilla->save();
    return true;
});


//Delete Dependencias de Planilla por ser Rechazada
Route::post('delete/planilla', function(Request $request){
    $asientos= App\Asiento::where('planilla_id', $request->planilla_id)->with('detalles')->get();
    foreach ($asientos as $item) {
        foreach ($item->detalles as $item2) {
            $item2->delete();
        }
        $item->delete();
    }
    // $asientos= App\Asiento::where('planilla_id', $request->planilla_id)->with('detalles')->get();
    $planilla= App\JugadoresPlanilla::find($request->planilla_id);
    $planilla->activo= $request->decision;
    $planilla->save();
    return true;
});

Route::post('prueba/planilla', function(Request $request){
    return App\JugadoresPlanilla::where('id', $request->planilla_id)->with('clubes', 'delegado', 'user')->first();
});

Route::post('find/planilla', function(Request $request){
    return App\JugadoresPlanilla::where('id', $request->planilla_id)->with('clubes', 'delegado', 'user')->first();
});

Route::get('find/ultima/planilla/{clube_id}', function($clube_id){
    $planilla= App\JugadoresPlanilla::where('clube_id', $clube_id)->orderby('created_at', 'desc')->get();
    return $planilla;
});
