@extends('voyager::master')

@php
    $equipo= App\Clube::find($dataTypeContent->clube_id);
    $delegado= App\Delegado::find($dataTypeContent->delegado_id);
    $nomina = App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->id)->with('jugador')->get();
    $index=0;
    $index2=0;
    $index3=0;
    $index4=0;
    $index5=0;
    $asientos_planillas_pendientes=0;
    $asientos_planillas_pagados=0;
    $asientos_jugadores_pendientes=0;
    $asientos_jugadores_pagados=0;
    $asientos= App\Asiento::where('planilla_id', $dataTypeContent->id)->with('jugadores', 'clubes', 'categorias')->get();
    $num_jugadores=count($nomina);
    $equipo_titulo= App\Clube::find($dataTypeContent->clube_id);
    $planilla_jugs= App\JugadoresPlanilla::where('id', $dataTypeContent->id)->with('user')->first();
@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<div id="voyager-loader" class="mireload" hidden>
    <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
    @if($admin_loader_img == '')
        <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">
    @else
        <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
    @endif
</div>
    <div class="container-fluid">
        <br>                                                    
        <div class="row">
            <div class="col-sm-12 text-center" >
                @if (Auth::user()->role_id==1 || Auth::user()->role_id==5)
                    @if($dataTypeContent->activo=="Entregado")
                        <a  class="btn btn-info" data-toggle="modal" data-target="#modal_acciones_planilla">
                            <i class="glyphicon glyphicon-question-sign"></i> <span class="hidden-xs hidden-sm">Aprobar/Rechazar</span>
                        </a>
                    @endif
                @endif
                @if($dataTypeContent->activo=="Entregado")
                    <a  class="btn btn-danger" onclick="delete_planilla()">
                        <i class="glyphicon glyphicon-trash"></i> <span class="hidden-xs hidden-sm">Eliminar Planilla</span>
                    </a>
                @endif
            </div>
             <!-- Parte Izquierda Lienzo , Inputs Totales -->
             <div class="col-sm-3" id="div_izquierdo_detalles" hidden>


                <div class="form-group">
                    <label for="input_buscar_tab">Buscar</label>
                    <input id="input_buscar_tab" type="text" class="form-control" placeholder="Introducir Texto a Buscar">
                </div>
                
                <table class="table mitable">
                    <thead>
                        <tr class="active">
                            <th class="text-center" colspan="2">Datos de la Planilla</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="2">{{$equipo_titulo->name}}</td>
                        </tr>
                        <tr>
                            <td>
                                Gestión:
                            </td>
                            <td id="fecha_entrega_td">
                                {{ \Carbon\Carbon::parse($dataTypeContent->fecha_entrega)->format('m-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Estado:
                            </td>
                            <td >
                                @if ($dataTypeContent->activo=="Entregado")
                                <span class="label label-primary text-center">{{$dataTypeContent->activo}}</span>
                                @elseif ($dataTypeContent->activo=="Aprobado")
                                    <span class="label label-success text-center">{{$dataTypeContent->activo}}</span>
                                @elseif ($dataTypeContent->activo=="Rechazado")
                                    <span class="label label-danger text-center">{{$dataTypeContent->activo}}</span>
                                @elseif ($dataTypeContent->activo=="Inactivo")
                                    <span class="label label-warning text-center">{{$dataTypeContent->activo}}</span>

                                @endif 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Categoria:
                            </td>
                            <td>
                                {{$dataTypeContent->categoria_jugadores}}
                            </td>
                        </tr>
                        <tr>
          
                            <td class="text-center" colspan="2">
                                {{$delegado->name}}
                            </td>
                        </tr>
                     
                        <tr>
                            <td class="text-center" colspan="2">
                                {{$dataTypeContent->observacion}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Editor:
                            </td>
                            <td>
                                {{$planilla_jugs->user->name}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Deudores:
                            </td>
                            <td id="input_jugadores_deudores"></td>
                        </tr>
                    </tbody>

                </table>




                    <div class="form-group" hidden>
                        <label for="input_mens_esperadas">Mensualidades Esperadas</label>
                        <input class="form-control text-center" id="input_mens_esperadas" name="input_mens_esperadas" type="number" readonly >
                    </div>
                    <div class="form-group" hidden>
                        <label for="input_mens_pagadas">Mensualidades Pagadas</label>
                        <input class="form-control text-center" id="input_mens_pagadas" name="input_mens_pagadas" type="number" readonly >
                    </div>
          
                
                <div class="row" hidden>
                    <div class="col-sm-12">
                        <label for="input_otros_esperados">Otros Ingresos Esperados</label>
                        <input class="form-control text-center" id="input_otros_esperados" name="input_otros_esperados" type="number" readonly >
                    </div>
                    <div class="col-sm-12">
                        <label for="input_otros_pagados">Otros Ingresos Pagados</label>
                        <input class="form-control text-center" id="input_otros_pagados" name="input_otros_pagados" type="number" readonly >
                    </div>
                </div>
                
                <div class="row" hidden>
                    <div class="col-sm-12">
                        <label for="input_subtotal">Total Esperado</label>
                        <input class="form-control text-center" id="input_subtotal" name="input_subtotal" type="number" readonly>
                    </div>
                    <div class="col-sm-12">
                        <label for="input_deudas">Monto Adeudado</label>
                        <input class="form-control text-center" id="input_deudas" name="input_deudas" type="number" readonly value="{{$dataTypeContent->deuda}}">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-12" hidden>
                        <label for="input_total">Total Pagado</label>
                        <input class="form-control text-center" id="input_total" name="input_total" type="number" readonly value="{{$dataTypeContent->total}}">
                    </div>
                </div>    
                        
            </div>
            
            <!-- Parte Derecha Lienzo , Asientos -->
            <div class="col-sm-9" id="div_derecho_detalles" hidden>
                {{-- <h3 class="text-center">Pagos de Jugadores y Equipo</h3> --}}
                <div class="col-sm-4 form-group">
                    <label for="select_cat_asientos">Tipo Asientos</label>
                    <div class="miselect">    
                        <select class="form-control select2" name="select_cat_asientos" id="select_cat_asientos">
                            <option value="jugadores">Jugadores</option>
                            <option value="club">Club</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 form-group">
                    <label for="select_tabs">Filtros</label>
                    <div class="miselect">    
                        <select class="form-control select2" name="select_tabs" id="select_tabs">
                            <option value="tab_todos">Todos</option>
                            <option value="tab_pendientes">Pendientes</option>
                            <option value="tab_pagados">Pagados</option>
                        </select>
                    </div>
                </div>

 

                <div class="col-sm-4 form-group">
                    <br>

    
                    <div id="btn_nomina" class="text-center">
                        <a  class="btn btn-primary btn-sm" onclick="change_derecha_lienzo(2)">
                            <i class="glyphicon glyphicon-th-list"></i> <span class="">Visualizar Nómina</span>
                        </a>                
                    </div>
                </div>


                <div id="tab_pendientes_jugadores" hidden>
                    <div class="table-responsive" >
                        <table class="table mitable" id="tabla_tab_pendientes_jugadores">
                            <thead>
                                <tr class="active">
                                    <th class="text-center" scope="col">#</th>
                                    <th class="text-center" scope="col">Jugador</th>
                                    <th class="text-center" scope="col">Categoria</th>
                                    <th class="text-center" scope="col">Monto</th>
                                    <th class="text-center" scope="col">Estado</th>
                                    <th class="text-center" scope="col">Observación</th>
                                    <th class="text-center" scope="col">Acción</th>
                                </tr>                            
                            </thead>
                            <tbody>
                                @foreach ($asientos as $item)
                                    @if($item->estado!="Pagado")
                                        @if ($item->categorias->tipo=="jugador")                                    
                                            @php
                                                $index3=$index3+1;
                                                $asientos_jugadores_pendientes+=$item->monto_restante;
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    {{$index3}}
                                                    <input id="check_pendientes_{{$item->id}}" type="checkbox">
                                                </td>
                                                <td>
                                                    {{$item->jugadores->name}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->categorias->title}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->monto}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->estado}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->observacion}}
                                                </td>
                                                <td class='text-center'>
                                                    @if ($item->estado!="Pagado")
                                                        <button onclick="set_input_modal({{$item->id}})" class="btn  btn-primary btn-xs " data-toggle="modal" data-target="#modal_pago">Pagar</button>
                                                    @else
                                                        <button onclick="set_input_modal({{$item->id}})" class="btn  btn-warning btn-xs " data-toggle="modal" data-target="#modal_pago">Ver</button>
                                                    @endif
                                                </td>                                        
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center"><h4>TOTAL PENDIENTE</h4></td><td colspan="4" class="text-center"><h4>{{$asientos_jugadores_pendientes}}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12" >
                        <a class="btn btn-dark btn-block form-group" onclick="validar_check('pendientes')">Pagar Seleccionados</a>
                    </div>
                </div>

                <div class="col-sm-12 table-responsive" id="tab_pagados_jugadores" hidden>
                    <table class="table mitable" id="tabla_tab_pagados_jugadores">
                        <thead class="thead-dark">
                            <tr class="active">
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Jugador</th>
                                <th class="text-center" scope="col">Categoria</th>
                                <th class="text-center" scope="col">Monto</th>
                                <th class="text-center" scope="col">Estado</th>
                                <th class="text-center" scope="col">Observación</th>
                                <th class="text-center" scope="col">Acción</th>
                            </tr>                            
                        </thead>
                        <tbody>
                            @foreach ($asientos as $item)
                                @if($item->estado=="Pagado")
                                    @if ($item->categorias->tipo=="jugador")                                    
                                        @php
                                            $index4=$index4+1;
                                            $asientos_jugadores_pagados+=$item->monto_pagado;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{$index4}}
                                            </td>
                                            <td>
                                                {{$item->jugadores->name}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->categorias->title}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->monto}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->estado}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->observacion}}
                                            </td>
                                            <td class='text-center'>
                                                @if ($item->estado!="Pagado")
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-primary btn-xs " data-toggle="modal" data-target="#modal_pago">Pagar</button>
                                                @else
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-warning btn-xs " data-toggle="modal" data-target="#modal_pago">Ver</button>
                                                @endif
                                            </td>                                        
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-center"><h4>TOTAL PAGADO</h4></td><td colspan="4" class="text-center"><h4>{{$asientos_jugadores_pagados}}</h4></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab_todos_jugadores">
                    <div class="col-sm-12 table-responsive" >
                        <table class="table mitable" id="tabla_tab_todos_jugadores">
                            <thead>
                                <tr class="active">
                                    <th class="text-center" scope="col">#</th>
                                    <th class="text-center" scope="col">Jugador</th>
                                    <th class="text-center" scope="col">Categoria</th>
                                    <th class="text-center" scope="col">Monto</th>
                                    <th class="text-center" scope="col">Estado</th>
                                    <th class="text-center" scope="col">Observación</th>
                                    <th class="text-center" scope="col">Acción</th>
                                </tr>                            
                            </thead>
                            <tbody>
                                @foreach ($asientos as $item)
                                    @if ($item->categorias->tipo=="jugador")                                    
                                        @php
                                            $index2=$index2+1;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{$index2}}
                                                @if ($item->estado!="Pagado")
                                                    <input id="check_todos_{{$item->id}}" type="checkbox">
                                                @endif
                                            </td>
                                            <td>
                                                {{$item->jugadores->name}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->categorias->title}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->monto}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->estado}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->observacion}}
                                            </td>
                                            <td class='text-center'>
                                                @if ($item->estado!="Pagado")
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-primary btn-xs " data-toggle="modal" data-target="#modal_pago">Pagar</button>
                                                @else
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-warning btn-xs " data-toggle="modal" data-target="#modal_pago">Ver</button>
                                                @endif
                                            </td>
                                        
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center"><h4>TOTAL PAGADO</h4></td><td colspan="4" class="text-center"><h4>{{$asientos_jugadores_pagados}}</h4></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center"><h4>TOTAL PENDIENTE</h4></td><td colspan="4" class="text-center"><h4>{{$asientos_jugadores_pendientes}}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <a class="btn btn-dark btn-block form-group" onclick="validar_check('todos')">Pagar Seleccionados</a>
                    </div>
                </div>

                <div id="tab_pendientes_club" hidden>
                    <div class="col-sm-12 table-responsive" >
                        <table class="table table-striped mitable" id="tabla_tab_pendientes_club">
                            <thead>
                                <tr class="active">
                                    <th class="text-center" scope="col">#</th>
                                    <th class="text-center" scope="col">Categoria</th>
                                    <th class="text-center" scope="col">Monto</th>
                                    <th class="text-center" scope="col">Estado</th>
                                    <th class="text-center" scope="col">Observación</th>
                                    <th class="text-center" scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asientos as $item)
                                    @if($item->estado!="Pagado")
                                        @if ($item->categorias->tipo=="planilla")                                    
                                            @php
                                                $index3=$index3+1;
                                                $asientos_planillas_pendientes+=$item->monto_restante;
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    {{$index3}}
                                                    <input id="check_pendientes_{{$item->id}}" type="checkbox">
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->categorias->title}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->monto}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->estado}}
                                                </td>
                                                <td class='text-center'>
                                                    {{$item->observacion}}
                                                </td>
                                                <td class='text-center'>
                                                    @if ($item->estado!="Pagado")
                                                        <button onclick="set_input_modal({{$item->id}})" class="btn  btn-primary btn-xs " data-toggle="modal" data-target="#modal_pago">Pagar</button>
                                                    @else
                                                        <button onclick="set_input_modal({{$item->id}})" class="btn  btn-warning btn-xs " data-toggle="modal" data-target="#modal_pago">Ver</button>
                                                    @endif
                                                </td>                                        
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center"><h4>TOTAL PENDIENTE</h4></td><td colspan="3" class="text-center"><h4>{{$asientos_planillas_pendientes}}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12" >
                        <a class="btn btn-dark btn-block form-group" onclick="validar_check('pendientes')">Pagar Seleccionados</a>
                    </div>
                </div>

                <div class="col-sm-12 table-responsive" id="tab_pagados_club" hidden>
                    <table class="table table-striped mitable" id="tabla_tab_pagados_club">
                        <thead>
                            <tr class="active">
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Categoria</th>
                                <th class="text-center" scope="col">Monto</th>
                                <th class="text-center" scope="col">Estado</th>
                                <th class="text-center" scope="col">Observación</th>
                                <th class="text-center" scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asientos as $item)
                                @if($item->estado=="Pagado")
                                    @if ($item->categorias->tipo=="planilla")                                    
                                        @php
                                            $index4=$index4+1;
                                            $asientos_planillas_pagados+=$item->monto_pagado;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{$index4}}
                                            </td>                                          
                                            <td class='text-center'>
                                                {{$item->categorias->title}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->monto}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->estado}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->observacion}}
                                            </td>
                                            <td class='text-center'>
                                                @if ($item->estado!="Pagado")
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-primary btn-xs " data-toggle="modal" data-target="#modal_pago">Pagar</button>
                                                @else
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-warning btn-xs " data-toggle="modal" data-target="#modal_pago">Ver</button>
                                                @endif
                                            </td>                                        
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-center"><h4>TOTAL PAGADO</h4></td><td colspan="3" class="text-center"><h4>{{$asientos_planillas_pagados}}</h4></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab_todos_club" hidden>
                    <div class="col-sm-12 table-responsive" >
                        <table class="table table-striped mitable" id="tabla_tab_todos_club">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" scope="col">#</th>
                                    <th class="text-center" scope="col">Categoria</th>
                                    <th class="text-center" scope="col">Monto</th>
                                    <th class="text-center" scope="col">Estado</th>
                                    <th class="text-center" scope="col">Observación</th>
                                    <th class="text-center" scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asientos as $item)
                                    @if ($item->categorias->tipo=="planilla")                                    
                                        @php
                                            $index2=$index2+1;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{$index2}}
                                                @if ($item->estado!="Pagado")
                                                    <input id="check_todos_{{$item->id}}" type="checkbox">
                                                @endif
                                            </td>
                                            <td class='text-center'>
                                                {{$item->categorias->title}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->monto}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->estado}}
                                            </td>
                                            <td class='text-center'>
                                                {{$item->observacion}}
                                            </td>
                                            <td class='text-center'>
                                                @if ($item->estado!="Pagado")
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-primary btn-xs " data-toggle="modal" data-target="#modal_pago">Pagar</button>
                                                @else
                                                    <button onclick="set_input_modal({{$item->id}})" class="btn  btn-warning btn-xs " data-toggle="modal" data-target="#modal_pago">Ver</button>
                                                @endif
                                            </td>                                    
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center"><h4>TOTAL PAGADO</h4></td><td colspan="3" class="text-center"><h4>{{$asientos_planillas_pagados}}</h4></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center"><h4>TOTAL PENDIENTE</h4></td><td colspan="3" class="text-center"><h4>{{$asientos_planillas_pendientes}}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="col-sm-12">
                        <a class="btn btn-dark btn-block form-group" onclick="validar_check('todos')">Pagar Seleccionados</a>
                    </div>
                </div>
            </div>

            <!-- Parte Derecha Lienzo, Nomina (Acción con botón)-->
            <div class="col-sm-9" id="div_derecho_nomina" hidden>
                <div  class="col-sm-12 table-responsive">            
                    <table class="table mitable" id="table2">
                        <thead>
                            <tr class="active">
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Edad</th>
                                <th class="text-center" scope="col">Polera</th>
                                <th class="text-center" scope="col">Nombres y Apellidos</th>
                                <th class="text-center" scope="col">Mensualidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center"><span class="label label-primary">Titulares</span></td>
                            </tr>
                            @foreach ($nomina as $item)
                                @if ($item->titular==1)
                                    @php
                                        $index=$index+1;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            {{$index}}
                                        </td>
                                        <td class='text-center'>
                                            {{$item->jugador->edad}}
                                        </td>
                                        <td class='text-center'>
                                            <span class="label label-primary">
                                            {{$item->jugador->polera}}
                                            </span>
                                        </td>
                                        <td>
                                           
                                                {{$item->jugador->name}}
                                           
                                        </td>
                                        <td class='text-center'>
                                            <span class="label label-success">
                                                {{$item->mensualidad}}
                                            </span>
                                        </td>
                                    </tr>
                                @endif                          
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-center"><span class="label label-primary">Suplentes</span></td>
                            </tr>
                            @foreach ($nomina as $item)
                                @if ($item->titular==2)
                                    @php
                                        $index=$index+1;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            {{$index}}
                                        </td>
                                        <td class='text-center'>
                                            {{$item->jugador->edad}}
                                        </td>
                                        <td class='text-center'>
                                            {{$item->jugador->polera}}
                                        </td>
                                        <td class='text-center'>
                                            {{$item->jugador->name}}
                                        </td>
                                        <td class='text-center'>
                                            {{$item->mensualidad}}
                                        </td>
                                    </tr>
                                @endif                          
                            @endforeach                              
                        </tbody>                   
                    </table>
                    <div id="btn_pagos" class="text-center" hidden>
                        <a  class="btn btn-success btn-sm" onclick="change_derecha_lienzo(1)">
                            <i class="glyphicon glyphicon-euro"></i> <span class="">Visualizar Pagos</span>
                        </a>  
                    </div>
                </div>
            </div>


            <div class="col-sm-12" id="div_total_detalles">
                @if ($dataTypeContent->activo=="Entregado")
                    <h4 class="text-center">Esta Planilla aun no ha sido aprobada o rechazada, espere a que se tome una decisión porfavor.</h4>
                @elseif($dataTypeContent->activo=="Rechazado")
                    <h4 class="text-center">Esta Planilla fue Rechazada, los motivos u observaciones son las siguientes:</h4>
                    <p class="text-center">{{$dataTypeContent->observacion}}</p>
                @endif
            </div>
        </div>   

        <!--Jugadores -->
        @if($dataTypeContent->activo=="Entregado" || $dataTypeContent->activo=="Rechazado")
        <div class="row" id="div_nomina_inicial">
            <div class="col-sm-12 text-center" >
                <h2>NÓMINA DE JUGADORES</h2>
            </div>              
            <div  class="col-sm-12 table-responsive">            
                <table class="table mitable" id="table2">
                    <thead>
                        <tr class="active">
                            <th class="text-center" scope="col">#</th>
                            <th class="text-center" scope="col">Edad</th>
                            <th class="text-center" scope="col">Polera</th>
                            <th class="text-center" scope="col">Nombres y Apellidos</th>
                            <th class="text-center" scope="col">Mensualidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center"><h4>Titulares</h4></td>
                        </tr>
                        @foreach ($nomina as $item)
                            @if ($item->titular==1)
                                @php
                                    $index5=$index5+1;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        {{$index5}}
                                    </td>
                                    <td class='text-center'>
                                        {{$item->jugador->edad}}
                                    </td>
                                    <td class='text-center'>
                                        {{$item->jugador->polera}}
                                    </td>
                                    <td>
                                        {{$item->jugador->name}}
                                    </td>
                                    <td class='text-center'>
                                        <span class="label label-success">
                                            {{$item->mensualidad}}
                                        </span>
                                    </td>
                                </tr>
                            @endif                          
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-center"><h4>Suplentes</h4></td>
                        </tr>
                        @foreach ($nomina as $item)
                            @if ($item->titular==2)
                                @php
                                    $index5=$index5+1;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        {{$index5}}
                                    </td>
                                    <td class='text-center'>
                                        {{$item->jugador->edad}}
                                    </td>
                                    <td class='text-center'>
                                        {{$item->jugador->polera}}
                                    </td>
                                    <td class='text-center'>
                                        {{$item->jugador->name}}
                                    </td>
                                    <td class='text-center'>
                                        {{$item->mensualidad}}
                                    </td>
                                </tr>
                            @endif                          
                        @endforeach                              
                    </tbody>                  
                </table>
            </div> 
        </div>
        @endif
                   
        
    </div>


    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::generic.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!--Modal Pagos -->
    <div class="modal" tabindex="-1" id="modal_pago" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Pago Deuda</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div hidden>
                    <input type="number" id="input_id_asiento_modal">
                </div>
                <div class="col-sm-12 table-responsive">
                    <table class="table table-striped table-bordered" id="table_modal_pagos">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" scope="col">Categoria</th>
                                <th class="text-center" scope="col">Monto Adeudado</th>
                                <th class="text-center" scope="col">Monto Pagado</th>
                                <th class="text-center" scope="col">Monto Restante a Pagar</th>
                                <th class="text-center" scope="col">Observación</th>


                            </tr>
                            

                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div class="col-sm-12">
                    <button id="visualizar_historial" onclick="historial_pagos(1)">Ver Historial de Pagos</button>
                    <button id="minimizar_historial" onclick="historial_pagos(0)" hidden>Minimizar Historial de Pagos</button>
                </div>
                <div class="col-sm-12" id="div_historial" hidden>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table_historial_pagos">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" scope="col">Fecha</th>
                                    <th class="text-center" scope="col">Monto Amortizado</th>
                                    <th class="text-center" scope="col">Editor</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

              <div class="col-md-8"></div>
                <div class="col-sm-12 col-md-4" id="div_monto_a_pagar" hidden>
                    <label for="input_monto_a_pagar">Monto a Pagar</label>
                    <input class="form-control form-group" type="number" id="input_monto_a_pagar">
                </div>
              

            </div>
            <div class="modal-footer" >
                <div id="div_botones_a_pagar" hidden>
                    <button onclick="save_asiento_individual()" type="button" class="btn btn-primary">Guardar</button>
                    {{-- <button  type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>       --}}
                </div>
            </div>
          </div>
        </div>
    </div>

    <!-- Modal Aprobar Rechazar-->
    <div class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_acciones_planilla">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tomar Acción Sobre Planilla</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                    
                    <div class="form-group col-6">
                        <label for="select_accion">Acción Decisiva</label>
                        <div class="miselect">                                
                            <select class="form-control select2" name="select_accion" id="select_accion">
                                <option value="Aprobado">Aprobar</option>
                                <option value="Rechazado">Rechazar</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group col-6">
                        <label for="">Observaciones</label>                             
                        <textarea class="form-control" name="text_area_observacion" rows="5" id="text_area_observacion"  ></textarea>
                    </div>                            
                </div>
                <div class="modal-footer mt-3">
                    {{-- <a type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</a> --}}
                    <a type="button" onclick="save_decision()" class="btn btn-sm btn-dark">Guardar</a>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        async function validar_check(seleccionada) {
            var validacion=0
            var asientos= await axios("/api/asientos/get/planilla/"+"{{$dataTypeContent->id}}")
            for (let index = 0; index < asientos.data.length; index++) {
                if (asientos.data.estado!="Pagado" && $("#check_"+seleccionada+"_"+asientos.data[index].id+"").prop('checked')) {
                    validacion+=1
                }
            }
            if (validacion>0) {
                misave(seleccionada)
            }
            else{
                toastr.error("Seleccione las casillas correspondientes de los asientos que quiere pagar")
                toastr.error("NO SELECCIONÓ NINGUN ASIENTO ")

            }
        }

        function misave(seleccionada){
            Swal.fire({
                title: 'Estás Seguro?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('.mireload').attr("hidden", false)
                    checkbox(seleccionada)
                }
            })
        }

        function delete_planilla(){
            Swal.fire({
                title: 'Estás Seguro?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('.mireload').attr("hidden", false)
                    delete_planilla_now()
                }
            })
        }

        async function delete_planilla_now() {
            var midata_eliminado={
                planilla_id: parseInt("{{$dataTypeContent->id}}"),
                decision: "Inactivo"
            }
            var validacion= await axios.post("/api/delete/planilla", midata_eliminado)
            if (validacion.data) {
                location.href="/admin/jugadores-planillas"
            }
        }

        function save_decision(){
            $('.mireload').attr("hidden", false)
            $("#modal_acciones_planilla .close").click()
            acciones_planilla()
        }

        function save_asiento_individual(){
            Swal.fire({
                title: 'Estas Seguro de Pagar el Asiento?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                    comprobar_monto_asiento()
                }
            })
        }

        async function change_derecha_lienzo(value) {
            if (value==1) {
                $("#btn_nomina").attr("hidden", false)
                $("#btn_pagos").attr("hidden", true)
                $('#div_derecho_detalles').attr("hidden", false)
                $('#div_derecho_nomina').attr("hidden", true)

            }
            else{
                $("#btn_nomina").attr("hidden", true)
                $("#btn_pagos").attr("hidden", false)
                $('#div_derecho_nomina').attr("hidden", false)
                $('#div_derecho_detalles').attr("hidden", true)
            }
        }

        async function acciones_planilla(){
            var midata={
                observacion: $("#text_area_observacion").val(),
                planilla_id: "{{$dataTypeContent->id}}",
                decision: $("#select_accion").val()
            }
            var planilla= await axios.post("/api/find/planilla", {planilla_id: parseInt("{{ $dataTypeContent->id }}")})
            var phone_club=planilla.data.clubes.wpp
            var phone_delegado=planilla.data.delegado.phone
            if (await validacion_wpp(phone_club)) {
                phone_club=phone_club.toString()
            }
            if (await validacion_wpp(phone_delegado)) {
                phone_delegado=phone_delegado.toString()
            }
            var mitext=""

            if ($("#select_accion").val()=="Rechazado") {
                var midata_rechazado={
                    planilla_id: parseInt("{{$dataTypeContent->id}}"),
                    decision: $("#select_accion").val()
                }
                await axios.post("/api/delete/planilla", midata_rechazado)
                mitext+="--------------- *Planilla de Jugadores* ---------------\n------------------------ *Rechazada* ------------------------\n\n"
                mitext+="*Club*:\n"
                mitext+="- "+planilla.data.clubes.name+"\n"
                mitext+="Fecha: "+planilla.data.fecha+"\n\n"               
                mitext+="*Observación*:\n"
                mitext+="- "+$("#text_area_observacion").val()
                mitext+="\n\nNota.- Pueden proceder a crear una nueva planilla si así lo requieren tomando en cuenta las observaciones dadas.\n\n"        
            }
            else{
                mitext+="--------------- *Planilla de Jugadores* ---------------\n------------------------ *Aprobada* ------------------------\n\n"
                    mitext+="*Club*:\n"
                    mitext+="- "+planilla.data.clubes.name+"\n"
                    mitext+="Fecha: "+planilla.data.fecha+"\n\n"               
                    mitext+="*Observación*:\n"
                    mitext+="- "+$("#text_area_observacion").val()
                    mitext+="\n\nNota.- Pueden proceder a realizar los pagos de las deudas que deben si es que las tienen.\n\n"
            }
            await mensaje_decision_planilla(phone_club, phone_delegado, mitext)

            var decision= await axios.post("/api/save/decision/planilla", midata)
            if (decision.data) {
                location.reload()
            }
        }

        async function mensaje_decision_planilla(phone_club, phone_delegado, mitext) {
            if (phone_club==phone_delegado) {
                if (await validacion_wpp(phone_club)) {
                    var newpassword=Math.random().toString().substring(2, 6)
                    var midata_cred = {
                        clube_id: parseInt("{{$dataTypeContent->clube_id}}"),
                        password: newpassword
                    }
                    var user= await axios.post("/api/reset/credenciales/club", midata_cred)
                    console.log("hola")
                    mitext+="*Credenciales*:\n"
                    mitext+="Usuario: "+user.data.email+"\n"
                    mitext+="Contraseña: "+newpassword+"\n\n"
                    mitext+="Link del Sistema: {{setting('admin.url')}}"
                    var midata={
                        phone: phone_club,
                        message: mitext
                    }
                    try {
                        await axios.post("/api/whaticket/send", midata)
                    } catch (error) {
                        toastr.error("fallo con whatsapp.")
                    }
                    
                }
            }
            else{
                console.log("hola2")
                if (await validacion_wpp(phone_delegado)) {
                    var midata={
                        phone: phone_delegado,
                        message: mitext
                    }
                    try {
                        await axios.post("/api/whaticket/send", midata)
                    } catch (error) {
                        toastr.error("fallo con whatsapp.")
                    }
                }

                if (await validacion_wpp(phone_club)) {
                    var newpassword=Math.random().toString().substring(2, 6)
                    var midata_cred = {
                        clube_id: parseInt("{{$dataTypeContent->clube_id}}"),
                        password: newpassword
                    }
                    var user= await axios.post("/api/reset/credenciales/club", midata_cred)
                    mitext+="*Credenciales*:\n"
                    mitext+="Usuario: "+user.data.email+"\n"
                    mitext+="Contraseña: "+newpassword+"\n\n"
                    mitext+="Link del Sistema: {{setting('admin.url')}}"

                    var midata2={
                        phone: phone_club,
                        message: mitext
                    }
                    try {
                        await axios.post("/api/whaticket/send", midata2)
                    } catch (error) {
                        toastr.error("fallo con whatsapp.")
                    }
                }
            }
        }

        async function checkbox(seleccionada) {
            var asientos= await axios("/api/asientos/get/planilla/"+"{{$dataTypeContent->id}}")
            for (let index = 0; index < asientos.data.length; index++) {
                    if (asientos.data[index].estado!="Pagado" && $("#check_"+seleccionada+"_"+asientos.data[index].id+"").prop('checked')) {
                        console.log("siuuu")
                        console.log(asientos.data[index].id)
                        var observacion="Ya pagó el total de la deuda"
                        var midata={
                            asiento_id: asientos.data[index].id,
                            monto_restante: 0,
                            monto_pagado: asientos.data[index].monto_restante,
                            estado: "Pagado",
                            user_id: parseInt("{{Auth::user()->id}}"),
                            observacion:observacion
                        }
                        console.log(midata)
                        await axios.post("/api/update/asiento", midata)
                    }
            }
            location.reload()
        }

        async function validacion_wpp(phone){
            var wpp=parseInt(phone)
            if (wpp<=79999999 && wpp>=60000000) {
                return true;
            }
            else{
                return false;
            }
        }

        async function set_input_modal(id) {
            $('#table_modal_pagos tbody').empty();
            $('#table_historial_pagos tbody').empty();
            $("#input_id_asiento_modal").val(parseInt(id))
            var asiento= await axios("/api/find/asiento/"+(parseInt(id)))
            $("#table_modal_pagos").append("<tr><td class='text-center'>"+asiento.data.categorias.title+"</td> <td class='text-center'>"+asiento.data.monto+"</td> <td class='text-center'>"+asiento.data.monto_pagado+"</td> <td class='text-center'>"+asiento.data.monto_restante+"</td> <td class='text-center'>"+asiento.data.observacion+"</td></tr>")
            if (asiento.data.detalles.length>0) {
                for (let index = 0; index < asiento.data.detalles.length; index++) {
                    var usuario= await axios("/api/find/user/id/"+asiento.data.detalles[index].user_id)
                    $("#table_historial_pagos").append("<tr><td class='text-center'>"+asiento.data.detalles[index].fecha+"<br><small>"+asiento.data.detalles[index].published+"</small></td><td class='text-center'>"+asiento.data.detalles[index].monto_pagado+"</td><td class='text-center'>"+usuario.data.name+"</td></tr>")
                }
            }
            if (asiento.data.estado=="Pagado") {
                $("#div_monto_a_pagar").attr("hidden", true)
                $("#div_botones_a_pagar").attr("hidden", true)
            }
            else{
                $("#div_monto_a_pagar").attr("hidden", false)
                $("#div_botones_a_pagar").attr("hidden", false)
            }
        }

        async function ingresos() {
            var asientos= await axios("/api/asientos/get/planilla/"+"{{$dataTypeContent->id}}")
            var newlist=asientos.data
            localStorage.setItem('misasientos', JSON.stringify(newlist));
            var misasientos = JSON.parse(localStorage.getItem('misasientos'));
            var ingresos_esperados=0
            var ingresos_pagados=0
            var mensualidades_esperadas=parseInt("{{$num_jugadores}}")*parseInt("{{setting('finanzas.mensualidad_jug')}}")
            var mensualidades_pagadas=0
            const unicos = [];
            for (let index = 0; index < asientos.data.length; index++) {
                if (asientos.data[index].categorias.title!="Mensualidades") {
                    if (asientos.data[index].estado=="Pagado") {
                        ingresos_pagados+=parseInt(asientos.data[index].monto)
                    }
                    ingresos_esperados+=parseInt(asientos.data[index].monto)
                }
                else{
                    if (asientos.data[index].estado=="Pagado") {
                        mensualidades_pagadas+=parseInt(asientos.data[index].monto_pagado)
                    }
                }
                if (asientos.data[index].estado!="Pagado" && asientos.data[index].categorias.tipo=="jugador") {
                    const valor = asientos.data[index].jugadores.id;
                    if (unicos.indexOf(valor) < 0) {
                    unicos.push(valor);
                    }
                }
            }
            $("#input_otros_esperados").val(ingresos_esperados)
            $("#input_otros_pagados").val(ingresos_pagados)
            $("#input_mens_esperadas").val(mensualidades_esperadas)
            $("#input_mens_pagadas").val(mensualidades_pagadas)
            $("#input_subtotal").val((mensualidades_esperadas+ingresos_esperados))
            $("#input_deudas").val((mensualidades_esperadas+ingresos_esperados-ingresos_pagados-mensualidades_pagadas))
            $("#input_total").val(ingresos_pagados+mensualidades_pagadas)
            $("#input_jugadores_deudores").html(unicos.length)
            var midata={
                cant_jugs_deudores:unicos.length,
                planilla_id: parseInt("{{$dataTypeContent->id}}")
            }
            await axios.post("/api/update/cant/jugs/deudores", midata)
        }

        async function visualizar_tabs(value){
            var seleccionado= value
            $("#input_buscar_tab").val("")
            var array_jugs=['tab_todos_jugadores','tab_pendientes_jugadores','tab_pagados_jugadores']
            var array_club=['tab_todos_club', 'tab_pendientes_club', 'tab_pagados_club']
            if ($("#select_cat_asientos").val()=="jugadores") {
                var tabla="tabla_"+seleccionado+"_jugadores"
                jQuery("#"+tabla+" tbody>tr").show();
                $('#'+seleccionado+"_jugadores").attr("hidden", false)
                for (let index = 0; index < array_jugs.length; index++) {
                    if (array_jugs[index]!=value+"_jugadores") {
                        $('#'+array_jugs[index]).attr("hidden", true)
                    }
                }
                for (let index = 0; index < array_club.length; index++) {
                    $('#'+array_club[index]).attr("hidden", true)
                }
            }
            if ($("#select_cat_asientos").val()=="club") {
                var tabla="tabla_"+seleccionado+"_club"
                jQuery("#"+tabla+" tbody>tr").show();
                $('#'+seleccionado+"_club").attr("hidden", false)
                for (let index = 0; index < array_club.length; index++) {
                    if (array_club[index]!=value+"_club") {
                        $('#'+array_club[index]).attr("hidden", true)
                    }
                }
                for (let index = 0; index < array_jugs.length; index++) {
                    $('#'+array_jugs[index]).attr("hidden", true)
                }
            }
        }

        $("#select_tabs").change(async function () {
            visualizar_tabs(this.value)
        })

        $("#select_cat_asientos").change(async function(){
            visualizar_tabs( $("#select_tabs").val())
        })

        ingresos()
        validacion_estado()

        function historial_pagos(valor) {
            if (valor) {
                $("#visualizar_historial").attr("hidden", true)
                $("#minimizar_historial").attr("hidden", false)
                $("#div_historial").attr("hidden", false)
            }
            else{
                $("#visualizar_historial").attr("hidden", false)
                $("#minimizar_historial").attr("hidden", true)
                $("#div_historial").attr("hidden", true)
            }
        }

        jQuery("#input_buscar_tab").keyup(function(){
            var cat_asientos= $("#select_cat_asientos").val()
            var filtro_select= $("#select_tabs").val()
            var tabla= "tabla_"+filtro_select+"_"+cat_asientos
            if( jQuery(this).val() != ""){
                jQuery("#"+tabla+" tbody>tr").hide();
                jQuery("#"+tabla+" td:contiene-palabra('" + jQuery(this).val() + "')").parent("tr").show();
            }
            else{
                jQuery("#"+tabla+" tbody>tr").show();
            }
        });
        
        jQuery.extend(jQuery.expr[":"], 
        {
            "contiene-palabra": function(elem, i, match, array) {
                return (elem.textContent || elem.innerText || jQuery(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        async function comprobar_monto_asiento() {
            var monto_a_pagar= parseInt($("#input_monto_a_pagar").val())
            var asiento_id=$("#input_id_asiento_modal").val()
            var asiento= await axios("/api/find/asiento/"+asiento_id)
            if (monto_a_pagar>asiento.data.monto_restante) {
                toastr.error("El monto que intenta ingresar es mayor del requerido")
                $("#input_monto_a_pagar").val(asiento.data.monto_restante)
            }
            if (monto_a_pagar<=asiento.data.monto_restante) {
                $('.mireload').attr("hidden", false)
                $("#modal_pago .close").click()
                await pagar_asiento_individual(asiento)
            }

        }

        async function validacion_estado() {
            if("{{$dataTypeContent->activo}}"=="Entregado" || "{{$dataTypeContent->activo}}"=="Rechazado"){
                $("#div_total_detalles").attr("hidden", false)
                $("#div_izquierdo_detalles").attr("hidden", true)
                $("#div_derecho_detalles").attr("hidden", true)
            }
            else{
                $("#div_total_detalles").attr("hidden", true)
                $("#div_izquierdo_detalles").attr("hidden", false)
                $("#div_derecho_detalles").attr("hidden", false)
            }
        }


        async function pagar_asiento_individual(asiento) {
            var monto_a_pagar= parseInt($("#input_monto_a_pagar").val())
            var asiento_id=$("#input_id_asiento_modal").val()
            var estado=""
            var observacion=""
            if (monto_a_pagar<asiento.data.monto_restante) {
                estado="Pendiente"
                observacion="Debe Mensualidad"
            }
            else{
                estado="Pagado"
                observacion="Ya pagó el total de la deuda"
            }
            var midata={
                asiento_id: parseInt(asiento_id),
                monto_restante: (asiento.data.monto_restante-monto_a_pagar),
                monto_pagado: monto_a_pagar,
                estado: estado,
                user_id: parseInt("{{Auth::user()->id}}"),
                observacion:observacion
            }
            console.log(midata)
            var asiento= await axios.post("/api/update/asiento", midata)
            if (asiento.data) {
                location.reload()          
            }

        }

        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {

                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script>
@stop
