@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    
    $equipos= App\Clube::all();
    $jugadores= App\Jugadore::all();
    $delegados= App\Delegado::all();
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
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="text-center">
                        <h2 class="subs">MUTUAL DE EX JUGADORES DE FUTBOL TRINIDAD</h2>
                        <span class="subs">FUNDADA EL 8 DE FEBRERO DE 1987 <br> MEDIANTE RESOLUCIÓN PEFECTURAL Nª. 050/99</span>
                    </div>
                    <hr>

                    <div class="row">
                 
                        <div class="col-md-12">

                            <div class="col-sm-4">
                                <label for="select_club">Club</label>
                                <div style="border-style: outset;">                                
                                    <select class="form-control select2" name="select_club" id="select_club">
                                        {{-- <option value="null">Elegir el Club</option> --}}
                                        @foreach ($equipos  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach     
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
                                <label for="select_delegado">Delegado</label>
                                <div style="border-style: outset;">    
                                    <select class="form-control select2" name="select_delegado" id="select_delegado">
                                        @foreach ($delegados  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach  
                                    </select>             
                                </div>                   
                            </div>
                            <div class="col-sm-12">
                                <hr>
                            </div>
                            <div class="col-sm-6">
                                <label for="input_fecha">Fecha</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" type="date" name="input_fecha" id="input_fecha">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="input_hora">Hora de Entrega</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" type="time" name="input_hora" id="input_hora">
                                </div>
                            </div>
                            {{-- <div class="col-md-3 ">
                                <label for="select_veedor">VªBª Veedor</label>
        
                                <select class="form-control select2" name="select_veedor" id="select_veedor">
                                    @foreach ($delegados  as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach   
                                </select>
                            </div> --}}
            
                        </div>
                        <hr>
                        <div class="col-md-12 text-center">
                            <h3 class="subs">
                                NÓMINA DE JUGADORES
                            </h3>
                            <h4 class="subs col-md-12">Titulares</h4>                                

                            <div class="col-sm-4">

                                <div style="border-style: outset;">
                                    <select name="" id="select_equipo" class="form-control select2">
                                        <option value="null">Equipos</option>
                                        @foreach ($equipos  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach                    
                                    </select>
                                </div>
                               
                            </div>
                            <div class="col-sm-4">
                                <div style="border-style: outset;">
                                    <select name="" id="select_jugador" class="form-control select2">
                                        {{-- <option value="null">Jugadores</option>
                                        @foreach ($jugadores  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach                     --}}
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-sm-2">
                                <button id="table2-new-row-button" onclick="add_todos()"  class="btn btn-sm btn-dark">Agregar a Todos </button>
                            </div>
                            <div class="col-sm-2">
                                <button id="table2-new-row-button" onclick="add_fila(1)"  class="btn btn-sm btn-dark">Agregar Titular </button>
                            </div>
                            {{-- <select name="" id="cluba" class="form-control">
                                <option value="">Clubes</option>
                                @foreach ($nomina  as $item)
                                    <option value="{{ $item->id }}">{{ $item->clubes->name }}</option>
                                @endforeach                    
                            </select> --}}
                            
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
                                    {{-- <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr> --}}
                                </tbody>
                               
                            </table>

                            <h4 class="subs col-md-12">Suplentes</h4>

                            <div class="col-md-4">
                                <div style="border-style: outset;">

                                    <select name="" id="select_equipo_sup" class="form-control select2">
                                        <option value="null">EQUIPOS</option>
                                        @foreach ($equipos  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="border-style: outset;">

                                    <select name="" id="select_jugador_sup" class="form-control select2">
                                        {{-- <option value="null">JUAGADORES</option>
                                        @foreach ($jugadores  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach   
                                        <option value="null">AGREGAR A TODOS</option>                  --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button id="table3-new-row-button" onclick="add_fila(2)"  class="btn btn-sm btn-dark">Agregar Jugador</button>
                            </div>
                        
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
                     
                                </tbody>
                            </table>

                            <hr>
                            <h3 class="subs">TOTALES</h3>
                            
                            <div class="col-md-4">
                                <label for="input_total">Monto Esperado</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_sub_total" name="input_sub_total" type="number">
                                </div>
                            </div>
            
                            <div class="col-md-4">
                                <label for="input_deudas">Monto Adeudado</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_deudas" name="input_deudas" type="number">
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <label for="input_total">Total Pagado</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_total" name="input_total" type="number">
                                </div>
                            </div>

                            
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="form-group">
                                
                                <button class="btn btn-dark btn-block" onclick="misave()">Guardar Formulario</button>
                            </div>
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

        async function guardar_planilla(){
            var club_id= $("#select_club").val()
            var categoria_jugadores= $("#select_cat").val()
            var fecha_entrega=$("#input_fecha").val()
            var hora_entrega=$("#input_hora").val()
            var veedor_id= $("#select_veedor").val()
            var delegado_id=$("#select_delegado").val()
            var deuda=$("#input_deudas").val()
            var total=$("#input_total").val()
            // var observacion=$("#text_area_deudas").val()
            var observacion=""
            var subtotal=$("#input_sub_total").val()

            var detalles={
                club_id: club_id,
                categoria_jugadores:categoria_jugadores,
                fecha_entrega:fecha_entrega,
                hora_entrega:hora_entrega,
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
            $('.tab_jugs_tits_id').each(function(){
                if (id==this.value) {
                    validador=true
                }
            })
            $('.tab_jugs_sups_id').each(function(){
                if (id==this.value) {
                    validador=true
                }
            })
            return validador;
        }

        async function generar_nomina(planilla_id){
            var jugs_tits=[];
            var jugs_sups=[];
            var jugs_tits_id=[];
            var jugs_sups_id=[];



            var table2 = document.getElementById("table2");

            var index=0;
            $('.tab_jugs_tits_id').each(function(){
                jugs_tits_id[index]=this.value
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

                var midata={
                    planilla_id:planilla_id,
                    jugador_id: parseInt(jugs_tits_id[(i-1)]),
                    titular: 1,
                    mensualidad: mensualidad
                }
                await axios.post("/api/jugadores/rel/planilla/jugs/save", midata)
                if (mensualidad<1400) {
                    var data={
                        tipo: "Ingreso",
                        detalle:"Mensualidades",
                        monto: (1400-mensualidad),
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        club_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_tits_id[(i-1)]),
                        observacion: "Debe Mensualidad",
                        estado: "Pendiente",
                        monto_pagado: 0,
                        monto_restante:(1400-mensualidad)
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data)

                }
                else{
                    var data={
                        tipo: "Ingreso",
                        detalle:"Mensualidades",
                        monto: 1400,
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        club_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_tits_id[(i-1)]),
                        observacion: "Pagó Total Mensualidad",
                        estado: "Pagado",
                        monto_pagado: 1400,
                        monto_restante:0
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data)

                }
               console.log(midata)
            
            }

            var table3 = document.getElementById("table3");

            var index2=0;
            $('.tab_jugs_sups_id').each(function(){
                jugs_sups_id[index2]=this.value
                index2+=1
            })

            // console.log(jugs_sups_id)

            for (var i = 1, row; row = table3.rows[i]; i++) {
                var mensualidad= 0
                if (row.cells[4].innerText!="") {
                    mensualidad= parseInt(row.cells[4].innerText)
                }
                else{
                    mensualidad= 0
                }
                var midata2={
                    planilla_id:planilla_id,
                    jugador_id: parseInt(jugs_sups_id[(i-1)]),
                    titular: 2,
                    mensualidad: mensualidad
                }
                await axios.post("/api/jugadores/rel/planilla/jugs/save", midata2)
                if (mensualidad<1400) {
                    var data2={
                        tipo: "Ingreso",
                        detalle:"Mensualidades",
                        monto: (1400-mensualidad),
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        club_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_sups_id[(i-1)]),
                        observacion: "Debe Mensualidad",
                        estado: "Pendiente",
                        monto_pagado: 0,
                        monto_restante:(1400-mensualidad)
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data2)

                }
                else{
                    var data2={
                        tipo: "Ingreso",
                        detalle:"Mensualidades",
                        monto: 1400,
                        editor_id:parseInt("{{Auth::user()->id}}"),
                        planilla_id: planilla_id,
                        club_id: $("#select_club").val(),
                        jugador_id: parseInt(jugs_sups_id[(i-1)]),
                        observacion: "Pagó Total Mensualidad",
                        estado: "Pagado",
                        monto_pagado: 1400,
                        monto_restante:0
                    }
                    var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", data2)

                }
                console.log(midata2)
            }

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
                await add_jugador(jugador, (index+1), 1)
            }   
            toastr.success("Planilla a Completa Registrada")
        }
        async function add_fila(tipo){
            var jugador_id=0
            if (tipo==1) {
                jugador_id= $("#select_jugador").val()
            }
            else{
                jugador_id= $("#select_jugador_sup").val()
                // console.log("Jugador: "+jugador_id)
            }

            if (jugador_id !="null") {

                var jugador= await axios("/api/jugadores/find/"+jugador_id )
                
                var cont= count_jugs(1)
                if (tipo==2) {
                    cont=cont+ count_jugs(2)
                }
                var num=cont+1
                if (cont==0) {
                    // console.log("Hola1")
                    // console.log("Select"+$("#select_club").val())
                    // console.log(jugador.data.club_id)
                    if (jugador.data.club_id!= $("#select_club").val()) {
                        if ($("#select_club").val()=="null") {
                            toastr.error("Selecciona el Club en la parte Superior del cual crearás la plantilla.")
                        }
                        else{
                            toastr.error("El Jugador que intenta ingresar pertenece a otro equipo, realice la transferencia si desea utilizarlo.")
                        }
                    }
                    else{
                        await add_jugador(jugador, num, tipo)
                      
                    }
                }else{
                    // console.log("Hola2")

                    var club_id=club_jugs(tipo)

                    // console.log("Club "+club_id)
                    // console.log("Jugador Equipo "+jugador.data.club_id)

                    if (jugador.data.club_id!=club_id ) {
                        toastr.error("El Jugador que intenta ingresar pertenece a otro equipo, realice la transferencia si desea utilizarlo.")
                    }
                    else{
                        await add_jugador(jugador, num, tipo)
                    }
                }
            }
            else{
                toastr.error("Seleccione Jugador para Agregarlo")
            }
          
            
           
            
        }

        async function add_jugador(jugador, cont, tipo){
            if(comparar_exis_jug(jugador.data.id)){
                toastr.error("El Jugador: "+jugador.data.name+" ya se encuentra en la nómina")
            }
            else{
                if (tipo==1) {
                    $('#table2').append("<tr><td><input class='tab_jugs_tits_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs' type='number' value="+jugador.data.club_id+" hidden>"+cont+"</td><td class='tab_jugs_tits'>"+jugador.data.edad+"</td><td>  "+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_tit'>1400</td></tr>");
                    example2.init();
                }
                else{
                    $('#table3').append("<tr><td><input class='tab_jugs_sups_id' type='number' value="+jugador.data.id+" hidden><input class='tab_club_jugs_sups' type='number' value="+jugador.data.club_id+" hidden>"+cont+"</td><td class='tab_jugs_sups'>"+jugador.data.edad+"</td><td>"+jugador.data.polera+"</td><td> "+jugador.data.name+"</td><td class='mensualidad_table_sup'>1400</td></tr>");
                    example3.init();
                }
                total_mensualidades()
            }
           
            
        }

        // function add_deudas() {
        //     $('#table4').append("<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>")
        //     example4.init()
        // }

        function total_mensualidades() {
            var tits= count_jugs(1)
            var sups =count_jugs(2)
            var cuotas= parseInt(tits) + parseInt(sups)
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
            for (var i = 1, row; row = table3.rows[i]; i++) {
                sub_total_sups+= parseInt(row.cells[4].innerText)
            }




            subtotal= (tits+sups)*1400

                $("#input_sub_total").val(subtotal)
                $("#input_total").val((sub_total_sups+sub_total_tits))
            var monto_adeudado= subtotal-(sub_total_tits+sub_total_sups)
                $("#input_deudas").val(monto_adeudado)

        }

        function count_jugs(tipo){
            var num=0
            if (tipo==1) {
                $('.tab_jugs_tits').each(function(){
                    num+=1
                })
            }
            else{
                $('.tab_jugs_sups').each(function(){
                    num+=1
                })
            }
           
            // console.log(num)
            return num;
        }

        function club_jugs(tipo){
            var club_id=0

            if (tipo==1) {
                $('.tab_club_jugs').each(function(){
                    club_id=this.value
                })
            }
            else{
                club_id=$("#select_club").val()
            }
           
            // console.log(num)
            return club_id;
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
        var example3 = new BSTable("table3", {
			editableColumns:"2,4",
			// $addButton: $('#table2-new-row-button'),
			onEdit:function() {
                total_mensualidades()
				console.log("EDITED");
			},
			advanced: {
				columnLabel: ''
			}
		});

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

        $('document').ready(function () {
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
    </script>
@stop

@section('mijs')
    
@stop
