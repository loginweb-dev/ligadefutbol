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
    <div class="container-fluid">
      
        <div class="col-sm-4" hidden>
            <br />
            <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#modal_delegado">Crear Delegado</button>
        </div>

        <br>
        <div class="row"  >        
  
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
                
            <div class="col-sm-4">


                <div class="form-group" id="club_div" hidden>
                    <label for="select_club">Club</label>
                    <div class="miselect">                                
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

                <label for="select_cat">Categoria</label>
                <div class="form-group miselect">                    
                    <select class="form-control select2" name="select_cat" id="select_cat">
                        <option value="Senior">Senior</option>
                        <option value="Especial">Especial</option>
                    </select>
                </div>
                
   
                <label>Lista de jugadores existentes.</label>
                <div class="form-group miselect">  
                    <select name="" id="select_jugador" class="form-control select2">
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
                    
                <div class="form-group">
                    <label for="fecha">Gestión</label>
                    <input type="month" name="gestion" id="gestion" class="form-control" value="{{ $date }}">
                </div>

                <label>Delegado</label>                  
                <div class="miselect">
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
                <div class="form-group">  
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-dark btn-block dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a  href="#" onclick="add_todos()">Agregar Toda la Lista</a></li>
                            <li><a  href="#" onclick="add_fila()">Agregar Jugador Individual</a></li>
                            <li><a  href="#" data-toggle="modal" data-target="#modal_jugador"> Crear Jugador</a></li>
                            <li><a  href="#" data-toggle="modal" data-target="#modal_transferencia"> Transferencia</a></li>	
                            <li><a  href="#" data-toggle="modal" data-target="#modal_delegado">Crear Delegado</a></li>	
                            <li><a  href="#" onclick="return location.reload()">Limpiar Lista</a></li>	
                        </ul>
                    </span>
                </div>

                <div class="form-group">
                    <label for="input_total">Monto Esperado</label>
                        <input class="form-control" id="input_sub_total" name="input_sub_total" type="number" readonly>
                </div>

                <div class="form-group">
                    <label for="input_deudas">Monto Adeudado</label>
                        <input class="form-control" id="input_deudas" name="input_deudas" type="number" readonly>
                </div>
                
                <div class="form-group">
                    <label for="input_total">Total Pagado</label>
                        <input class="form-control" id="input_total" name="input_total" type="number" readonly>
                </div>     
                <div class="form-group">
                   
                    <button id="miboton1" class="btn btn-primary btn-block" onclick="validacion_cantidad_jugs()">Guardar Formulario</button>
                </div>   

            </div>

            <div class="col-sm-8">
                <label for="">Lista de Jugadores</label>
                <div  class="table-responsive">
                    <table class="table mitable" id="table2">
                        <thead>
                            <tr class="active">
                                <th class="" scope="col">ID</th>
                                <th class="" scope="col">S/T</th>
                                <th class="" scope="col">Polera</th>
                                <th class="" scope="col">Nombre</th>
                                <th class="" scope="col">Mensualidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <p>Titular (T)= <input type="checkbox" name="" id="" disabled> / Suplentes (S)= <input type="checkbox" name="" id="" checked disabled></p>
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

    <!-- Modal Transferencia-->
    <div class="modal fade modal-primary"id="modal_transferencia">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Transferencias
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
                                                         
                                <textarea class="form-control" name="observacion_transferencia" id="observacion_transferencia"  rows="3"></textarea>
                            
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a onclick="validacion_realizar_transferencia()" type="button" class="btn btn-sm btn-dark">Guardar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Delegado-->
    <div class="modal fade modal-primary" id="modal_delegado">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    Crear delegado
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <div class="form-group col-6">
                            <label for="delegado_creation">Nombre Delegado</label>                                            
                            <input type="text" class="form-control" id="delegado_creation" name="delegado_creation" placeholder="Introduzca el nombre del Delegado">                         
                        </div>

                         <div class="form-group col-6">
                            <label for=""># Whatsapp</label>                                                       
                            <input type="number" class="form-control" id="wpp_delegado" name="wpp_delegado" placeholder="Ingrese su WhatsApp">                           
                        </div>    
                </div>
                <div class="modal-footer mt-3">
                    <a type="button" onclick="validacion_crear_delegado()" class="btn btn-sm btn-dark">Guardar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Jugador-->
    <div class="modal fade modal-primary" id="modal_jugador">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Crear Jugador
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="col-sm-6">
                            <label for="nombre_jugador_create">Nombre</label>                                                           
                                <input class="form-control" id="nombre_jugador_create" name="nombre_jugador_create" type="text">                         
                        </div>
                        <div class="col-sm-6">
                            <label for="polera_jugador_create"># Polera</label>
                                                           
                                <input class="form-control" id="polera_jugador_create" name="polera_jugador_create" min="1" type="number">
                          
                        </div>
                        <div class="col-sm-6">
                            <label for="nacido_jugador_create">Fecha Nac.</label>
                            <input class="form-control" id="nacido_jugador_create" name="nacido_jugador_create" type="date">
                        </div>
                        <div class="col-sm-6">
                            <label for="wpp_jugador_create">WhatsApp</label>
                            <input class="form-control" id="wpp_jugador_create" name="wpp_jugador_create" type="number">   
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" onclick="validacion_crear_jugador()" class="btn btn-sm btn-dark">Guardar</a>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal Crear Jugador-->
        <div class="modal fade modal-primary" id="modal_jugador_view">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Datos del Jugador
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <img src="/storage/jugadores/jugadordefault.png" alt="" class="img-responsive">
                            </div>
                            <div class="col-sm-6">
                                <table class="table mitable">
                                    <tr>
                                        <td>Jugador:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Edad:</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

    
