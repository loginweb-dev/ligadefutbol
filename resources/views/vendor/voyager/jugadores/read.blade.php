@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
<link href="{{setting('admin.url')}}plugin_profile/css/tm_editable.css" type="text/css"  rel="stylesheet">
<link href="{{setting('admin.url')}}plugin_profile/css/tm_validator.css" type="text/css"  rel="stylesheet">
<link href="{{setting('admin.url')}}plugin_profile/css/themes/round-button-theme.css" type="text/css"  rel="stylesheet" >
@stop

@section('content')
    @php
        $sin_perfil='jugadores/jugadordefault.png';
        $jugadore=App\Jugadore::where('id', $dataTypeContent->id)->with('clubes', 'transferencias')->first();
        $rel_temporada= App\RelTemporadaJugadore::where('jugadore_id', $dataTypeContent->id)->with('temporadas', 'clubes')->get();
        $transferencias= App\Transferencia::where('jugadore_id', $dataTypeContent->id)->with('club_origen', 'club_destino')->get();
        $equipos=App\Clube::all();
    @endphp
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    {{-- <h1 class="text-center">Jugador: {{$dataTypeContent->name}}</h1> --}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div  class="table-responsive">
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center" colspan="3"><h4>Datos Personales</h4></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td rowspan="4" class="text-center">
                                               
                                                @if ($dataTypeContent->foto)
                                                    <img src="@if( !filter_var($dataTypeContent->foto, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->foto ) }}@else{{ $dataTypeContent->foto }}@endif" style="width:150px">
                                                @else
                                                    <img src="{{Voyager::image($sin_perfil)}}" style="width:150px">
                                                @endif
                                            </td>

                                        </tr>
                                        <tr>
                                            <td><b>Nombre:</b></td>
                                            <td >
                                                <div class="tm_editable_container input-group theme1 " id="text_demo_nombre" data-iplaceholder="Escribe el Nombre..">
                                                    <input id="input_name" type="text" value ="{{$dataTypeContent->name}}" >
                                                </div>
                                            </td>

                                          
                                        </tr>
                                        <tr>
                                            <td><b>Nº Polera:</b></td>
                                            <td >
                                                <div class="tm_editable_container input-group theme1" id="text_demo_polera" data-iplaceholder="Escribe el Nombre..">
                                                    <input type="text" value ="{{$dataTypeContent->polera}}" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Edad:</b></td>
                                            <td >
                                                <div class="tm_editable_container input-group theme1" id="text_demo_edad" data-iplaceholder="Escribe el Nombre..">
                                                    <input type="text" value ="{{$dataTypeContent->edad}}" >
                                                </div>
                                            </td>
                                        </tr>
                                            
                                        <tr>
                                            <td><b>Fecha Nacimiento:</b></td>
                                            <td colspan="2">
                                                {{-- {{$dataTypeContent->nacimiento}} --}}
                                                <div class="tm_editable_container input-group theme1" id="text_demo_nacimiento" >
                                                    <input type="text" value="{{$dataTypeContent->nacido}}">
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Categoria:</b></td>
                                            <td colspan="2">
                                                <div class="tm_editable_container input-group " id="select_demo_categoria">
                                                    <select class=" my_select">
                                                        @if ($dataTypeContent->jug_categoria=="Senior")
                                                            <option value="Senior" selected="selected">Senior</option>
                                                            <option value="Especial" >Especial</option>
                                                        @else
                                                            <option value="Senior">Senior</option>
                                                            <option value="Especial" selected="selected">Especial</option>
                                                        @endif
                                                      
                                                    </select>
                                                </div>
                                               
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Club Actual:</b></td>
                                            {{-- <td colspan="2">{{$jugadore->clubes->name}}</td> --}}
                                            <td colspan="2">
                                                <div class="tm_editable_container input-group " id="select_demo_club">
                                                    <select class=" my_select">
                                                        @foreach ($equipos as $item)
                                                            @if($item->id == $jugadore->clubes->id)
                                                                <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                                            @else
                                                                <option value="{{$item->id}}" >{{$item->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                  
                                        <tr>
                                            <td><b>WhatsApp:</b></td>
                                            <td colspan="2" >
                                                <div class="tm_editable_container input-group theme1 " id="text_demo_whatsapp" data-iplaceholder="Escribe el WhatsApp..">
                                                    <input type="text" value ="{{$dataTypeContent->phone}}" >

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- <img src="@if( !filter_var($dataTypeContent->foto, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->foto ) }}@else{{ $dataTypeContent->foto }}@endif" style="width:500px"> --}}

                            </div>
                            <div class="text-center">
                                <button type='button' onclick='send_carnet()' class='btn btn-success'>Enviar Carnet</button>

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div  class="table-responsive">
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center" colspan="7"><h4>Trayectoria</h4></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Club Origen</th>
                                            <th class="text-center">Club Destino</th>
                                            <th class="text-center">Fecha Transferencia</th>
                                            <th class="text-center">Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($transferencias!="[]")
                                            <tr>
                                                <td  class="text-center success">{{$transferencias[0]->club_origen->name}}</td>
                                                <td  class="text-center success"><-- Empezó su debut en este equipo</td>
                                                <td class="text-center success">{{$jugadore->fecha}}</td>
                                                <td class="text-center success">0</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="text-center success">{{$jugadore->clubes->name}}</td>
                                                <td  class="text-center success"><-- Empezó su debut en este equipo</td>
                                                <td class="text-center success">{{$jugadore->fecha}}</td>
                                                <td class="text-center success">0</td>
                                            </tr>
                                        @endif
                                       
                                     
                                        @foreach ($transferencias as $item)
                                          
                                            <tr>
                                                <td class="text-center">{{$item->club_origen->name}}</td>
                                                <td class="text-center">{{$item->club_destino->name}}</td>
                                                <td class="text-center">{{$item->fecha}}</td>
                                                <td class="text-center">{{$item->precio}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div  class="table-responsive">
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center" colspan="6"><h4>Rendimiento</h4></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Temporada</th>
                                            <th class="text-center">Club</th>
                                            <th class="text-center">Media de Goles</th>
                                            <th class="text-center">Partidos Jugados</th>
                                            <th class="text-center">Partidos de Titular</th>
                                            <th class="text-center">Partidos de Suplente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // $planilla= App\JugadoresPlanilla::where('temporada_id', $dataTypeContent->temporada_id)->where('clube_id', $dataTypeContent->clube_id)->get();
                                            $rel_planilla_jugadores= App\RelPlanillaJugadore::where('jugador_id', $dataTypeContent->id)->with('planilla')->get();
                                        @endphp
                                        @foreach ($rel_temporada as $item)
                                            @php
                                                $partido_titular=0;
                                                $partido_suplente=0;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$item->temporadas->title}}</td>
                                                <td class="text-center">{{$item->clubes->name}}</td>

                                                @foreach ($rel_planilla_jugadores as $item2)
                                               
                                                    @if ($item->temporada_id==$item2->planilla->temporada_id && ($item2->planilla->activo=="Inactivo" || $item2->planilla->activo=="Aprobado"))
                                                        @php
                                                            $partido_titular+=1;
                                                        @endphp
                                                        @if ($item2->titular=1)
                                                            @php
                                                                $partido_titular+=1;
                                                            @endphp
                                                        @endif
                                                        @if ($item2->titular=2)
                                                            @php
                                                                $partido_suplente+=1;
                                                            @endphp
                                                    @endif
                                                    
                                                    @endif
                                                @endforeach
                                                @php
                                                    $media_goles=0;
                                                    $total_partidos=$partido_titular+$partido_suplente;
                                                    if ($total_partidos>0) {
                                                        $media_goles= $item->goles/$total_partidos;
                                                    }
                                                @endphp
                                                <td class="text-center">{{round($media_goles, 1)}}</td>
                                                <td class="text-center">{{$item->partidos}}</td>
                                                <td class="text-center">{{$partido_titular}}</td>
                                                <td class="text-center">{{$partido_suplente}}</td>
                                            </tr>
                                           
                                        @endforeach
                                      
                                        {{-- <tr>
                                            <td>Temporada 2021-2022</td>
                                            <td>Las Aguilas FC</td>
                                            <td>1</td>
                                            <td>10</td>
                                            <td>8</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>Temporada 2022-2023</td>
                                            <td>IPTV</td>
                                            <td>2</td>
                                            <td>30</td>
                                            <td>25</td>
                                            <td>5</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        {{-- <div class="col-sm-4">
                            <div  class="table-responsive">
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center" colspan="6"><h4>Rendimiento</h4></th>
                                        </tr>
                                        <tr>
                                            <td>Temporada</td>
                                            <th>Club</th>
                                            <th>Media de Goles</th>
                                            <th>Partidos Jugados</th>
                                            <th>Partidos de Titular</th>
                                            <th>Partidos de Suplente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Temporada 2021-2022</td>
                                            <td>Las Aguilas FC</td>
                                            <td>1</td>
                                            <td>10</td>
                                            <td>8</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>Temporada 2022-2023</td>
                                            <td>IPTV</td>
                                            <td>2</td>
                                            <td>30</td>
                                            <td>25</td>
                                            <td>5</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>                         --}}
                    </div>
                    
                </div>
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
@stop

@section('javascript')
    {{-- @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                //Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script> --}}
    {{-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  --}}
    <script src="{{setting('admin.url')}}plugin_profile/js/tm_validator.js"></script> 
    <script src="{{setting('admin.url')}}plugin_profile/js/tm_editable.js"></script> 
    <script>
        async function send_carnet() {
            if (await validacion_wpp("{{$dataTypeContent->phone}}")) {
                var imagen= "$dataTypeContent->foto"
                if ("$dataTypeContent->foto") {
                 imagen= "/jugadores/jugadordefault.png"   
                }
                var midata2={
                    phone: "{{$dataTypeContent->phone}}",
                    imagen:"{{setting('admin.url')}}storage"+imagen
                }
                // console.log("{{setting('admin.url')}}storage"+imagen)
                try {
                    await axios.post("/api/whaticket/multimedia/send", midata2)
                } catch (error) {
                    toastr.error("Falló en notificación por WhatsApp.")
                }
                var mitext= ""
                mitext+="*Nombre:* {{$dataTypeContent->name}}\n"
                mitext+="*Nº Polera:* {{$dataTypeContent->polera}}\n"
                mitext+="*Edad:* {{$dataTypeContent->edad}}\n"
                mitext+="*Fecha Nacimiento:* {{$dataTypeContent->nacimiento}}\n"
                mitext+="*Categoria:* {{$dataTypeContent->jug_categoria}}\n"
                mitext+="*Club Actual:* {{$jugadore->clubes->name}}\n"
                var midata={
                    phone: "{{$dataTypeContent->phone}}",
                    message: mitext
                }
                try {
                    await axios.post("/api/whaticket/send", midata)
                } catch (error) {
                    toastr.error("Falló en notificación por WhatsApp.")
                }
                
            }
            else{

            }
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
        $('document').ready(function () {
            $('#text_demo_nombre, .width_auto_demo').tm_editbale('init', initial('text_demo_nombre', 'name', 'tm-edit-0'))
            $('#text_demo_polera, .width_auto_demo').tm_editbale('init', initial('text_demo_polera', 'polera', 'tm-edit-1'))
            $('#text_demo_edad, .width_auto_demo').tm_editbale('init', initial('text_demo_edad', 'edad', 'tm-edit-2'))
            $('#text_demo_nacimiento, .width_auto_demo').tm_editbale('init', initial('text_demo_nacimiento', 'nacido', 'tm-edit-3'))
            $('#select_demo_categoria, .width_auto_demo').tm_editbale('init', initial('select_demo_categoria', 'jug_categoria', 'tm-edit-4'))
            $('#select_demo_club, .width_auto_demo').tm_editbale('init', initial('select_demo_club', 'clube_id', 'tm-edit-5'))
            $('#text_demo_whatsapp, .width_auto_demo').tm_editbale('init', initial('text_demo_whatsapp', 'phone', 'tm-edit-6'))
        });
        async function send_carnet_jugador() {
            var phone= "{{$dataTypeContent->phone}}"
            if (phone!="") {
                
            }
        }
        function initial(text_demo, name, div_id) {
          
            var array= {
                            theme:'round-button-theme',
                            outside_btn:{
                                            onshow:"&nbsp;<i class='voyager-edit'></i>&nbsp;",
                                            new_line:false,
                                            onhover:''
                                        },
                            inside_btn:{
                                            
                                            new_line:false,
                                            ok:"<i class='fa fa-check'></i>",
                                            cancel:"<i class='fa fa-times'></i>"
                                        },
                            ok:{
                                before:function(value){
                                    var deferred = $.Deferred();
                                    //your code start here
                                
                                    //alert(value) //value of element
                                    // $("#tm-edit-0 .i_text").html("hola")
                                    validar_input(text_demo, value, name, div_id)
                                    //your code ends here
                                    deferred.resolve();
                                    return deferred.promise();
                                },
                                after:function(){
                                    //$("#tm-edit-0 .i_text").html("{{$dataTypeContent->name}}")
                                    // $("#tm-edit-0").html("<div class='tm_editable_container input-group theme1 has_tm_editable_container round-button-theme'><div class='no_edit full_row'><span class='i_text'>{{$dataTypeContent->name}}</span><span class='outside_btncontainer right'><a class='i_edit'><i class='voyager-edit'></i><span class='i_edit_text'></span></a></span></div></div>")
                                }
                            } ,
                            remove:{
                                before:function(){
                                    var deferred = $.Deferred();
                                    //your code start here
                                    alert('hello')
                                    //your code ends here
                                    deferred.resolve();
                                    return deferred.promise();
                                },
                                after:function(){}
                            }
                        
                        }

            return array;
            
        }
        async function validar_input(text_demo, value, name, div_id) {
            if(value!=""){
                switch (name) {
                    case 'polera':
                            if (parseInt(value)>0) {
                                var midata={id: "{{$dataTypeContent->id}}",valor: value, name: name}
                                await axios.post("/api/jugadores/update/jugador", midata)
                                toastr.success("Actualizó el número de polera exitosamente")
                            }
                            else{
                                toastr.error("Ingrese un numero de polera válido")
                            }
                        
                        
                      
                        break;
                    case 'edad':
                        if (parseInt(value)>0) {
                            var midata={id: "{{$dataTypeContent->id}}",valor: value, name: name}
                            await axios.post("/api/jugadores/update/jugador", midata)
                            toastr.success("Actualizó la edad exitosamente")
                        }
                        else{
                            toastr.error("Ingrese una edad válida")
                        }
                        break;
                    case 'nacido':
                        let isValidDate = Date.parse(value);

                        if (isNaN(isValidDate)) {
                            var midata={id: "{{$dataTypeContent->id}}",valor: value, name: name}
                            await axios.post("/api/jugadores/update/jugador", midata)
                            toastr.success("Actualizó la fecha de nacimiento exitosamente")
                        }
                        else{
                            toastr.error("Ingrese una fecha válida")
                        }
                      
                        break;
                    case 'phone':
                        if (parseInt(value)>59999999 && parseInt(value)<80000000) {
                            var midata={id: "{{$dataTypeContent->id}}",valor: value, name: name}
                            await axios.post("/api/jugadores/update/jugador", midata)
                            toastr.success("Actualizó el número de celular exitosamente")
                        }
                        else{
                            toastr.error("Ingrese un numero de celular válido")
                        }
                        break;
                    default:
                    console.log("guardar")
                    var midata={id: "{{$dataTypeContent->id}}",valor: value, name: name}
                            await axios.post("/api/jugadores/update/jugador", midata)
                            toastr.success("Actualizó el campo exitosamente")
                            break;
                }
               
            }
            else{
                toastr.error("No puede Guardar el campo vacío")
                location.reload()
            }

        }
    </script>
@stop
