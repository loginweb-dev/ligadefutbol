@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());

    $nomina = App\JugadoresPlanilla::where('activo', true)->with('clubes')->get();
    $arbitro = App\Arbitro::all();
    $delegados = App\Delegado::all();
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="page-content edit-add container-fluid">

        <div class="row">
            <div class="col-sm-offset-2  col-sm-8">
                <div class="text-center">
                    <h2 class="">FORMULARIO DE NUEVO PARTIDO</h2>
                    <p>Registra la nomina de los dos equipos para crear el partido.</p>
                    <H4>MUTUAL DE EX JUFADORES DE FUTBOL TRINIDAD</H4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="accordion" id="accordionExample">

                <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsemi" aria-expanded="false" aria-controls="collapsemi">
                          <h3>1.- DATOS DEL PARTIDO</h3>
                        </button>
                      </h5>
                    </div>
                    <div id="collapsemi" class="collapse collapsed" aria-labelledby="headingOne" data-parent="#accordionExample">
                      <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <label for="">Fecha del Partido</label>
                                    <input type="date" name="" id="fecha" class="form-control">
                                </td>
                                <td>
                                    <label for="">Hora del Partido</label>
                                    <input type="time" name="" id="hora" class="form-control">
                                </td>
                         
                                <td>
                                    <label for="">Categoria del Partido</label>
                                    <div style="border-style: outset;">
                                        <select name="" id="categoria" class="miselect form-control select2">
                                            <option value="Senior">Senior</option>
                                            <option value="Especial">Especial</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="">Hora Comienzo 1er Tiempo</label>
                                    <input type="time" id="hora_comienzo_pt" class="form-control">
                                </td>
                                <td>
                                    <label for="">Hora Comienzo 2do Tiempo</label>
                                    <input type="time" id="hora_comienzo_st" class="form-control">
                                </td>
                                <td>
                                    <label for="">Veedor del Partido</label>
                                    <div style="border-style: outset;">
                                        <select name="" id="veedor_id" class="select2 form-control">
                                            @foreach ($delegados as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach                                    
                                        </select>
                                    <div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label for="">Arbitro del Partido</label>
                                    <div style="border-style: outset;">
                                        <select name="" id="juez_1" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>
                                    <div>
                                </td>
                                <td>
                                    <label for="">Primer juez del Partido</label>
                                    <div style="border-style: outset;">
                                        <select name="" id="juez_2" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>
                                    <div>
                                </td>
                                <td>
                                    <label for="">Segundo Juez del Partido</label>
                                    <div style="border-style: outset;">
                                        <select name="" id="juez_3" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>
                                    <div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="">Cuarto juez del Partido</label>
                                    <div style="border-style: outset;">
                                        <select name="" id="juez_4" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>
                                    <div>
                                </td>
                                <td>
                                    <div style="margin-top: 22px;">
                                        <button onclick="paso2()" class="btn btn-md btn-dark btn-block" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">>> Ir al segudno paso >> </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                      </div>
                    </div>
                </div>

                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <h3>2.- NOMINA DEL EQUIPO "A"</h3>
                      </button>
                    </h5>
                  </div>
                  <div id="collapseOne" class="collapse collapsed" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">    

                        <div class="col-sm-8">
                            <label for="">Seleciona un club para tu partido</label>
                            <div style="border-style: outset;">
                                <select name="" id="planilla_a_id" class="form-control select2">
                                    @foreach ($nomina  as $item)
                                        <option value="{{ $item->id }}">{{ $item->clubes->name }}</option>
                                    @endforeach                    
                                </select>
                            </div>
                            <br>
                            <label for="">NOMINA DE JUGADORES</label>
                            <table class="table table-striped table-bordered" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Polera</th>
                                        <th scope="col">Nombres y Apellidos</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">TA</th>
                                        <th scope="col">TR</th>
                                        <th scope="col">G1T</th>
                                        <th scope="col">G2T</th>
                                      </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Nombre del Delegado</label>
                                <div style="border-style: outset;">
                                    <select name="" id="delegado_a" class="form-control select2">
                                        {{-- <option value="">Elije un Delegado</option> --}}
                                        @foreach ($delegados as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach  
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Resultado Parcial</label>
                                <input type="number" id="rp1t_a" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label for="">Resultado Final</label>
                                <input type="number" id="rp2t_a" class="form-control" value="0">
                            </div>
                 
                            <div class="form-group">
                                <label for="">Evaluacion del Arbitro</label>
                                <select name="" id="evaluacion_a" class="form-control ">
                                    <option value="MALA">MALA</option>
                                    <option value="MALA">REGULAR</option>
                                    <option value="MALA">BUENA</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 22px;">
                                <button onclick="paso3()" class="btn btn-md btn-dark btn-block" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">>> Ir al segudno paso >> </button>
                            </div>
                        </div>

                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                      <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h3>3.- NOMINA DEL EQUIPO "B"</h3>
                      </button>
                    </h5>
                  </div>
                  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">

                        <div class="col-sm-8">
                            <label for="">Seleciona un club para tu partido</label>
                            <div style="border-style: outset;">
                                <select name="" id="planilla_b_id" class="form-control select2">
                                    {{-- <option value="">Elije una opcion</option> --}}
                                    @foreach ($nomina  as $item)
                                        <option value="{{ $item->id }}">{{ $item->clubes->name }}</option>
                                    @endforeach                    
                                </select>
                            </div>
                            <br>
                            <label for="">NOMINA DE JUGADORES</label>
                            <table class="table table-striped table-bordered" id="example2">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Polera</th>
                                        <th scope="col">Nombres y Apellidos</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">TA</th>
                                        <th scope="col">TR</th>
                                        <th scope="col">G1T</th>
                                        <th scope="col">G2T</th>
                                      </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Nombre del Delegado</label>
                                <div style="border-style: outset;">
                                    <select name="" id="delegado_b" class="form-control select2">
                                        {{-- <option value="">Elije un Delegado</option> --}}
                                        @foreach ($delegados as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach  
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Resultado Parcial</label>
                                <input type="number" id="rp1t_b" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label for="">Resultado Final</label>
                                <input type="number" id="rp2t_b" class="form-control" value="0">
                            </div>
                 
                            <div class="form-group">
                                <label for="">Evaluacion del Arbitro</label>
                                <select name="" id="delegado_b" class="form-control ">
                                    <option value="MALA">MALA</option>
                                    <option value="MALA">REGULAR</option>
                                    <option value="MALA">BUENA</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-top: 22px;">
                                <button onclick="paso4()" class="btn btn-md btn-dark btn-block" type="button" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">>> Ir al segudno paso >> </button>
                            </div>

                        </div>

                    </div>
                  </div>
                </div>
           
                <div class="card">
                    <div class="card-header" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                          <h3>4.- ENVIO DE FORMULARIO</h3>
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                      <div class="card-body">
  
                          <div class="col-sm-6">
                            {{-- <label for="">Ganador del Partido ?</label>
                            <select name="" id="ganador" class="form-control"></select>
                            <br> --}}
                            <label for="">Observaciones del Partido</label>
                            <textarea name="" id="description" cols="0" rows="0" class="form-control"></textarea>
                          </div>
  
                          <div class="col-sm-6">
                            {{-- <table class="table">
                                <tr>
                                    <td>
                                        <label for="">Goles Equipo "A"</label>
                                        <input type="number" id="tga" class="form-control" value="0">
                                    </td>
                                    <td>
                                        <label for="">Goles Equipo "B"</label>
                                        <input type="number"  id="tgb" class="form-control" value="0">
                                    </td>
                                </tr>
                            </table> --}}
                            <br>
                            <button class="btn btn-dark btn-block" onclick="misave()">Guardar Formulario</button>
                          </div>
  
                      </div>
                    </div>
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
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then(async (result) => {
                if (result.isConfirmed) {
                    var midata = {
                        'description': $("#description").val(),
                        'fecha': $("#fecha").val(),
                        'hora': $("#hora").val(),
                        'hora_comienzo_pt': $("#hora_comienzo_pt").val(),
                        'hora_comienzo_st': $("#hora_comienzo_st").val(),
                        'categoria': $("#categoria").val(),
                        'veedor_id': $("#veedor_id").val(),
                        'juez_1': $("#juez_1").val(),
                        'juez_2': $("#juez_2").val(),
                        'juez_3': $("#juez_3").val(),
                        'juez_4': $("#juez_4").val(),
                        'planilla_a_id': $("#planilla_a_id").val(),
                        'planilla_b_id': $("#planilla_b_id").val(),
                    }
                    // console.log(midata)
                    var partido = await axios.post("/api/partidos/save", midata)
                    $('#example tbody tr').each(async function(i){
                        var jugador_id,  aux1, aux2, aux3, aux4;
                        $(this).find('td').each(async function(j){
                            switch (j) {
                                case 0:
                                    jugador_id = $(this).text();
                                    break;
                                case 4:
                                    aux1 = $(this).text();
                                    break;
                                case 5:
                                    aux2 = $(this).text();
                                    break;
                                case 6:
                                    aux3 = $(this).text();
                                    break;
                                case 7:
                                    aux4 = $(this).text();
                                    break;
                                default:
                                    break;
                            }
                        })
                        var midata2 = {
                            'partido_id': partido.data.id,
                            'nomina_id': $("#planilla_b_id").val(),
                            'delegado_id': $("#delegado_b").val(),
                            'evaluacion': $("#evaluacion_b").val(),
                            'ta': aux1,
                            'tr': aux2,
                            'g1t': aux3,
                            'g2t': aux4,
                            'result1t': $("#rp1t_b").val(),
                            'result2t': $("#rp2t_b").val(), 
                            'jugador_id': jugador_id                  
                        }
                        console.log(midata2)
                        await axios.post("/api/partidos/rel/save", midata2)
                    })               
                    location.href = "/admin/partidos"
                }
            })
        }

        var example1 = new BSTable("example", {
			editableColumns:"4,5,6,7",
			onEdit:function() {
				console.log("EDITED");
			},
			advanced: {
				columnLabel: ''
			}
		});

        var example2 = new BSTable("example2", {
			editableColumns:"4,5,6,7",
			onEdit:function() {
				console.log("EDITED");
			},
			advanced: {
				columnLabel: ''
			}
		});

        $("#planilla_a_id").change(async function () { 
            var datos = await axios("/api/partidos/nomina/"+this.value)
            $("#example tbody tr").remove();
            for (let index = 0; index < datos.data.length; index++) {
                $('#example').append("<tr><td>"+datos.data[index].id+"</td><td>"+datos.data[index].jugador.polera+"</td><td>"+datos.data[index].jugador.name+"<br><label class=''>TITULAR</label></td><td>"+datos.data[index].jugador.edad+"</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>");
            }
            example1.init();
        });        

        $("#planilla_b_id").change(async function () { 
            var datos = await axios("https://roman.loginweb.dev/api/partidos/nomina/"+this.value)
            $("#example2 tbody tr").remove();
            for (let index = 0; index < datos.data.length; index++) {
                $('#example2').append("<tr><td>"+datos.data[index].id+"</td><td>"+datos.data[index].jugador.polera+"</td><td>"+datos.data[index].jugador.name+"<br><label class=''>TITULAR</label></td><td>"+datos.data[index].jugador.edad+"</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>");
            }
            example2.init();
        });  

        $('document').ready(function () {
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


            $('[data-toggle="tooltip"]').tooltip();
        });

        function paso2(){
            console.log('entro')
            $("#collapsemi").collapse('hide');
        }
        function paso3(){
            console.log('entro')
            $("#collapseOne").collapse('hide');
        }
        
        function paso4(){
            console.log('entro')
            $("#collapseTwo").collapse('hide');
        }
    </script>
@stop