@stop

@section('javascript')
    <script>
        // guardar planilla-----------------------------------------------------------------
        function misave(){
            Swal.fire({
                title: 'Estas Seguro de Guardar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {

                    // $('.mireload').attr("hidden", false)
                    $("#miboton1").hide()
                    guardar_planilla()
                }
            })
        }
        async function guardar_planilla(){
            toastr.success("Guardando Planilla, porfavor no recargue ni se salga de la página.")
            var clube_id= $("#select_club").val()
            var categoria_jugadores= $("#select_cat").val()
            // var fecha_entrega=$("#fecha_mensual").val()+"-01"
            var delegado_id=$("#select_delegado").val()
            var deuda=$("#input_deudas").val()
            var total=$("#input_total").val()
            var observacion=""
            var subtotal=$("#input_sub_total").val()
            var detalles={
                clube_id: clube_id,
                categoria_jugadores:categoria_jugadores,
                // fecha_entrega: fecha_entrega,
                delegado_id:delegado_id,
                deuda:deuda,
                total:total,
                observacion:observacion,
                subtotal:subtotal,
                men_pagadas: total,
                user_id: parseInt("{{ Auth::user()->id }}"),
                gestion: $("#gestion").val(),
                activo: "Entregado"
            }
            console.log(detalles)
            var planilla= await axios.post("/api/jugadores/planilla/save", detalles)
            var phone_club=planilla.data.clubes.wpp
            var phone_delegado=planilla.data.delegado.phone
            if (await validacion_wpp(phone_club)) {
                phone_club=phone_club.toString()
            }
            if (await validacion_wpp(phone_delegado)) {
                phone_delegado= phone_delegado.toString()
            }           
            await generar_nomina(planilla.data.id, phone_club, phone_delegado, planilla.data.fecha)
            location.href = "/admin/jugadores-planillas/"+planilla.data.id
        }

        // guardar transferencia-----------------------------------------------------------------
        function save_transferencia(){
            Swal.fire({
                title: 'Estas Seguro de Guardar la Transferencia?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('.mireload').attr("hidden", false)
                    $("#modal_transferencia .close").click()
                    transferir_jugador()
                }
            })
        }

        async function validacion_realizar_transferencia(){
            if ($("#jugador_transferencia").val()!="" && $("#observacion_transferencia").val()) {
                save_transferencia()
            } else {
                toastr.error("El jugador y la observación son datos obligatorios.")
            }
        }

        // guardar Delegado-----------------------------------------------------------------
        function save_delegado(){
            $('.mireload').attr("hidden", false)
            $("#modal_delegado .close").click()
            crear_delegado()
        }

        async function validacion_crear_delegado() {
            if ($("#delegado_creation").val()!="") {
                save_delegado()
            }
            else{
                toastr.error("El nombre es un dato obligatorio")
            }
        }

        // guardar Jugador-----------------------------------------------------------------
        function save_jugador(){
            $('.mireload').attr("hidden", false)
            $("#modal_jugador .close").click()
            crear_jugador()
        }

        async function validacion_crear_jugador() {
            if ($("#nombre_jugador_create").val() != "" && $("#polera_jugador_create").val()!="" && $("#nacido_jugador_create").val()!="") {
                save_jugador()
            }
            else{
                toastr.error("EL nombre, el número de polera y la fecha de nacimiento son datos obligatorios")
            }
        }

        async function validacion_gestion() {
            if ("{{setting('nominas.validacion_gestion')}}") {
                var ultima_planilla= await axios("/api/find/ultima/planilla/"+$("#select_club").val())
                var gestion_actual=$("#gestion").val()+"-01"
                if (ultima_planilla.data.length>0) {
                    if (ultima_planilla.data[0].gestion==gestion_actual && ultima_planilla.data[0].activo=="Aprobado") {
                        toastr.error("Ya existe una planilla de ese Mes que está Aprobada.")
                    }
                    else{
                        console.log(ultima_planilla.data)
                        console.log(gestion_actual)
                        if(Date.parse(ultima_planilla.data[0].gestion) <= Date.parse(gestion_actual)){
                            // toastr.success("correcto")
                            if (Date.parse(ultima_planilla.data[0].gestion)== Date.parse(gestion_actual)&& ultima_planilla.data[0].activo!="Entregado" && ultima_planilla.data.activo!="Aprobado" ) {
                                misave()
                            }
                            else if(Date.parse(ultima_planilla.data[0].gestion)== Date.parse(gestion_actual)&& ultima_planilla.data[0].activo=="Entregado"){
                                toastr.error("Hay una planilla de dicha gestión en proceso de verificación.")
                            }
                            else if(Date.parse(ultima_planilla.data[0].gestion)< Date.parse(gestion_actual)){
                                if (ultima_planilla.data[0].activo!="Entregado") {
                                    misave()
                                } else {
                                    toastr.error("Hay una planilla de una gestión pasada que está en proceso de verificación.")
                                }
                            }
                        }
                        else{
                            toastr.error("Está intentando crear una planilla de una gestión ya pasada")
                        }
                    }
                }
                else{
                    misave()
                }
            } 
            else {
                misave()
            }
            
        }
        async function validacion_cantidad_jugs() {
            if ("{{setting('nominas.validacion_jugs')}}") {
                var table2 = document.getElementById("table2");
                var cant_tits=0
                var cant_sups=0
                var jugs_id=[]
                var index=0;
                $('.tab_jugs_id').each(function(){
                    jugs_id[index]=this.value
                    index+=1
                })
                for (var i = 1, row; row = table2.rows[i]; i++) {
                    if ($("#check_"+parseInt(jugs_id[(i-1)])+"").prop('checked')) {
                        cant_sups+=1
                    }
                    else{
                        cant_tits+=1
                    }
                }
                if (cant_tits=="{{setting('nominas.titulares')}}"&& cant_sups>= "{{setting('nominas.suplentes_minimo')}}" && cant_sups<= "{{setting('nominas.suplentes_maximo')}}") {
                    // toastr.success("ok")
                    await validacion_gestion()
                }
                else{
                    toastr.error("La cantidad maxima de suplentes debe ser: {{setting('nominas.suplentes_maximo')}}")
                    toastr.error("La cantidad mínima de suplentes debe ser: {{setting('nominas.suplentes_minimo')}}")
                    toastr.error("La cantidad de titulares debe ser: {{setting('nominas.titulares')}}")

                }
            }
            else{
                await validacion_gestion()
            }
          
        }

        async function probar_mensaje_whatsapp() {
            var telefono= '70269362'
            if (await validacion_wpp(telefono)) {
                var midata={
                    phone: telefono,
                    message: "hola"
                }
            }
        }

        function calcularEdad(fecha) {
            var hoy = new Date();
            var cumpleanos = new Date(fecha);
            var edad = hoy.getFullYear() - cumpleanos.getFullYear();
            var m = hoy.getMonth() - cumpleanos.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
                edad--;
            }

            return edad;
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
            mitext+="Puede Verificar el Estado de la misma en: "+"https://ligadefutbol.loginweb.dev/admin/jugadores-planillas/1\n"
            var midata={
                    phone: '70269362',
                    message: mitext
                }
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
                if (await validacion_wpp(phone)) {
                    var midata={
                        phone: phone.toString(),
                        message: mitext
                    }
                    try {
                        await axios.post("/api/whaticket/send", midata)
                    } catch (error) {
                        toastr.error("Falló la notificación con WhatsApp.")
                    }
                   
                }
        }

        function comparar_exis_jug(id){
            var validador=false;
            $('.tab_jugs_id').each(function(){
                if (id==this.value) {
                    validador=true
                }
            })
            return validador;
        }

        //MENSAJE para Whatsapp de la PLANILLA --------------------------------------------------------------
        async function generar_nomina(planilla_id, phone_club, phone_delegado, fecha){
            var jugs=[];
            var jugs_id=[];
            var jugs_phone=[];
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
                var mensualidad= 0
                if (row.cells[4].innerText!="") {
                    mensualidad= parseInt(row.cells[4].innerText)
                }
                else{
                    mensualidad= 0
                }

                if ($("#check_"+parseInt(jugs_id[(i-1)])+"").prop('checked')) {
                    titular=2
                    wpp_index_sups+=1
                    wpp_suplentes+=wpp_index_sups+".- "+row.cells[3].innerText+"\n"
                    if ("{{setting('notificaciones.noti_asientos')}}") {
                        if (await validacion_wpp(jugs_phone[(i-1)])) {
                            await notificacion_asientos_jugadores(fecha, "Suplente", "- "+row.cells[3].innerText, "- "+row.cells[2].innerText, row.cells[4].innerText, jugs_phone[(i-1)])
                        }
                    }
                }
                else{
                    titular=1
                    //Cuerpo Titulares
                    wpp_index_tits+=1
                    wpp_titulares+=wpp_index_tits+".- "+row.cells[3].innerText+"\n"
                    if ("{{setting('notificaciones.noti_asientos')}}") {
                        if (await validacion_wpp(jugs_phone[(i-1)])) {
                            await notificacion_asientos_jugadores(fecha, "Titular", "- "+row.cells[3].innerText, "- "+row.cells[2].innerText, row.cells[4].innerText, jugs_phone[(i-1)])
                        }
                    }
                }

                var midata={
                    planilla_id:planilla_id,
                    jugador_id: parseInt(jugs_id[(i-1)]),
                    titular: titular,
                    mensualidad: mensualidad
                }
                await axios.post("/api/jugadores/rel/planilla/jugs/save", midata)
                var monto_dinero= parseInt("{{setting('finanzas.mensualidad_jug')}}")
                if (mensualidad < monto_dinero) {
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
                    var asiento= await axios.post("/api/asiento/save", data)
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
                    var asiento= await axios.post("/api/asiento/save", data)
                }
            }
            //Armando el cuerpo
            mitext+=wpp_titulares
            mitext+="\n*Suplentes*:\n"
            mitext+=wpp_suplentes
            mitext+="\nSe enviará un mensaje cuando se tome una decisión respecto a esta planilla.\n\n"
            //Condicional de repetidos
            if ("{{setting('notificaciones.noti_planillas')}}") {
                if (phone_club==phone_delegado) {
                    if (await validacion_wpp(phone_club)) {
                        var newpassword=Math.random().toString().substring(2, 6)
                        var midata_cred = {
                            clube_id: parseInt($("#select_club").val()),
                            password: newpassword
                        }
                        var user= await axios.post("/api/reset/credenciales/club", midata_cred)
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
                            toastr.error("Falló la notificación por WhatsApp.")
                        }
                        
                    }
                }
                else{
                    if (await validacion_wpp(phone_delegado)) {
                        var midata={
                            phone: phone_delegado,
                            message: mitext
                        }
                        try {
                            await axios.post("/api/whaticket/send", midata)
                        } catch (error) {
                            toastr.error("Falló en notificación por WhatsApp.")
                        }
                    }
                    if (await validacion_wpp(phone_club)) {
                        var newpassword=Math.random().toString().substring(2, 6)
                        var midata_cred = {
                            clube_id: parseInt($("#select_club").val()),
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
                            toastr.error("Falló en notificación por WhatsApp.")
                        }
                        
                    }
                }
                var midata={
                    cant_jugs_deudores:cant_jugs_deudas,
                    planilla_id: planilla_id
                }
            }
            await axios.post("/api/update/cant/jugs/deudores", midata)
            location.href="/admin/jugadores-planillas"
        }

        // VALIDACION DE WHATSAPP -------------------------------------------------------------
        async function validacion_wpp(phone){
            var wpp=parseInt(phone)
            if (wpp<=79999999 && wpp>=60000000) {
                return true;
            }
            else{
                return false;
            }
        }

        async function add_todos(){
            var equipo_id=$("#select_club").val()
            var jugadores= await axios("/api/jugadores/planilla/find/jugadores/"+equipo_id)
            for (let index = 0; index < jugadores.data.length; index++) {
                var jugador= await axios("/api/jugadores/find/"+jugadores.data[index].id)
                await add_jugador(jugador)
            }   
            toastr.success("Planilla a Completa Registrada")
        }

        async function add_fila(){
            var jugador_id=0
                jugador_id= $("#select_jugador").val()
            if (jugador_id !="null") {
                var jugador= await axios("/api/jugadores/find/"+jugador_id )
                var cont= count_jugs()
                var num=cont+1
                if (cont==0) {
                    if (jugador.data.clube_id!= $("#select_club").val()) {
                        if ($("#select_club").val()=="null") {
                            toastr.error("Selecciona el Club en la parte Superior del cual crearás la plantilla.")
                        }
                        else{
                            toastr.error("El Jugador que intenta ingresar pertenece a otro equipo, realice la transferencia si desea utilizarlo.")
                        }
                    }
                    else{
                        await add_jugador(jugador)
                    }
                }else{
                    var clube_id=club_jugs()
                    if (jugador.data.clube_id!=clube_id ) {
                        toastr.error("El Jugador que intenta ingresar pertenece a otro equipo, realice la transferencia si desea utilizarlo.")
                    }
                    else{
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
                    $('#table2').append("<tr><td><input class='tab_jugs_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs' type='number' value="+jugador.data.clube_id+" hidden><input class='tab_jugs_phone' type='number' value="+jugador.data.phone+" hidden>"+cont+"</td><td class='tab_jugs'><input id='check_"+jugador.data.id+"' type='checkbox'></td><td><span class='label label-warning'>"+jugador.data.polera+"</span></td><td><a href='#' data-toggle='modal' data-target='#modal_jugador_view'>"+jugador.data.name+"</a></td><td class='mensualidad_table_tit'>"+parseInt("{{setting('finanzas.mensualidad_jug')}}")+"</td></tr>");

                    example2.init();
                total_mensualidades()
            }                       
        }

        function total_mensualidades() {
            var tits= count_jugs()
            var cuotas= parseInt(tits)
            var sub_total_tits= 0
            var sub_total_sups=0
            var subtotal=0
            for (var i = 1, row; row = table2.rows[i]; i++) {
                sub_total_tits+= parseInt(row.cells[4].innerText)
            }
            subtotal= (tits)*parseInt("{{setting('finanzas.mensualidad_jug')}}")

                $("#input_sub_total").val(subtotal)
                $("#input_total").val((sub_total_sups+sub_total_tits))
            var monto_adeudado= subtotal-(sub_total_tits+sub_total_sups)
                $("#input_deudas").val(monto_adeudado)
        }

        function count_jugs(){
            var num=0
                $('.tab_jugs').each(function(){
                    num+=1
                })
            return num;
        }

        function club_jugs(){
            var clube_id=0
                clube_id=$("#select_club").val()
            return clube_id;
        }

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
                var jugador= await axios("/api/jugadores/find/"+$("#jugador_transferencia").val() )
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
                'edad': calcularEdad($("#nacido_jugador_create").val()),
                'nacido':$("#nacido_jugador_create").val(),
                'clube_id':$("#select_club").val(),
                'phone':$("#wpp_jugador_create").val()
            }
            var jugador= await axios.post("/api/create/jugador", midata)
            if (jugador.data!=null) {
                $('.mireload').attr("hidden", true)
                var cont= count_jugs()
                await add_jugador(jugador)
                toastr.success("Jugador Creado Exitosamente")
            }
        }

        var example2 = new BSTable("table2", {
			editableColumns:"4",
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

            // console.log(calcularEdad("1999-01-01"))
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