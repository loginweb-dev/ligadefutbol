@extends('voyager::master')

@php
    $a = App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->planilla_a_id)->with('jugador')->get();
    $b =App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->planilla_b_id)->with('jugador')->get();
    $ea = App\Clube::find($a[0]->jugador->clube_id);
    $eb = App\Clube::find($b[0]->jugador->clube_id);

    $nomina = App\JugadoresPlanilla::where('activo', true)->with('clubes')->get();
    $arbitro = App\Arbitro::all();
    $delegados = App\Delegado::all();
    $veedor = App\Delegado::find($dataTypeContent->veedor_id);

    $relparnom_a = App\RelPartidoNomina::where('partido_id', $dataTypeContent->id)->where('nomina_id', $dataTypeContent->planilla_a_id)->get();
    $relparnom_b = App\RelPartidoNomina::where('partido_id', $dataTypeContent->id)->where('nomina_id', $dataTypeContent->planilla_b_id)->get();

@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-sm-3">
            
                <table class="table mitable">
                    <thead>
                        <tr class="active">
                            <th class="text-center" colspan="2">Datos del Partido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-center">Juego #{{ $dataTypeContent->id }}</th>
                            <td  class="text-center">
                                @switch($dataTypeContent->status)
                                    @case(1)
                                        <span class="label label-success">Registrado</span>
                                        @break
                                    @case(2)
                                        <span class="label  label-primary">Finalizo</span></h2>  
                                        @break
                                    @case(3)
                                        <span class="badge badge-danger">Se Cancelo</span>
                                        @break
                                    @case(4)
                                        <span class="badge badge-warning">Empate</span>
                                        @break
                                    @default         
                                                                   
                                @endswitch                                                                 
                                                              
                            </td>
                        </tr>
                        <tr>
                            <td>Fecha & Hora: </td>
                            <td>{{ $dataTypeContent->created_at }}</td>
                        </tr>
                        <tr>
                            <td>Categoria: </td>
                            <td>{{ $dataTypeContent->categoria }}</td>
                        </tr>
                        <tr>
                            <td>Veedor: </td>
                            <td>{{ $veedor->name }}</td>
                        </tr>
                    </tbody>
                </table>


                @if($dataTypeContent->status == 1)                  
                    <table class="table">
                        <thead>
                            <tr class="active">
                                <th class="text-center" colspan="2">Formulaio de Resultados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">                               
                                    <label for="">Arbitro del Partido</label>  
                                    <div class="form-group miselect">                              
                                        <select name="" id="juez_1" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label for="">Primer juez del Partido</label>
                                    <div class="form-group miselect">                                       
                                        <select name="" id="juez_2" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>                   
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label for="">Segundo Juez del Partido</label>
                                    <div class="form-group miselect">                                     
                                        <select name="" id="juez_3" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>                  
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <label for="">Cuarto juez del Partido</label>
                                    <div class="form-group miselect">
                                        <select name="" id="juez_4" class="form-control select2">
                                            @foreach ($arbitro as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach    
                                        </select>                  
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <label for="">Inicio 1er Tiempo</label>   
                                    <input type="time" id="hora_comienzo_pt" class="form-control">
                                    <label for="">Hora 2do Tiempo</label>
                                    <input type="time" id="hora_comienzo_st" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                               
                                        <a href="#" onclick="misave()" class="btn btn-sm btn-dark btn-block">Enviar y Guardar</a>
                                   
                                            
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                   
                                        @if($isSoftDeleted)
                                            <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-sm btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                                            </a>
                                        @else
                                            <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger btn-sm btn-block delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                                            {{ __('voyager::generic.delete') }}
                                            </a>
                                        @endif
                                        <div hidden>
                                            <input type="text" id="miid" class="form-control" value="{{ $dataTypeContent->id }}" hidden />
                                        </div>
                                   
                                </td>
                            </tr>
                        </tbody>
                    </table>                                                    





     




           
                @else  

                    @php
                                        
                        $arb1 =  App\Arbitro::find($dataTypeContent->juez_1);
                        $arb2 =  App\Arbitro::find($dataTypeContent->juez_2);
                        $arb3 =  App\Arbitro::find($dataTypeContent->juez_3);
                        $arb4 =  App\Arbitro::find($dataTypeContent->juez_4);
                    @endphp
                    <table class="table mitable">
                        <thead>
                            <tr class="active">
                                <th class="text-center" colspan="2">Resultados del Partido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>                      
                                <td colspan="2" class="text-center"><span class="label label-success">{{ $eb->name }} - {{ $dataTypeContent->ganador }}</span></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><span class="label label-success">{{ $ea->name }} - {{ $dataTypeContent->ganador }} </span></td>
                            </tr>
                            <tr>
      
                                <td colspan="2" class="text-center"><span class="label label-info">{{ $dataTypeContent->description }} </span></td>
                            </tr>
                            
                            <tr>
                                <td>Arbitro del Partido</td>
                                <td>{{ $arb1->name }}</td>
                            </tr>
                            <tr>
                                <td>Primer Juez</td>
                                <td>{{ $arb2->name }}</td>
                            </tr>
                            <tr>
                                <td>Segundo Juez</td>
                                <td>{{ $arb3->name }}</td>
                            </tr>
                            <tr>
                                <td>Tercer Juez</td>
                                <td>{{ $arb4->name }}</td>
                            </tr>
                            <tr>
                                <td>Tercer Juez</td>
                                <td>{{ $arb4->name }}</td>
                            </tr>
                            <tr>
                                <td>Inicio 1er Tiempo</td>
                                <td>{{ $dataTypeContent->hora_comienzo_pt }}</td>
                            </tr>
                            <tr>
                                <td>Inicio 2do Tiempo</td>
                                <td>{{ $dataTypeContent->hora_comienzo_st }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="col-sm-9">

                <div class="table-responsive">
                    <table class="table table-striped mitable" id="cluba">
              
                        <thead>
                            <tr class="active"><th class="text-center" colspan="8"><span class="label label-primary">Equipo A: {{ $ea->name }}</span></th></tr>
                            <tr class="active">
                                <th scope="col">ID</th>                                                                
                                <th scope="col">Nombres y Apellidos</th>
                                <th scope="col">#</th>
                                <th scope="col">Edad</th>
                                <th scope="col">TA</th>
                                <th scope="col">TR</th>
                                <th scope="col">G1T</th>
                                <th scope="col">G2T</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($a as $item)
                                <tr>
                                    <td><span class="label label-info">{{ $item->jugador->id }}</span></td>
                                    <td>
                                        <a href="/admin/jugadores/{{  $item->jugador->id }}">
                                            {{ $loop->index + 1 }}.- {{ $item->jugador->name }} 
                                        </a>
                                    </td>
                                    <td><span class="label label-warning">{{ $item->jugador->polera }}</span></td>           
                                    <td><span class="label label-success">{{ $item->jugador->edad }}</span></td>                                        
                                    <td>
                                        @if($dataTypeContent->status == 1)
                                            0 
                                        @else 
                                            @if ($relparnom_a[$loop->index]->ta == 0)
                                                0
                                            @else
                                                <span class="label label-primary">{{ $relparnom_a[$loop->index]->ta }}</span>
                                            @endif                                            
                                        @endif
                                    </td>  
                                    <td>
                                        @if($dataTypeContent->status == 1)
                                            0 
                                        @else 
                                            @if ($relparnom_a[$loop->index]->tr == 0)
                                                0
                                            @else
                                                <span class="label label-primary">{{ $relparnom_a[$loop->index]->tr }}</span>
                                            @endif                                            
                                        @endif
                                    </td>                                                      
                                    <td>
                                        @if($dataTypeContent->status == 1)
                                            0 
                                        @else 
                                            @if ($relparnom_a[$loop->index]->g1t == 0)
                                                0
                                            @else
                                                <span class="label label-primary">{{ $relparnom_a[$loop->index]->g1t }}</span>
                                            @endif                                            
                                        @endif
                                    </td>                            
                                    <td>               
                                        @if($dataTypeContent->status == 1)
                                            0 
                                        @else 
                                            @if ($relparnom_a[$loop->index]->g2t == 0)
                                                0
                                            @else
                                                <span class="label label-primary">{{ $relparnom_a[$loop->index]->g2t }}</span>
                                            @endif                                            
                                        @endif
                                    </td>  
                                </tr>
                            @endforeach
                            {{-- <tr>
                                <td colspan="4" style="text-align:right"><span class="label label-danger">Totales: </span></td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>

                
                    @if($dataTypeContent->status == 1)     
                        <div class="form-group">               
                            <textarea name="" id="description" rows="4" class="form-control" placeholder="Detalles u Observaciones del partido"></textarea>                    
                        </div>
                    @else
                    @endif
          
          

                <div class="table-responsive">
                    <table class="table table-striped mitable" id="clubb">
                        <thead>
                            <tr class="active"><th class="text-center" colspan="8"><span class="label label-primary">Equipo B: {{ $eb->name }}</span></th></tr>
                            <tr class="active">
                                <th scope="col">ID</th>                                                                
                                <th scope="col">Nombres y Apellidos</th>
                                <th scope="col">#</th>
                                <th scope="col">Edad</th>
                                <th scope="col">TA</th>
                                <th scope="col">TR</th>
                                <th scope="col">G1T</th>
                                <th scope="col">G2T</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($b as $item)
                                <tr>
                                    <td><span class="label label-info">{{ $item->jugador->id }}</span></td>
                                    <td>
                                        <a href="#">
                                            {{ $loop->index + 1 }}.- {{ $item->jugador->name }} 
                                        </a>
                                    </td>
                                    <td><span class="label label-warning">{{ $item->jugador->polera }}</span></td>                                                                        
                                    <td><span class="label label-success">{{ $item->jugador->edad }}</span></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>
                                        @if($dataTypeContent->status == 1)
                                            0 
                                        @else 
                                            @if ($relparnom_b[$loop->index]->g1t == 0)
                                                0
                                            @else
                                                <span class="label label-primary">{{ $relparnom_b[$loop->index]->g1t }}</span>
                                            @endif                                            
                                        @endif
                                    </td>                            
                                    <td>               
                                        @if($dataTypeContent->status == 1)
                                            0 
                                        @else 
                                            @if ($relparnom_b[$loop->index]->g2t == 0)
                                                0
                                            @else
                                                <span class="label label-primary">{{ $relparnom_b[$loop->index]->g2t }}</span>
                                            @endif                                            
                                        @endif
                                    </td>  
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {

            var now = new Date(Date.now());
            var mitime = now.getHours() + ":" + now.getMinutes();
            $('#hora_comienzo_pt').val(mitime);
            $('#hora_comienzo_st').val(mitime);

        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })


        @if($dataTypeContent->status == 1)     

            var example1 = new BSTable("cluba", {
                editableColumns:"4,5,6,7",
                onEdit:function() {
                    console.log("EDITED")
                    totales()
                },
                advanced: {
                    columnLabel: ''
                }
            });
            example1.init();

            var example2 = new BSTable("clubb", {
                editableColumns:"4,5,6,7",
                onEdit:function() {
                    console.log("EDITED");
                },
                advanced: {
                    columnLabel: ''
                }
            });
            example2.init();

        @else  

        @endif

        function totales(){
            
            //recorrer equipoa--------------------------------------
            var equipoa = document.getElementById("cluba")
            var ta = 0
            var tr = 0
            var g1t = 0
            var g2t = 0
            for (var i = 2, row; row = equipoa.rows[i]; i++) {
                ta += parseInt(row.cells[4].innerText)
                tr += parseInt(row.cells[5].innerText)
                g1t += parseInt(row.cells[6].innerText)
                g2t += parseInt(row.cells[7].innerText)
                console.log(parseInt(row.cells[4].innerText))
            }
            console.log(ta)
        }
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

        async function misave() {
            // totales()
            Swal.fire({
                title: 'Estás Seguro?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then(async (result) => {
                if (result.isConfirmed) {
                    var midata = {
                        'miid': $("#miid").val(),
                        'juez_1': $("#juez_1").val(),
                        'juez_2': $("#juez_2").val(),
                        'juez_3': $("#juez_3").val(),
                        'juez_4': $("#juez_4").val(),
                        'hora_comienzo_pt': $("#hora_comienzo_pt").val(),
                        'hora_comienzo_st': $("#hora_comienzo_st").val(),
                        'description': $("#description").val(),
                    }
                    // console.log(midata)      
                    var partido = await axios.post("/api/partidos/update", midata)

                    //recorrer equipoa--------------------------------------
                    var equipoa = document.getElementById("cluba")
                    var partido_id = "{{ $dataTypeContent->id }}"
                    var nomina_a_id = "{{ $dataTypeContent->planilla_a_id }}"
                    for (var i = 2, row; row = equipoa.rows[i]; i++) {
                        var midataa = {
                            'partido_id': partido_id,
                            'nomina_id': nomina_a_id,
                            'ta': row.cells[4].innerText,
                            'tr': row.cells[5].innerText,
                            'g1t': row.cells[6].innerText,
                            'g2t': row.cells[7].innerText,
                            'jugador_id': row.cells[0].innerText                
                        }
                        // console.log(midataa)
                        await axios.post("/api/partidos/rel/save", midataa)
                    }

                    //recorrer equipob-------------------------------------
                    var equipob = document.getElementById("clubb");
                    var nomina_b_id = "{{ $dataTypeContent->planilla_b_id }}"
                    for (var i = 2, row; row = equipob.rows[i]; i++) {
                        var midatab = {
                            'partido_id': partido_id,
                            'nomina_id': nomina_b_id,
                            'ta': row.cells[4].innerText,
                            'tr': row.cells[5].innerText,
                            'g1t': row.cells[6].innerText,
                            'g2t': row.cells[7].innerText,
                            'jugador_id': row.cells[0].innerText                
                        }
                        // console.log(midataa)
                        await axios.post("/api/partidos/rel/save", midatab)
                    }

                    // actualiar puntos------------------------------------     
                    await axios.post("/api/partidos/update/clube", {'partido_id': partido_id})

                      // crear asientos------------------------------------     
                      await axios.post("/api/partidos/update/clube", {'partido_id': partido_id})

                    location.reload()             
                }
            })
        }


    </script>
@stop
