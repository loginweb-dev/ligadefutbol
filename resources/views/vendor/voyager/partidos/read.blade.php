@extends('voyager::master')

@php
    $a = App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->planilla_a_id)->with('jugador')->get();
    $b =App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->planilla_b_id)->with('jugador')->get();
    $ea = App\Clube::find($a[0]->jugador->clube_id);
    $eb = App\Clube::find($b[0]->jugador->clube_id);

    $nomina = App\JugadoresPlanilla::where('activo', true)->with('clubes')->get();
    $arbitro = App\Arbitro::all();
    $delegados = App\Delegado::all();
@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">

            <div class="col-sm-9">
                <h2> {{ $ea->name }}</h2>
                <div class="table-responsive">
                    <table class="table table-striped mitable" id="cluba">
                        <thead>
                            <tr class="active">
                                <th scope="col">#</th>
                                <th scope="col">P</th>
                                <th scope="col">Nombres y Apellidos</th>
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
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->jugador->polera }}</td>
                                    <td>{{ $item->jugador->name }}</td>
                                    <td>{{ $item->jugador->edad }}</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h2> {{ $eb->name }}</h2>

                <div class="table-responsive">
                    <table class="table table-striped mitable" id="clubb">
                        <thead>
                            <tr class="active">
                                <th scope="col">#</th>
                                <th scope="col">P</th>
                                <th scope="col">Nombres y Apellidos</th>
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
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->jugador->polera }}</td>
                                    <td>{{ $item->jugador->name }}</td>
                                    <td>{{ $item->jugador->edad }}</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="col-sm-3">
                <br>
                <div class="form-group">
                    <a href="#" onclick="misave()" class="btn btn-sm btn-dark btn-block">Enviar y Guardar</a>
                </div>
                <table class="table mitable">
                    <thead>
                        <tr class="active">
                            <td>Juego #{{ $dataTypeContent->id }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
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
                            <td>{{ $dataTypeContent->veedor_id }}</td>
                        </tr>
                    </tbody>
                </table>

                <label for="">Arbitro del Partido</label>  
                <div class="form-group miselect">                              
                    <select name="" id="juez_1" class="form-control select2">
                        @foreach ($arbitro as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach    
                    </select>
                </div>

                <label for="">Primer juez del Partido</label>
                <div class="form-group miselect">                                       
                    <select name="" id="juez_2" class="form-control select2">
                        @foreach ($arbitro as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach    
                    </select>                   
                </div>

                <label for="">Segundo Juez del Partido</label>
                <div class="form-group miselect">                                     
                    <select name="" id="juez_3" class="form-control select2">
                        @foreach ($arbitro as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach    
                    </select>                  
                </div>

                <label for="">Cuarto juez del Partido</label>
                <div class="form-group miselect">
                    <select name="" id="juez_4" class="form-control select2">
                        @foreach ($arbitro as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach    
                    </select>                  
                </div>

                <label for="">Inicio 1er Tiempo</label>   
                <input type="time" id="hora_comienzo_pt" class="form-control">
                <label for="">Hora 2do Tiempo</label>
                <input type="time" id="hora_comienzo_st" class="form-control">

                <div class="form-group">
                    @if($isSoftDeleted)
                        <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-sm btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                        </a>
                    @else
                        <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger btn-sm btn-block delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                            <i class="voyager-trash"></i> <span class="">{{ __('voyager::generic.delete') }}</span>
                        </a>
                    @endif
                </div> 
                <div hidden>
                    <input type="text" id="miid" class="form-control" value="{{ $dataTypeContent->id }}" hidden />
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
        var now = new Date(Date.now());
        var mitime = now.getHours() + ":" + now.getMinutes();
        $('#hora_comienzo_pt').val(mitime);
        $('#hora_comienzo_st').val(mitime);


        var example1 = new BSTable("cluba", {
			editableColumns:"4,5,6,7",
			onEdit:function() {
				console.log("EDITED");
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
                        'hora_comienzo_st': $("#hora_comienzo_st").val()
                    }
                    console.log(midata)     
                    var partido = await axios.post("/api/partidos/save", midata)
                    location.href = "/admin/partidos"              
                }
            })
        }


    </script>
@stop
