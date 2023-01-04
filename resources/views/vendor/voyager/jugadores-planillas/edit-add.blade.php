@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    
    $equipos= App\Clube::all();
    $ultimo_equipo= end($equipos);
    $jugadores= App\Jugadore::where('clube_id', $equipos[$ultimo_equipo]->id)->get();
    $delegados= App\Delegado::where('clube_id', $equipos[$ultimo_equipo]->id)->get();
    $club_unico=false;
    $equipos_transferencia=false;
    if (Auth::user()->role_id==3) {
        $club_unico=App\Clube::where('user_id', Auth::user()->id)->with('jugadores')->first();
        $equipos_transferencia=App\Clube::where('user_id','!=',Auth::user()->id)->get();
        $delegados_club=App\Delegado::where('clube_id', $club_unico->id)->get();
    }
    $date= date("Y-m");
    
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
        {{-- <div class="row">
            <div class="col-sm-12"> --}}
                <div class="panel panel-bordered">
                    <a class="btn btn-dark" onclick="probar_mensaje_whatsapp()"> Prueba Formato Wpp</a>

                    <div class="row">        
                            <div class="col-sm-12 text-center">
                            	<h2>PASO 1.- DETALLES DEL CLUB</h2>
                            	{{-- <p>Formulario para el registro de nominas de jugadores del club o equipo: {{ $club_unico->name }}</p> --}}
                            </div>

                            <div class="col-sm-4">
                                <label for="fecha">Gestión</label>
                                {{-- <div style="border-style: outset;">     --}}

                                <input type="month" name="fecha_mensual" id="fecha_mensual" class="form-control" value="{{$date}}">
                                {{-- </div> --}}
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
                                        @if(Auth::user()->role_id==3)
                                            @foreach ($delegados_club  as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach  
                                        @else
                                            @foreach ($delegados  as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach  
                                        @endif
                                    </select>
                                    </div>
                                    <br>
                                    <span class="input-group-btn">
                                        <a  class="btn  btn-dark" data-toggle="modal" data-target="#modal_delegado" ><span>Crear </span>  <i class="voyager-plus"></i>  </a>    
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
                                    @if(Auth::user()->role_id==3)
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
                        <div class="col-sm-6 text-center" id="club_div" hidden>
                            <label for="select_club">Club</label>
                            <div style="border-style: outset;">                                
                                <select class="form-control select2" name="select_club" id="select_club">
                                    @if(Auth::user()->role_id==3)
                                        <option value="{{$club_unico->id}}">{{$club_unico->name}}</option>
                                    @else
                                        @foreach ($equipos  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach   
                                    @endif
                                    
                                </select>
                            </div>
                        </div>
                                        
                        <div class="col-sm-6 text-center">
                            <div class="input-group">
                                <label>Lista de jugadores existentes.</label>

                                {{-- <button type="button" class="btn btn-dark">Acciones jugadores</button> --}}
                                <div style="border-style: outset;">  

                                <select name="" id="select_jugador" class="form-control select2">
                                    {{-- <option value="null">Jugadores</option> --}}

                                    @if(Auth::user()->role_id==3)
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
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a  onclick="add_todos()">Agregar Toda la Lista</a></li>
                                        <li><a  onclick="add_fila()">Agregar Jugador Individual</a></li>
                                        <li><a   data-toggle="modal" data-target="#modal_jugador"> Crear Jugador</a></li>
                                        <li><a   data-toggle="modal" data-target="#modal_transferencia"> Transferencia</a></li>	
                                    </ul>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div  class=" table-responsive">
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
                        </div>
                        

                    </div>   {{-- close row 2 --}}
					<div class="row"> 
                        
                        	<div class="col-sm-12 text-center">
                        		<h2>PASO 3.- TOTALES</h2>
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
            {{-- </div>
        </div> --}}
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
                                    @if (Auth::user()->role_id==3)
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
                    
                        <div class="form-group col-6">
                            <label for="delegado_creation">Nombre Delegado</label>
                            <div style="border-style: outset;">                                
                                <input type="text" class="form-control" id="delegado_creation" name="delegado_creation" placeholder="Introduzca el nombre del Delegado">
                            </div>
                        </div>

                         <div class="form-group col-6">
                            <label for=""># Whatsapp</label>
                            <div style="border-style: outset;">                                
                                <input type="number" class="form-control" id="wpp_delegado" name="wpp_delegado" placeholder="Ingrese su WhatsApp">
                            </div>
                        </div>
                            
                </div>
                <div class="modal-footer mt-3">
                    <a type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <a type="button" onclick="save_delegado()" class="btn btn-dark">Guardar</a>
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

        async function probar_mensaje_whatsapp() {
            var fecha= new Date()
            var mitext= ""
                mitext+="--------------- *Planilla de Jugadores* ---------------\n--------------- *Creada Exitosamente* ---------------\n\n"
                mitext+="Fecha: 10-01-2023"+fecha
                mitext+="*Jugador (Titular)*:\n"
                mitext+="- Juan Carlos Perez Suarez\n"
                mitext+="*Camiseta*:\n"
                mitext+="- Nª21\n"
                mitext+="*Mensualidad (1400Bs)*:\n"
                mitext+="- Pago Registrado con un monto de: 1200Bs\n"
                mitext+="- Saldo Deudor: 200Bs\n\n"
                mitext+="Nota.- Se debe realizar el pago del saldo deudor lo mas antes posible porfavor, ya que en caso de que en su nómina hayan 5 jugadores deudores no podrán disputar el siguiente partido."

            var midata={
                    phone: '70269362',
                    message: mitext
                }
                // console.log("1 "+midata)
                await axios.post("/api/whaticket/send", midata)
        }

        async function notificacion_planilla_creada() {
             var mitext= ""
                mitext+="--------------- *Planilla de Jugadores* ---------------\n--------------- *Creada Exitosamente* ---------------\n\n"
                mitext+="Fecha: 10-01-2023\n"
                mitext+="*Titulares*:\n"
                mitext+="1.- Juan Carlos Perez Suarez\n"
                mitext+="2.- Pedro Manuel Hurtado Monasterio\n"
                mitext+="3.- Ramon de la Fuente Martinez\n"
                mitext+="4.- Juan Carlos Perez Suarez\n"
                mitext+="5.- Nanario Gonzales Bere Paco\n"
                mitext+="6.- Pedro Manuel Hurtado Monasterio\n"
                mitext+="7.- Ramon de la Fuente Martinez\n"
                mitext+="8.- Ramon de la Fuente Martinez\n"
                mitext+="9.- Juan Carlos Perez Suarez\n"
                mitext+="10.- Ramon de la Fuente Martinez\n"
                mitext+="11.- Ramon de la Fuente Martinez\n\n"
                mitext+="*Suplentes*:\n"
                mitext+="12.- Ramon de la Fuente Martinez\n"
                mitext+="13.- Pedro Manuel Hurtado Monasterio\n"
                mitext+="14.- Pedro Manuel Hurtado Monasterio\n"
                mitext+="15.- Pedro Manuel Hurtado Monasterio\n\n"
                mitext+="Se enviará un mensaje cuando se tome una decisión respecto a esta planilla.\n\n"
                mitext+="Puede Verificar el Estado de la misma en: "+"https://ligadefutbol.loginweb.dev/admin/admin/jugadores-planillas/1\n"



            var midata={
                    phone: '70269362',
                    message: mitext
                }
                // console.log("1 "+midata)
                await axios.post("/api/whaticket/send", midata)
        }

        async function notificacion_asientos_jugadores(fecha, titularidad, nombre, camiseta, men_pagada, phone) {
            var deuda=parseFloat("{{setting('finanzas.mensualidad_jug')}}").toFixed(2) -parseFloat(men_pagada).toFixed(2)
            var mitext= ""
                mitext+="--------------- *Planilla de Jugadores* ---------------\n--------------- *Creada Exitosamente* ---------------\n\n"
                mitext+="Fecha: "+fecha+"\n\n"
                mitext+="*Jugador ("+titularidad+")*:\n"
                mitext+=""+nombre+"\n"
                mitext+="*Camiseta*:\n"
                mitext+="- Nª"+camiseta+"\n"
                mitext+="*Mensualidad ({{setting('finanzas.mensualidad_jug')}}Bs)*:\n"
                mitext+="- Pago Registrado con un monto de: "+men_pagada+"Bs\n"
                mitext+="- Saldo Deudor: "+deuda+"Bs\n\n"
                mitext+="Nota.- Se debe realizar el pago del saldo deudor lo mas antes posible porfavor, ya que en caso de que en su nómina hayan 5 jugadores deudores no podrán disputar el siguiente partido."
            var midata={
                    phone: phone.toString(),
                    message: mitext
                }
                // console.log("1 "+midata)
                await axios.post("/api/whaticket/send", midata)
        }


        async function guardar_planilla(){
            var clube_id= $("#select_club").val()
            var categoria_jugadores= $("#select_cat").val()
            var fecha_entrega=$("#fecha_mensual").val()+"-01"
            // var hora_entrega=$("#input_hora").val()
            // var veedor_id= $("#select_veedor").val()
            var delegado_id=$("#select_delegado").val()
            var deuda=$("#input_deudas").val()
            var total=$("#input_total").val()
            // var observacion=$("#text_area_deudas").val()
            var observacion=""
            var subtotal=$("#input_sub_total").val()

            var detalles={
                clube_id: clube_id,
                categoria_jugadores:categoria_jugadores,
                fecha_entrega:fecha_entrega,
                // hora_entrega:hora_entrega,
                // veedor_id:veedor_id,
                delegado_id:delegado_id,
                deuda:deuda,
                total:total,
                observacion:observacion,
                subtotal:subtotal,
                men_pagadas: total,
                user_id: parseInt("{{Auth::user()->id}}")
            }
            console.log(fecha_entrega)

            var planilla= await axios.post("/api/jugadores/planilla/save", detalles)
            console.log(planilla.data)
            var phone_club=(planilla.data.clubes.wpp).toString()
            var phone_delegado=(planilla.data.delegado.phone).toString()
           
            await generar_nomina(planilla.data.id, phone_club, phone_delegado, planilla.data.fecha)

          
        
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

        async function generar_nomina(planilla_id, phone_club, phone_delegado, fecha){
            var jugs=[];
            // var jugs_sups=[];
            var jugs_id=[];
            var jugs_phone=[];
            // var jugs_sups_id=[];
            var titular=0
            var cant_jugs_deudas=0

            var table2 = document.getElementById("table2");

            var index=0;
            $('.tab_jugs_id').each(function(){
                jugs_id[index]=this.value
                index+=1
            })
            var index2=0
            $('.tab_jugs_phone').each(function() {
                jugs_phone[index2]=this.value
                index2+=1
            })

            //MENSAJE PLANILLA
            //Cabecera
            var mitext= ""
                mitext+="--------------- *Planilla de Jugadores* ---------------\n--------------- *Creada Exitosamente* ---------------\n\n"
                mitext+="Fecha: "+fecha+"\n"
                mitext+="*Titulares*:\n"

            //Cuerpo
            var wpp_titulares=""
            var wpp_index_tits=0
            var wpp_suplentes=""
            var wpp_index_sups=0
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
                    //Cuerpo Suplentes
                    wpp_index_sups+=1
                    wpp_suplentes+=wpp_index_sups+".- "+row.cells[3].innerText+"\n"

                    //var jugador= await axios("/api/jugadores/find/"+parseInt(jugs_id[(i-1)]))
                    //Mensaje Asientos Suplentes
                    //console.log(jugador.data)
                    await notificacion_asientos_jugadores(fecha, "Suplente", "- "+row.cells[3].innerText, "- "+row.cells[2].innerText, row.cells[4].innerText, jugs_phone[(i-1)])
                }
                else{
                    titular=1
                    //Cuerpo Titulares
                    wpp_index_tits+=1
                    wpp_titulares+=wpp_index_tits+".- "+row.cells[3].innerText+"\n"

                    //Mensaje Asientos Titulares
                    //var jugador= await axios("/api/jugadores/find/"+parseInt(jugs_id[(i-1)]))
                    //console.log(jugador.data)
                    await notificacion_asientos_jugadores(fecha, "Titular", "- "+row.cells[3].innerText, "- "+row.cells[2].innerText, row.cells[4].innerText, jugs_phone[(i-1)])

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
                    cant_jugs_deudas+=1
                    var data={
                        tipo: "Ingreso",
                        cat_asiento_id:1,
                        monto: monto_dinero,
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        clube_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_id[(i-1)]),
                        observacion: "Debe Mensualidad",
                        estado: "Pendiente",
                        monto_pagado: mensualidad,
                        monto_restante:(monto_dinero-mensualidad)
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data)

                }
                else{
                    var data={
                        tipo: "Ingreso",
                        cat_asiento_id:1,
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
            //Armando el cuerpo
            mitext+=wpp_titulares
            mitext+="\n*Suplentes*:\n"
            mitext+=wpp_suplentes
            mitext+="\nSe enviará un mensaje cuando se tome una decisión respecto a esta planilla.\n\n"
            //Condicional de repetidos
            if (phone_club==phone_delegado) {
                mitext+="Puede Verificar el Estado de la misma en: "+"{{setting('admin.url')}}/admin/jugadores-planillas/"+planilla_id
                var midata={
                    phone: phone_club,
                    message: mitext
                }
                await axios.post("/api/whaticket/send", midata)
            }
            else{
                var midata={
                    phone: phone_club,
                    message: mitext
                }
                await axios.post("/api/whaticket/send", midata)
                mitext+="Puede Verificar el Estado de la misma en: "+"{{setting('admin.url')}}/admin/jugadores-planillas/"+planilla_id
                var midata2={
                    phone: phone_delegado,
                    message: mitext
                }
                await axios.post("/api/whaticket/send", midata2)
            }



            var midata={
                cant_jugs_deudores:cant_jugs_deudas,
                planilla_id: planilla_id
            }
            await axios.post("/api/update/cant/jugs/deudores", midata)
            
            location.href="{{setting('admin.url')}}admin/jugadores-planillas/"+planilla_id
        }

        async function add_todos(){
            var equipo_id=$("#select_club").val()
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            // var cont= count_jugs()+1

            for (let index = 0; index < jugadores.data.length; index++) {
                var jugador= await axios("/api/jugadores/find/"+jugadores.data[index].id)
                await add_jugador(jugador)
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
                        // await add_jugador(jugador, num)
                        await add_jugador(jugador)

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
                        // await add_jugador(jugador, num)
                        await add_jugador(jugador)

                    }
                }
            }
            else{
                toastr.error("Seleccione Jugador para Agregarlo")
            }
          
            
           
            
        }

        async function add_jugador(jugador){
            if(comparar_exis_jug(jugador.data.id)){
                toastr.error("El Jugador: "+jugador.data.name+" ya se encuentra en la nómina")
            }
            else{
                var cont=count_jugs()+1
                // if (tipo==1) {
                    // $('#table2').append("<tr><td><input class='tab_jugs_tits_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs' type='number' value="+jugador.data.clube_id+" hidden>"+cont+"</td><td class='tab_jugs_tits'>"+jugador.data.edad+"</td><td>  "+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_tit'>1400</td></tr>");
                    $('#table2').append("<tr><td><input class='tab_jugs_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs' type='number' value="+jugador.data.clube_id+" hidden><input class='tab_jugs_phone' type='number' value="+jugador.data.phone+" hidden>"+cont+"</td><td class='tab_jugs'><input id='check_"+jugador.data.id+"' type='checkbox'></td><td>  "+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_tit'>"+parseInt("{{setting('finanzas.mensualidad_jug')}}")+"</td></tr>");

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
                // await add_jugador(jugador, (cont+1))
                await add_jugador(jugador)
                toastr.success("Jugador Transferido Exitosamente")

            }
        }

        async function crear_delegado() {
            var midata={
                clube_id:$("#select_club").val(),
                name:$("#delegado_creation").val(),
                phone: $("#wpp_delegado").val()
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
                // await add_jugador(jugador, (cont+1))
                await add_jugador(jugador)
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
            onDelete:function(){
                console.log("DELETED");
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

        $("#select_club").change(async function () { 
            $('#select_jugador').find('option').remove().end()
            var equipo_id=this.value
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            for (let index = 0; index < jugadores.data.length; index++) {
                $('#select_jugador').append($('<option>', {
                    value: jugadores.data[index].id,
                    text: jugadores.data[index].name
                }));
            }
            var delegados= await axios("/api/delegados/find/club/"+equipo_id)
            console.log(delegados.data)
            $('#select_delegado').find('option').remove().end()
            for (let index = 0; index < delegados.data.length; index++) {
                $('#select_delegado').append($('<option>', {
                    value: delegados.data[index].id,
                    text: delegados.data[index].name
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
            var equipo_id= $("#select_club").val()
            var delegados= await axios("/api/delegados/find/club/"+equipo_id)
            // var delegados= await axios("/api/all/delegados")
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
