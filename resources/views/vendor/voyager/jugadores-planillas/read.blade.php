@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    @php
        $equipo_titulo= App\Clube::find($dataTypeContent->clube_id);
    @endphp
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} de {{$equipo_titulo->name}} &nbsp;
        
        {{-- @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan --}}
        {{-- @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan --}}

       
                      
               
        
        
       
    </h1>
    <div class="text-center">
        @can('browse', $dataTypeContent)
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
        </a>
        @endcan
    
    <a  class="btn btn-success">
            <i class="glyphicon glyphicon-envelope"></i> <span class="hidden-xs hidden-sm">Enviar Lista</span>
        </a>
    
    
    
    <a  class="btn btn-info">
            <i class="glyphicon glyphicon-question-sign"></i> <span class="hidden-xs hidden-sm">Aprobar/Rechazar</span>
        </a>
        @include('voyager::multilingual.language-selector')
    </div>

    <hr>
@stop

@section('content')

    @php
        $equipo= App\Clube::find($dataTypeContent->clube_id);
        $delegado= App\Delegado::find($dataTypeContent->delegado_id);
        $nomina = App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->id)->with('jugador')->get();
        $index=0;
        $index2=0;
        $index3=0;
        $asientos= App\Asiento::where('planilla_id', $dataTypeContent->id)->with('jugadores', 'clubes')->get();
        $num_jugadores=count($nomina);
    @endphp
    {{-- <div class="page-content read container-fluid"> --}}
    <div class="panel panel-bordered" style="padding-bottom:5px;">
        <div class="row">
            <div class="col-md-12">

                {{-- <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="page-content edit-add container-fluid">
                        <div class="row">
                            <div class="col-md-12"> --}}
                                {{-- <div class="panel panel-bordered"> --}}
                                    {{-- <div class="text-center">
                                        <h2 class="subs">MUTUAL DE EX JUGADORES DE FUTBOL TRINIDAD</h2>
                                        <span class="subs">FUNDADA EL 8 DE FEBRERO DE 1987 <br> MEDIANTE RESOLUCIÓN PREFECTURAL Nª. 050/99</span>
                                    </div> --}}
                                    <hr>
                
                                    {{-- <div class="row"> --}}
                                 
                                        {{-- <div class="col-md-12"> --}}
                
                                            <div class="col-sm-4 form-group" hidden>
                                                <label for="input_club">Club</label>
                                                <div style="border-style: outset;">                                
                                                    {{-- <select class="form-control select2" name="select_club" id="select_club">
                                                        <option value="null">Elegir el Club</option>
                                                        @foreach ($equipos  as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach     
                                                    </select> --}}
                                                    <input type="text" class="form-control" id="input_club" value="{{$equipo->name}}" readonly>
                                                </div>
                                            </div>
                
                                            <div class="col-sm-4 form-group">
                                                <label for="select_cat">Categoria</label>
                                                <div style="border-style: outset;">    
                                                    {{-- <select class="form-control select2" name="select_cat" id="select_cat">
                                                        <option value="">Elegir Categoria</option>
                                                        <option value="Senior">Senior</option>
                                                        <option value="Especial">Especial</option>
                                                    </select> --}}

                                                    <input type="text" class="form-control" id="input_cat" value="{{$dataTypeContent->categoria_jugadores}}" readonly>

                                                </div>
                                            </div>
                
                                            <div class="col-sm-4 form-group">
                                                <label for="select_delegado">Delegado</label>
                                                <div style="border-style: outset;">    
                                               
                                                    <input type="text" class="form-control" id="input_delegdo" value="{{$delegado->name}}" readonly>

                                                </div>                   
                                            </div>
                                            {{-- <div class="col-sm-12">
                                                <hr>
                                            </div> --}}
                                          
                                            <div class="col-sm-4 form-group">
                                                <label for="input_fecha">Gestión</label>
                                                <div style="border-style: outset;">                                

                                                <input class="form-control" type="month" name="input_fecha" id="input_fecha" value="{{ \Carbon\Carbon::parse($dataTypeContent->fecha_entrega)->format('Y-m') }}" readonly>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-4 form-group">
                                                <label for="input_hora">Hora de Entrega</label>
                        
                                                <input class="form-control" type="text" name="input_hora" id="input_hora" value="{{ \Carbon\Carbon::parse($dataTypeContent->hora_entrega)->format('H:i') }}"  readonly>
                                            </div> --}}
                                           	{{-- <div class="col-sm-4 form-group">
                                                <label for="input_gestion">Gestion</label>
                        
                                                <input class="form-control" type="text" name="input_gestion" id="input_gestion"  readonly>
                                            </div> --}}
                                    
                            
                                        {{-- </div> --}}
                                        <hr>
                                        {{-- <div class="col-md-12 text-center"> --}}

                                            <div class="text-center" >

                                                <h3 class="subs">
                                                    NÓMINA DE JUGADORES
                                                </h3>
                                                
                                                <h4 class="subs col-md-12">Titulares</h4>

                                            </div>
                                          
                                            <div  class="col-md-12 col-sm-12 table-responsive">
                                                <table class="table table-striped table-bordered" id="table2">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th class="text-center" scope="col">#</th>
                                                            <th class="text-center" scope="col">Edad</th>
                                                            <th class="text-center" scope="col">Polera</th>
                                                            <th class="text-center" scope="col">Nombres y Apellidos</th>
                                                            <th class="text-center" scope="col">Mensualidad</th>
                                                        </tr>
                                                        
                    
                                                    </thead>
                                                    <tbody>
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
                                              
                                            <div class="text-center" >

                                                <h4 class="subs col-md-12">Suplentes</h4>
                                            </div>
                
                                            <div  class="col-md-12 col-sm-12 table-responsive">
                                                <table class="table table-striped table-bordered" id="table3">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th class="text-center" scope="col">#</th>
                                                            <th class="text-center" scope="col">Edad</th>
                                                            <th class="text-center" scope="col">Polera</th>
                                                            <th class="text-center" scope="col">Nombres y Apellidos</th>
                                                            <th class="text-center" scope="col">Mensualidad</th>
                                                        </tr>
                    
                                                    </thead>
                                                    <tbody>
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
                                            </div>
                                            <div class="col-md-9 form-group">

                                            </div>
                
                                            <hr>

                                            <div class="text-center" >

                                                <h3 class="subs col-md-12">DETALLES</h3>
                                            </div>
                                            <div class="col-md-8 col-sm-12">
                                                <div class="col-md-3 col-sm-12 form-group">
                                                    <label for="select_tabs">Filtros</label>
                                                    <div style="border-style: outset;">    
                                                        <select class="form-control select2" name="select_tabs" id="select_tabs">
                                                            <option value="Todos">Todos</option>
                                                            <option value="Pendientes">Pendientes</option>
                                                            <option value="Pagados">Pagados</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-5 form-group">
                                                    <label for="input_buscar_tab">Buscar</label>
                                                        <input id="input_buscar_tab" type="text" class="form-control" placeholder="Introducir Texto de Búsqueda">
                                                </div>
                                                <div class="col-md-12 col-sm-12 table-responsive" id="tab_todos">
                                                    {{-- <label for="text_area_deudas">Descripción</label>
                                                    <textarea class="form-control" name="text_area_deudas" rows="5" id="text_area_deudas" readonly >{{$dataTypeContent->observacion}}</textarea> --}}

                                                    <table class="table table-striped table-bordered" id="table3">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th class="text-center" scope="col">#</th>
                                                                <th class="text-center" scope="col">Jugador</th>
                                                                <th class="text-center" scope="col">Detalle</th>
                                                                <th class="text-center" scope="col">Monto</th>
                                                                <th class="text-center" scope="col">Estado</th>
                                                                <th class="text-center" scope="col">Observación</th>
                                                                <th class="text-center" scope="col">Acción</th>


                                                            </tr>
                                                            
                        
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($asientos as $item)
                                                                @php
                                                                    $index2=$index2+1;
                                                                @endphp

                                                                <tr>
                                                                    <td class="text-center">
                                                                        {{$index2}}
                                                                        @if ($item->estado!="Pagado")
                                                                            <input id="check_{{$item->id}}" type="checkbox">
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{$item->jugadores->name}}
                                                                    </td>
                                                                    <td class='text-center'>
                                                                        {{$item->detalle}}
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

                                                                        @endif
                                                                    </td>
                                                                
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-sm-8 table-responsive" id="tab_pendientes" hidden>
                                                    {{-- <label for="text_area_deudas">Descripción</label>
                                                    <textarea class="form-control" name="text_area_deudas" rows="5" id="text_area_deudas" readonly >{{$dataTypeContent->observacion}}</textarea> --}}

                                                    <table class="table table-striped table-bordered" id="table3">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th class="text-center" scope="col">#</th>
                                                                <th class="text-center" scope="col">Jugador</th>
                                                                <th class="text-center" scope="col">Detalle</th>
                                                                <th class="text-center" scope="col">Monto</th>
                                                                <th class="text-center" scope="col">Estado</th>
                                                                <th class="text-center" scope="col">Observación</th>
                                                                <th class="text-center" scope="col">Acción</th>


                                                            </tr>
                                                            
                        
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($asientos as $item)
                                                                @if($item->estado!="Pagado")

                                                                    @php
                                                                        $index3=$index3+1;
                                                                    @endphp

                                                                    <tr>
                                                                        <td class="text-center">
                                                                            {{$index3}}
                                                                            <input id="check_{{$item->id}}" type="checkbox">
                                                                        </td>
                                                                        <td>
                                                                            {{$item->jugadores->name}}
                                                                        </td>
                                                                        <td class='text-center'>
                                                                            {{$item->detalle}}
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

                                                                            @endif
                                                                        </td>
                                                                    
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <a class="btn btn-dark btn-block form-group" onclick="validar_check()">Pagar Seleccionados</a>
                                                </div>
                                                <hr>

                                            </div>


                                            <div class="col-sm-4">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="input_mens_esperadas">Mensualidades Esperadas</label>
                                                        <input class="form-control text-center" id="input_mens_esperadas" name="input_mens_esperadas" type="number" readonly >
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="input_mens_pagadas">Mensualidades Pagadas</label>
                                                        <input class="form-control text-center" id="input_mens_pagadas" name="input_mens_pagadas" type="number" readonly >
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="input_otros_esperados">Otros Ingresos Esperados</label>
                                                        <input class="form-control text-center" id="input_otros_esperados" name="input_otros_esperados" type="number" readonly >
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="input_otros_pagados">Otros Ingresos Pagados</label>
                                                        <input class="form-control text-center" id="input_otros_pagados" name="input_otros_pagados" type="number" readonly >
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label for="input_subtotal">Total Esperado</label>
                                                        <input class="form-control text-center" id="input_subtotal" name="input_subtotal" type="number" readonly>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="input_deudas">Monto Adeudado</label>
                                                        <input class="form-control text-center" id="input_deudas" name="input_deudas" type="number" readonly value="{{$dataTypeContent->deuda}}">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="input_total">Total Pagado</label>
                                                        <input class="form-control text-center" id="input_total" name="input_total" type="number" readonly value="{{$dataTypeContent->total}}">
                                                    </div>
                                                </div>
                                                
                                               


                                            </div>
                                           
                
                                            
                                        {{-- </div> --}}
                                    {{-- </div> --}}
                
                
                                    {{-- <div class="row">
                                        <div class="col-sm-offset-3 col-sm-6">
                                            <div class="form-group">
                                                
                                                <button class="btn btn-dark btn-block" onclick="misave()">Guardar Formulario</button>
                                            </div>
                                        </div>
                                    </div> --}}
                                   
                
                                {{-- </div> --}}
                            {{-- </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
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
    </div><!-- /.modal -->

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
                                <th class="text-center" scope="col">Detalle</th>
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

              <div class="col-md-8"></div>
                <div class="col-sm-12 col-md-4">
                    <label for="input_monto_a_pagar">Monto a Pagar</label>
                    <input class="form-control form-group" type="number" id="input_monto_a_pagar">
                </div>
              

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>


@stop

@section('javascript')
    {{-- @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}

    <script>

        async function validar_check() {
            var validacion=0
            var asientos= await axios("{{setting('admin.url')}}api/asientos/get/planilla/"+"{{$dataTypeContent->id}}")
            for (let index = 0; index < asientos.data.length; index++) {
                // if (asientos.data.estado!="Pagado") {
                    if (asientos.data.estado!="Pagado" && $("#check_"+asientos.data[index].id+"").prop('checked')) {
                        validacion+=1
                    }
                // }
               
            }
            if (validacion>0) {
                misave()
            }
            else{
                toastr.error("Seleccione las casillas correspondientes de los asientos que quiere pagar")
                toastr.error("NO SELECCIONÓ NINGUN ASIENTO ")

            }
        }

       

        function misave(){
            Swal.fire({
                title: 'Estás Seguro?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                    checkbox()
                }
            })
        }

        async function checkbox() {
            var asientos= await axios("{{setting('admin.url')}}api/asientos/get/planilla/"+"{{$dataTypeContent->id}}")
            for (let index = 0; index < asientos.data.length; index++) {
                // if (asientos.data.estado!="Pagado") {
                    if (asientos.data.estado!="Pagado" && $("#check_"+asientos.data[index].id+"").prop('checked')) {
                        console.log("siuuu")
                    }
                // }
               
            }
           
        }

        async function set_input_modal(id) {
            $('#table_modal_pagos tbody').empty();

            $("#input_id_asiento_modal").val(parseInt(id))
            var asiento= await axios("{{setting('admin.url')}}api/find/asiento/"+(parseInt(id)))
            $("#table_modal_pagos").append("<tr><td class='text-center'>"+asiento.data.detalle+"</td> <td class='text-center'>"+asiento.data.monto+"</td> <td class='text-center'>0</td> <td class='text-center'>"+asiento.data.monto+"</td> <td class='text-center'>"+asiento.data.observacion+"</td></tr>")
        }

        async function ingresos() {

            var asientos= await axios("{{setting('admin.url')}}api/asientos/get/planilla/"+"{{$dataTypeContent->id}}")

            // localStorage.getItem('micaja')
            // var misasientos = JSON.parse(localStorage.getItem('misasientos'));
            var newlist=asientos.data
            localStorage.setItem('misasientos', JSON.stringify(newlist));
            var misasientos = JSON.parse(localStorage.getItem('misasientos'));
            console.log(misasientos)


            var ingresos_esperados=0
            var ingresos_pagados=0
            var mensualidades_esperadas=parseInt("{{$num_jugadores}}")*parseInt("{{setting('finanzas.mensualidad_jug')}}")
            var mensualidades_pagadas=0

            for (let index = 0; index < asientos.data.length; index++) {
                if (asientos.data[index].detalle!="Mensualidades") {
                    if (asientos.data[index].estado=="Pagado") {
                        ingresos_pagados+=parseInt(asientos.data[index].monto)
                    }
                   
                    ingresos_esperados+=parseInt(asientos.data[index].monto)

                }
                else{
                    if (asientos.data[index].estado=="Pagado") {
                        mensualidades_pagadas+=parseInt(asientos.data[index].monto)
                    }
                    
                }
            }
            $("#input_otros_esperados").val(ingresos_esperados)
            $("#input_otros_pagados").val(ingresos_pagados)

            $("#input_mens_esperadas").val(mensualidades_esperadas)
            mensualidades_pagadas+=parseInt("{{$dataTypeContent->men_pagadas}}")
            $("#input_mens_pagadas").val(mensualidades_pagadas)

            $("#input_subtotal").val((mensualidades_esperadas+ingresos_esperados))
            $("#input_deudas").val((mensualidades_esperadas+ingresos_esperados-ingresos_pagados-mensualidades_pagadas))
            $("#input_total").val(ingresos_pagados+mensualidades_pagadas)

            
        }
         ingresos()

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
