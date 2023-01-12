@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))


@section('content')
    @php
        $sin_perfil='jugadores/jugadordefault.png';
        $jugadore=App\Jugadore::where('id', $dataTypeContent->id)->with('clubes')->first();
    @endphp
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    {{-- <h1 class="text-center">Jugador: {{$dataTypeContent->name}}</h1> --}}
                    <div class="row">
                        <div class="col-sm-4">
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
                                            <td>Nombre</td>
                                            <td>{{$dataTypeContent->name}} <br> hola prueba</td>
                                        </tr>
                                        <tr>
                                            <td># Polera</td>
                                            <td>{{$dataTypeContent->polera}}</td>
                                        </tr>
                                        <tr>
                                            <td>Edad</td>
                                            <td>{{$dataTypeContent->edad}}</td>
                                        </tr>
                                            
                                        <tr>
                                            <td >Fecha Nacimiento</td>
                                            <td colspan="2">{{$dataTypeContent->nacimiento}}</td>
                                        </tr>
                                        <tr>
                                            <td >Categoria</td>
                                            <td colspan="2">{{$dataTypeContent->jug_categoria}}</td>
                                        </tr>
                                        <tr>
                                            <td >Club Actual</td>
                                            <td colspan="2">{{$jugadore->clubes->name}}</td>
                                        </tr>
                                  
                                        <tr>
                                            <td>WhatsApp</td>
                                            <td colspan="2">{{$dataTypeContent->phone}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- <img src="@if( !filter_var($dataTypeContent->foto, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->foto ) }}@else{{ $dataTypeContent->foto }}@endif" style="width:500px"> --}}

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div  class="table-responsive">
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center" colspan="6"><h4>Trayectoria</h4></th>
                                        </tr>
                                        <tr>
                                            <th>Equipo</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Finalización</th>
                                            <th>Goles</th>
                                            <th>Tarjetas Amarillas</th>
                                            <th>Tarjetas Rojas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Equipo1</td>
                                            <td>10-01-2020</td>
                                            <td>10-11-2020</td>
                                            <td>20</td>
                                            <td>4</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>Equipo2</td>
                                            <td>10-01-2021</td>
                                            <td>10-11-2022</td>
                                            <td>12</td>
                                            <td>8</td>
                                            <td>2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <div class="col-sm-4">
                            <div  class="table-responsive">
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-center" colspan="4"><h4>Transferencias</h4></th>
                                        </tr>
                                        <tr>
                                            <th>Equipo Inicial</th>
                                            <th>Equipo Final</th>
                                            <th>Fecha</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Equipo1</td>
                                            <td>Equipo2</td>
                                            <td>10-11-2020</td>
                                            <td>1000Bs</td>
                                        </tr>
                                        <tr>
                                            <td>Equipo2</td>
                                            <td>Equipo3</td>
                                            <td>10-11-2020</td>
                                            <td>900</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
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
    @if ($isModelTranslatable)
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
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script>
@stop
