@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    
    $equipos= App\Clube::all();
    $jugadores= App\Jugadore::all();
    $delegados= App\Delegado::all();
    $club_unico=false;
    $equipos_transferencia=false;
    if (Auth::user()->role_id!=1) {
        $club_unico=App\Clube::where('user_id', Auth::user()->id)->with('jugadores')->first();
        $equipos_transferencia=App\Clube::where('user_id','!=',Auth::user()->id)->get();
    }
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
<div id="voyager-loader" class="mireload" hidden>
    <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
    @if($admin_loader_img == '')
        <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">
    @else
        <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
    @endif
</div>
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered">

                    <div class="row">        
                            <div class="col-sm-12 text-center">
                            	<h2>PASO 1.- NÓMINA DE JUGADORES</h2>
                            	{{-- <p>Formulario para el registro de nominas de jugadores del club o equipo: {{ $club_unico->name }}</p> --}}
                            </div>

                            <div class="col-sm-4" id="club_div" hidden>
                                <label for="select_club">Club</label>
                                <div style="border-style: outset;">                                
                                    <select class="form-control select2" name="select_club" id="select_club">
                                        @if(Auth::user()->role_id!=1)
                                            <option value="{{$club_unico->id}}">{{$club_unico->name}}</option>
                                        @else
                                            @foreach ($equipos  as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach   
                                        @endif
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="select_cat">Categoria</label>
                                <div style="border-style: outset;">    
                                    <select class="form-control select2" name="select_cat" id="select_cat">
                                        {{-- <option value="">Elegir Categoria</option> --}}
                                        <option value="Senior">Senior</option>
                                        <option value="Especial">Especial</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="input-group">
                                    <label>Delegado</label>
                                    <div style="border-style: outset;">  
                                    <select class="form-control select2" name="select_delegado" id="select_delegado">
                                        @foreach ($delegados  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach  
                                    </select>
                                    </div>
                                    <br>
                                    <span class="input-group-btn">
                                        <a  class="btn btn-sm  btn-dark" data-toggle="modal" data-target="#modal_delegado" ><span>Crear </span>  <i class="voyager-plus"></i>  </a>    
                                    </span>
                                </div>
                                {{-- <div style="border-style: outset;">    
                                   
                                </div> --}}
                                {{-- <input type="text" class="form-control" placeholder="Recipient's username" aria-describedby="basic-addon2"> --}}
                                {{-- <span class="input-group-addon" id="basic-addon2">@example.com</span> --}}
                            </div>

                            {{-- <div class="col-sm-4" hidden>
                                <label for="select_delegado">Delegado</label>
                                <div style="border-style: outset;">    
                                    <select class="form-control select2" name="select_delegado" id="select_delegado">
                                        @foreach ($delegados  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach  
                                    </select>
                                </div>                   
                            </div> --}}
                            <div class="col-sm-4" hidden>
                                <br />
                                    <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#modal_delegado">Crear Delegado</button>
                                    {{-- <button type="button" class="btn btn-dark dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> --}}

                            </div>
                                
       				</div>
                    <div class="row"  >        

                        <div class="col-sm-12 text-center">
                            <h2>PASO 2.- JUGADORES</h2>                                
							<p>Selecina los jugadores exitentes en la lista, tambien puedes agregar uno nuevo.</p>
                        </div>    
                            <div class="col-sm-4" hidden>
                                <label for="select_equipo">Equipo</label>
                                <div style="border-style: outset;">
                                    <select name="" id="select_equipo" class="form-control select2">
                                        @if(Auth::user()->role_id!=1)
                                            <option value="{{$club_unico->id}}">{{$club_unico->name}}</option>
                                        @else
                                            @foreach ($equipos  as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach   
                                        @endif
                                    </select>
                                </div>
                            </div>
                           
                            {{-- <div class="col-sm-6 text-center">
                               	<label>Lista de jugadores existentes.</label>
                                <div style="border-style: outset;">
                                        
                                   
                                </div>                                
                            </div> --}}
                                        
                            <div class="col-sm-12 text-center">
                                <div class="input-group">
                                    <label>Lista de jugadores existentes.</label>

                                    {{-- <button type="button" class="btn btn-dark">Acciones jugadores</button> --}}
                                    <div style="border-style: outset;">  

                                    <select name="" id="select_jugador" class="form-control select2">
                                        {{-- <option value="null">Jugadores</option> --}}

                                        @if(Auth::user()->role_id!=1)
                                            @foreach ($club_unico->jugadores  as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @else
                                            @foreach ($jugadores  as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    </div>
                                    <br>
                                    <span class="input-group-btn ">

                                        <button type="button" class="btn btn-dark dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a  onclick="add_todos()">Agregar Toda la Lista</a></li>
                                            <li><a  onclick="add_fila()">Agregar Jugador Individual</a></li>
                                            <li><a   data-toggle="modal" data-target="#modal_jugador"> Crear Jugador</a></li>
                                            <li><a   data-toggle="modal" data-target="#modal_transferencia"> Transferencia</a></li>	
                                        </ul>
                                    </span>
                                </div>
                            </div>
                          
                            <div  class="col-sm-12 table-responsive">
                                <table class="table table-striped table-bordered" id="table2">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            {{-- <th class="text-center" scope="col">Edad</th> --}}
                                            <th class="text-center" scope="col">Suplentes</th>
                                            <th class="text-center" scope="col">Polera</th>
                                            <th class="text-center" scope="col">Nombre</th>
                                            <th class="text-center" scope="col">Mensualidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>   {{-- close row 2 --}}
						<div class="row"> 
                        
                        	<div class="col-sm-12 text-center">
                        		<h2>TOTALES</h2>
                        		<p>totales en pagos u deudas para el equipo.</p>
                        	</div>
                            <div class="col-sm-3">
                                <label for="input_total">Monto Esperado</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_sub_total" name="input_sub_total" type="number">
                                </div>
                            </div>
            
                            <div class="col-sm-3">
                                <label for="input_deudas">Monto Adeudado</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_deudas" name="input_deudas" type="number">
                                </div>
                            </div>
                           
                            <div class="col-sm-3">
                                <label for="input_total">Total Pagado</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_total" name="input_total" type="number">
                                </div>
                            </div>     
                            <div class="col-sm-3">
                                <br />
                             	<button class="btn btn-dark btn-block" onclick="misave()">Guardar Formulario</button>
                            </div>   
                                
                        </div>                                                             


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->

    <!-- Modal Transferencia-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_transferencia">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container ">
                        <div class="col-sm-6">
                            <label for="equipo_transferencia">Elija el Equipo Actual del Jugador</label>
                            <div style="border-style: outset;">                                
                                <select class="form-control select2" name="equipo_transferencia" id="equipo_transferencia">
                                    @if (Auth::user()->role_id!=1)
                                        @foreach ($equipos_transferencia  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach   
                                    @else
                                        @foreach ($equipos  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach       
                                    @endif
                                
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="jugador_transferencia">Elija el Jugador</label>
                            <div style="border-style: outset;">                                
                                <select class="form-control select2" name="jugador_transferencia" id="jugador_transferencia">Jugador</select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="observacion_transferencia">Observaciones</label>
                            <div style="border-style: outset;">                                
                                <textarea class="form-control" name="observacion_transferencia" id="observacion_transferencia"  rows="3"></textarea>
                            </div>
                        </div>
                        {{-- <div hidden>
                            <input type="number" id="input_transferencia">
                        </div> --}}
                    </div>

                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <a onclick="save_transferencia()" type="button" class="btn btn-primary">Guardar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Delegado-->
    <div class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_delegado">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Delegado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container ">
                       
                        
                        <div class="col-sm-6">
                            <label for="delegado_creation">Nombre Delegado</label>
                            <div style="border-style: outset;">                                
                                <input type="text" class="form-control" id="delegado_creation" name="delegado_creation" placeholder="Introduzca el nombre del Delegado">
                            </div>
                        </div>
                       
                        {{-- <div hidden>
                            <input type="number" id="input_transferencia">
                        </div> --}}
                    </div>

                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <a type="button" onclick="save_delegado()" class="btn btn-primary">Guardar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Jugador-->
    <div class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_jugador">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Jugador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container ">
                        <div class="col-sm-6">
                            <label for="nombre_jugador_create">Nombre</label>
                            <div style="border-style: outset;">                                
                                <input class="form-control" id="nombre_jugador_create" name="nombre_jugador_create" type="text">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="polera_jugador_create"># Polera</label>
                            <div style="border-style: outset;">                                
                                <input class="form-control" id="polera_jugador_create" name="polera_jugador_create" type="number">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="edad_jugador_create">Edad</label>
                            <div style="border-style: outset;">                                
                                <input class="form-control" id="edad_jugador_create" name="edad_jugador_create" type="number">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="nacido_jugador_create">Fecha Nac.</label>
                            <div style="border-style: outset;">                                
                                <input class="form-control" id="nacido_jugador_create" name="nacido_jugador_create" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="wpp_jugador_create">WhatsApp</label>
                            <div style="border-style: outset;">                                
                                <input class="form-control" id="wpp_jugador_create" name="wpp_jugador_create" type="number">
                            </div>
                        </div>
                        
                        {{-- <div class="col-sm-6">
                            <label for="delegado_creation">Nombre Delegado</label>
                            <div style="border-style: outset;">                                
                                <input type="text" class="form-control" id="delegado_creation" name="delegado_creation" placeholder="Introduzca el nombre del Delegado">
                            </div>
                        </div> --}}
                        
                        {{-- <div hidden>
                            <input type="number" id="input_transferencia">
                        </div> --}}
                    </div>

                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <a type="button" onclick="save_jugador()" class="btn btn-primary">Guardar</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        function misave(){
            Swal.fire({
                title: 'Estas Seguro de Guardar?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                // location.href = "{{setting('admin.url')}}admin/jugadores-planillas"
                    $('.mireload').attr("hidden", false)
                    guardar_planilla()
                    // location.href = "/admin/jugadores-planillas"
                }
            })
        }
        function save_transferencia(){
            Swal.fire({
                title: 'Estas Seguro de Guardar la Transferencia?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                // location.href = "{{setting('admin.url')}}admin/jugadores-planillas"
                    $('.mireload').attr("hidden", false)
                    $("#modal_transferencia .close").click()
                    transferir_jugador()
                    // location.href = "/admin/jugadores-planillas"
                }
            })
        }
        function save_delegado(){
            Swal.fire({
                title: 'Estas Seguro de Guardar el Delegado?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                // location.href = "{{setting('admin.url')}}admin/jugadores-planillas"
                    $('.mireload').attr("hidden", false)
                    $("#modal_delegado .close").click()
                    crear_delegado()
                    // location.href = "/admin/jugadores-planillas"
                }
            })
        }
        function save_jugador(){
            Swal.fire({
                title: 'Estas Seguro de Crear el Jugador?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                // location.href = "{{setting('admin.url')}}admin/jugadores-planillas"
                    $('.mireload').attr("hidden", false)
                    $("#modal_jugador .close").click()
                    crear_jugador()
                    // location.href = "/admin/jugadores-planillas"
                }
            })
        }


        async function guardar_planilla(){
            var clube_id= $("#select_club").val()
            var categoria_jugadores= $("#select_cat").val()
            // var fecha_entrega=$("#input_fecha").val()
            // var hora_entrega=$("#input_hora").val()
            var veedor_id= $("#select_veedor").val()
            var delegado_id=$("#select_delegado").val()
            var deuda=$("#input_deudas").val()
            var total=$("#input_total").val()
            // var observacion=$("#text_area_deudas").val()
            var observacion=""
            var subtotal=$("#input_sub_total").val()

            var detalles={
                clube_id: clube_id,
                categoria_jugadores:categoria_jugadores,
                // fecha_entrega:fecha_entrega,
                // hora_entrega:hora_entrega,
                veedor_id:veedor_id,
                delegado_id:delegado_id,
                deuda:deuda,
                total:total,
                observacion:observacion,
                subtotal:subtotal,
                men_pagadas: total
            }
          

            var planilla= await axios.post("/api/jugadores/planilla/save", detalles)
            
            await generar_nomina(planilla.data.id)
            // await generar_nomina(1)

          
        
        }

        function comparar_exis_jug(id){
            var validador=false;
            $('.tab_jugs_id').each(function(){
                if (id==this.value) {
                    validador=true
                }
            })
            // $('.tab_jugs_sups_id').each(function(){
            //     if (id==this.value) {
            //         validador=true
            //     }
            // })
            return validador;
        }

        async function generar_nomina(planilla_id){
            var jugs=[];
            // var jugs_sups=[];
            var jugs_id=[];
            // var jugs_sups_id=[];
            var titular=0


            var table2 = document.getElementById("table2");

            var index=0;
            $('.tab_jugs_id').each(function(){
                jugs_id[index]=this.value
                index+=1
            })

            // console.log(jugs_tits_id)

            for (var i = 1, row; row = table2.rows[i]; i++) {
                // console.log("Numero: "+row.cells[0].innerText+" Edad: "+row.cells[1].innerText+" Polera: "+row.cells[2].innerText+" N y A: "+ row.cells[3].innerText+ " Mensualidad: "+row.cells[4].innerText)

                var mensualidad= 0
                if (row.cells[4].innerText!="") {
                    mensualidad= parseInt(row.cells[4].innerText)
                }
                else{
                    mensualidad= 0
                }

                if ($("#check_"+parseInt(jugs_id[(i-1)])+"").prop('checked')) {
                    titular=2
                }
                else{
                    titular=1
                }

                var midata={
                    planilla_id:planilla_id,
                    jugador_id: parseInt(jugs_id[(i-1)]),
                    titular: titular,
                    mensualidad: mensualidad
                }
                await axios.post("/api/jugadores/rel/planilla/jugs/save", midata)
                var monto_dinero= parseInt("{{setting('finanzas.mensualidad_jug')}}")
                if (mensualidad<monto_dinero) {
                    var data={
                        tipo: "Ingreso",
                        detalle:"Mensualidades",
                        monto: (monto_dinero-mensualidad),
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        clube_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_id[(i-1)]),
                        observacion: "Debe Mensualidad",
                        estado: "Pendiente",
                        monto_pagado: 0,
                        monto_restante:(monto_dinero-mensualidad)
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data)

                }
                else{
                    var data={
                        tipo: "Ingreso",
                        detalle:"Mensualidades",
                        monto: monto_dinero,
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        clube_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_id[(i-1)]),
                        observacion: "Pagó Total Mensualidad",
                        estado: "Pagado",
                        monto_pagado: monto_dinero,
                        monto_restante:0
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data)

                }
               console.log(midata)
            
            }

            // var table3 = document.getElementById("table3");

            // var index2=0;
            // $('.tab_jugs_sups_id').each(function(){
            //     jugs_sups_id[index2]=this.value
            //     index2+=1
            // })

            // // console.log(jugs_sups_id)

            // for (var i = 1, row; row = table3.rows[i]; i++) {
            //     var mensualidad= 0
            //     if (row.cells[4].innerText!="") {
            //         mensualidad= parseInt(row.cells[4].innerText)
            //     }
            //     else{
            //         mensualidad= 0
            //     }
            //     var midata2={
            //         planilla_id:planilla_id,
            //         jugador_id: parseInt(jugs_sups_id[(i-1)]),
            //         titular: 2,
            //         mensualidad: mensualidad
            //     }
            //     await axios.post("/api/jugadores/rel/planilla/jugs/save", midata2)
            //     if (mensualidad<1400) {
            //         var data2={
            //             tipo: "Ingreso",
            //             detalle:"Mensualidades",
            //             monto: (1400-mensualidad),
            //             editor_id:parseInt("{{Auth::user()->id}}"),
            //             planilla_id: planilla_id,
            //             clube_id: $("#select_club").val(),
            //             jugador_id: parseInt(jugs_sups_id[(i-1)]),
            //             observacion: "Debe Mensualidad",
            //             estado: "Pendiente",
            //             monto_pagado: 0,
            //             monto_restante:(1400-mensualidad)
            //         }
            //         var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data2)

            //     }
            //     else{
            //         var data2={
            //             tipo: "Ingreso",
            //             detalle:"Mensualidades",
            //             monto: 1400,
            //             editor_id:parseInt("{{Auth::user()->id}}"),
            //             planilla_id: planilla_id,
            //             clube_id: $("#select_club").val(),
            //             jugador_id: parseInt(jugs_sups_id[(i-1)]),
            //             observacion: "Pagó Total Mensualidad",
            //             estado: "Pagado",
            //             monto_pagado: 1400,
            //             monto_restante:0
            //         }
            //         var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data2)

            //     }
            //     console.log(midata2)
            // }

            // var msj_chatbot={
            //     msj: "Hola que tal, prueba desde Roman",
            //     telefono:"59171130523"
            // }
            // await axios.post("{{setting('admin.url')}}api/send/message",msj_chatbot )
          
            location.href="{{setting('admin.url')}}admin/jugadores-planillas"
        }

        async function add_todos(){
            var equipo_id=$("#select_equipo").val()
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            
            for (let index = 0; index < jugadores.data.length; index++) {
                var jugador= await axios("/api/jugadores/find/"+jugadores.data[index].id)
                await add_jugador(jugador, (index+1))
            }   
            toastr.success("Planilla a Completa Registrada")
        }
        async function add_fila(){
            var jugador_id=0
            // if (tipo==1) {
                jugador_id= $("#select_jugador").val()
            // }
            // else{
            //     jugador_id= $("#select_jugador_sup").val()
            //     // console.log("Jugador: "+jugador_id)
            // }

            if (jugador_id !="null") {

                var jugador= await axios("/api/jugadores/find/"+jugador_id )
                
                var cont= count_jugs()
                // if (tipo==2) {
                //     cont=cont+ count_jugs(2)
                // }
                var num=cont+1
                if (cont==0) {
                    // console.log("Hola1")
                    // console.log("Select"+$("#select_club").val())
                    // console.log(jugador.data.club_id)
                    if (jugador.data.clube_id!= $("#select_club").val()) {
                        if ($("#select_club").val()=="null") {
                            toastr.error("Selecciona el Club en la parte Superior del cual crearás la plantilla.")
                        }
                        else{
                            toastr.error("El Jugador que intenta ingresar pertenece a otro equipo, realice la transferencia si desea utilizarlo.")
                        }
                    }
                    else{
                        await add_jugador(jugador, num)
                      
                    }
                }else{
                    // console.log("Hola2")

                    var clube_id=club_jugs()

                    // console.log("Club "+club_id)
                    // console.log("Jugador Equipo "+jugador.data.club_id)

                    if (jugador.data.clube_id!=clube_id ) {
                        toastr.error("El Jugador que intenta ingresar pertenece a otro equipo, realice la transferencia si desea utilizarlo.")
                    }
                    else{
                        await add_jugador(jugador, num)
                    }
                }
            }
            else{
                toastr.error("Seleccione Jugador para Agregarlo")
            }
          
            
           
            
        }

        async function add_jugador(jugador, cont){
            if(comparar_exis_jug(jugador.data.id)){
                toastr.error("El Jugador: "+jugador.data.name+" ya se encuentra en la nómina")
            }
            else{
                // if (tipo==1) {
                    // $('#table2').append("<tr><td><input class='tab_jugs_tits_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs' type='number' value="+jugador.data.clube_id+" hidden>"+cont+"</td><td class='tab_jugs_tits'>"+jugador.data.edad+"</td><td>  "+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_tit'>1400</td></tr>");
                    $('#table2').append("<tr><td><input class='tab_jugs_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs' type='number' value="+jugador.data.clube_id+" hidden>"+cont+"</td><td class='tab_jugs'><input id='check_"+jugador.data.id+"' type='checkbox'></td><td>  "+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_tit'>"+parseInt("{{setting('finanzas.mensualidad_jug')}}")+"</td></tr>");

                    example2.init();
                // }
                // else{
                //     $('#table3').append("<tr><td><input class='tab_jugs_sups_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs_sups' type='number' value="+jugador.data.clube_id+" hidden>"+cont+"</td><td class='tab_jugs_sups'>"+jugador.data.edad+"</td><td>"+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_sup'>1400</td></tr>");
                //     example3.init();
                // }
                total_mensualidades()
            }
           
            
        }

        // function add_deudas() {
        //     $('#table4').append("<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>")
        //     example4.init()
        // }

        function total_mensualidades() {
            var tits= count_jugs()
            // var sups =count_jugs(2)
            // var cuotas= parseInt(tits) + parseInt(sups)
            var cuotas= parseInt(tits)
            var sub_total_tits= 0
            var sub_total_sups=0
            var subtotal=0
            // $('.mensualidad_table_tit').each(function (){
            //     sub_total_tits+= parseInt(this.value)
            //     console.log(this.value)
            // })
            //$('.mensualidad_table_sup').each(function (){
            //     sub_total_sups+=parseInt(this.value)
            // })

            for (var i = 1, row; row = table2.rows[i]; i++) {
                sub_total_tits+= parseInt(row.cells[4].innerText)
            }
            // for (var i = 1, row; row = table3.rows[i]; i++) {
            //     sub_total_sups+= parseInt(row.cells[4].innerText)
            // }




            subtotal= (tits)*parseInt("{{setting('finanzas.mensualidad_jug')}}")

                $("#input_sub_total").val(subtotal)
                $("#input_total").val((sub_total_sups+sub_total_tits))
            var monto_adeudado= subtotal-(sub_total_tits+sub_total_sups)
                $("#input_deudas").val(monto_adeudado)

        }

        function count_jugs(){
            var num=0
            // if (tipo==1) {
                $('.tab_jugs').each(function(){
                    num+=1
                })
            // }
            // else{
            //     $('.tab_jugs_sups').each(function(){
            //         num+=1
            //     })
            // }
           
            // console.log(num)
            return num;
        }

        function club_jugs(){
            var clube_id=0

            // if (tipo==1) {
            //     $('.tab_club_jugs').each(function(){
            //         clube_id=this.value
            //     })
            // }
            // else{
                clube_id=$("#select_club").val()
            // }
           
            // console.log(num)
            return clube_id;
        }
        //Setear Input de Tipo de Jugador en modal
        // function set_input_tipo_jugador(tipo) {
        //     $("#input_transferencia").val(tipo)
        // }

        async function transferir_jugador() {
            var midata={
                'jugadore_id':$("#jugador_transferencia").val(),
                'clube_id_origen':$("#equipo_transferencia").val(),
                'clube_id_destino':$("#select_club").val(),
                'observacion':$("#observacion_transferencia").val()
            }
            var transferencia= await axios.post("/api/transferencia/jugador", midata)
            if (transferencia.data!=null) {
                $('.mireload').attr("hidden", true)
                var cont= count_jugs()
                // if ($("#input_transferencia").val()==2) {
                //     cont=cont+ count_jugs(2)
                // }
                var jugador= await axios("/api/jugadores/find/"+$("#jugador_transferencia").val() )
                await add_jugador(jugador, (cont+1))
                toastr.success("Jugador Transferido Exitosamente")

            }
        }

        async function crear_delegado() {
            var midata={
                clube_id:$("#select_club").val(),
                name:$("#delegado_creation").val()
            }
            var delegado= await axios.post("/api/create/delegado", midata)
            if (delegado.data!=null) {
                $('.mireload').attr("hidden", true)
                toastr.success("Delegado Creado Exitosamente")
                await cargar_delegados()
                console.log($('#select_delegado').val())
                console.log($('#select_delegado option:selected').text())
            }
        }

        async function crear_jugador() {
            var midata={
                'name':$("#nombre_jugador_create").val(),
                'polera':$("#polera_jugador_create").val(),
                'edad':$("#edad_jugador_create").val(),
                'nacido':$("#nacido_jugador_create").val(),
                // 'jug_categoria':$("#").val(),
                'clube_id':$("#select_club").val(),
                // 'color_carnet':$("#").val(),
                'phone':$("#wpp_jugador_create").val()
            }
            var jugador= await axios.post("/api/create/jugador", midata)
            if (jugador.data!=null) {
                $('.mireload').attr("hidden", true)
                var cont= count_jugs()
                // if ($("#input_transferencia").val()==2) {
                //     cont=cont+ count_jugs(2)
                // }
                await add_jugador(jugador, (cont+1))
                toastr.success("Jugador Creado Exitosamente")
            }
        }

        var example2 = new BSTable("table2", {
			editableColumns:"2,4",
			// $addButton: $('#table2-new-row-button'),
			onEdit:function() {
				console.log("EDITED");
                total_mensualidades()
			},
			advanced: {
				columnLabel: ''
			}
		});
        // var example3 = new BSTable("table3", {
		// 	editableColumns:"2,4",
		// 	// $addButton: $('#table2-new-row-button'),
		// 	onEdit:function() {
        //         total_mensualidades()
		// 		console.log("EDITED");
		// 	},
		// 	advanced: {
		// 		columnLabel: ''
		// 	}
		// });

        // var example4 = new BSTable("table4", {
		// 	editableColumns:"1,2,3,4,5,6,7,8,9",
		// 	// $addButton: $('#table2-new-row-button'),
		// 	onEdit:function() {
		// 		console.log("EDITED");
		// 	},
		// 	advanced: {
		// 		columnLabel: ''
		// 	}
		// });

        $("#select_equipo").change(async function () { 
            $('#select_jugador').find('option').remove().end()
            var equipo_id=this.value
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            for (let index = 0; index < jugadores.data.length; index++) {
                $('#select_jugador').append($('<option>', {
                    value: jugadores.data[index].id,
                    text: jugadores.data[index].name
                }));
            }                                            
        })
        $("#select_equipo_sup").change(async function () { 

            $('#select_jugador_sup').find('option').remove().end()
            var equipo_id=this.value
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            for (let index = 0; index < jugadores.data.length; index++) {
                $('#select_jugador_sup').append($('<option>', {
                    value: jugadores.data[index].id,
                    text: jugadores.data[index].name
                }));
            }

        })
        //select jugadores Modal Transferencia
        $("#equipo_transferencia").change(async function () { 
            $('#jugador_transferencia').find('option').remove().end()
            var equipo_id=this.value
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            for (let index = 0; index < jugadores.data.length; index++) {
                $('#jugador_transferencia').append($('<option>', {
                    value: jugadores.data[index].id,
                    text: jugadores.data[index].name
                }));
            }                                            
        })

        //Select Delegado
        async function cargar_delegados() {
            $('#select_delegado').find('option').remove().end()
            var delegados= await axios("/api/all/delegados")
            for (let index = 0; index < delegados.data.length; index++) {
                $('#select_delegado').append($('<option>', {
                    value: delegados.data[index].id,
                    text: delegados.data[index].name
                }));
            }         
        }

        //Validar Si es Admin, Manager o Club
        async function validad_roles() {
            //Si es Club
            if ("{{Auth::user()->role_id}}"==3) {
                
            }
            else{//Si es Admin o Manager
                $("#club_div").attr("hidden", false)
            }
        }
        

        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(async function () {
            //Inicializar Select Jugadores Transferencia
            validad_roles()
            $('#jugador_transferencia').find('option').remove().end()
            var equipo_id=$("#equipo_transferencia").val()
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            for (let index = 0; index < jugadores.data.length; index++) {
                $('#jugador_transferencia').append($('<option>', {
                    value: jugadores.data[index].id,
                    text: jugadores.data[index].name
                }));
            }             


          // example2.init();
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
            // add_deudas()
        });

        // Example POST method implementation:
        async function prueba() {
            var myData = {
                "data": {
                    "number": "59170269362", 
                    "whatsappId":17, 
                    "body":"Prueba Javascript Final"
                }
            };

            $.ajax({

                url: 'https://api.appxi.net/api/messages/send',
                type: 'POST',
                crossDomain: true,
                data: myData,
                datatype: 'jsonp',
                success: function() { alert("Success"); },
                error: function() { alert('Failed!'); },
                beforeSend: setHeader
               
            });
        }

        function setHeader(xhr) {
            xhr.setRequestHeader('Authorization',  'Bearer d13aaed2-3751-46c0-8934-7f7bc00709f2');
        }

    </script>
@stop

@section('mijs')
    
@stop
