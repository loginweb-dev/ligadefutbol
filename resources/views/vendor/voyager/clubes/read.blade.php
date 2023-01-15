@extends('voyager::master')

@php
    $jugadores = App\Jugadore::where('clube_id', $dataTypeContent->id)->with("temporadas")->get();
    $temporada = App\RelTemporadaClube::where('club_id', $dataTypeContent->id)->first();
    $tem = App\Temporada::where('id', $temporada->temporada_id)->first();
    $delegados = App\Delegado::where('clube_id', $temporada->id)->get();
    $user = App\Models\User::find($dataTypeContent->user_id);
@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-sm-8">        
                <table class="table mitable">
                    <thead>
                        <tr class="active">                       
                            <th>ID</th>
                            <th>Jugador</th>
                            <th>Edad</th>
                            <th>Polera</th>
                            <th>🟨</th>
                            <th>🟥</th>
                            <th>⚽</th>
                            <th>💬</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jugadores as $item)
                            <tr>
                                <td><span class="label label-primary">{{ $item->id }}</span></td>
                                <td>
                                    <a href="/admin/jugadores/{{ $item->id }}/edit">
                                        {{ $loop->index + 1 }}.- {{ $item->name }} 
                                    </a>
                                </td>
                                <td><span class="label label-success">{{ $item->edad }}</span></td>
                                <td><span class="label label-warning">{{ $item->polera }}</span></td>
                                <td>{{ $item->temporadas ? $item->temporadas->ta : 0 }}</td>
                                <td>{{ $item->temporadas ? $item->temporadas->tr : 0 }}</td>
                                <td>{{ $item->temporadas ? $item->temporadas->goles : 0 }}</td>
                                <td>{{ $item->phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>            
            </div>
            <div class="col-sm-4">   
                <table class="table mitable">
                    <tr class="active">
                        <td colspan="2" class="text-center">{{ $dataTypeContent->name }}</td>    
                    </tr>  
                    <tr>
                        <td class="text-center" colspan="2">
                            {{ $user->name }}
                            <br>
                            <span class="label label-primary">Usuario</span>
                        </td>    
                    </tr>   
                    <tr>
                        <td class="text-center" colspan="2">
                            {{ $dataTypeContent->presidente }}
                            <br>
                            <span class="label label-primary">Presidente</span>
                        </td>    
                    </tr>   
                    <tr>
                        <td class="text-center" colspan="2">
                            {{ $tem->title }}
                            <br>
                            <span class="label label-primary">temporada</span>
                        </td>    
                     
                    </tr>  
                    <tr>
                        <td class="text-center" colspan="2">
                            +591 {{ $dataTypeContent->wpp }}
                            <br>
                            <span class="label label-primary">Whatsapp</span>
                        </td>                          
                    </tr> 

                    <tr>
                        <td>Total Puntos: </td>
                        <td><span class="label label-primary">{{ $temporada->puntos }}</span></td>    
                    </tr> 
                    <tr>
                        <td>Partidos Jugados: </td>
                        <td><span class="label label-primary">{{ $temporada->partidos }}</span></td>    
                    </tr> 
                    <tr>
                        <td>Partidos Descansos: </td>
                        <td><span class="label label-primary">{{ $temporada->descansos }}</span></td>    
                    </tr> 
                    <tr>
                        <td>Total 🟨: </td>
                        <td><span class="label label-primary">{{ $temporada->ta }}</span></td>    
                    </tr> 
                    <tr>
                        <td>Total 🟥: </td>
                        <td><span class="label label-primary">{{ $temporada->tr }}</span></td>    
                    </tr> 

                    <tr>
                        <td>Goles ⚽++: </td>
                        <td><span class="label label-primary">{{ $temporada->golesa }}</span></td>    
                    </tr> 
                    <tr>
                        <td>Goles ⚽--: </td>
                        <td><span class="label label-primary">{{ $temporada->golesc }}</span></td>    
                    </tr> 
                    <tr>
                        <td class="text-center" colspan="2">
                            <ul class="list-group">
                                @foreach ($delegados as $item)
                                    <li class="list-group-item">{{ $loop->index+1 }}.- {{ $item->name }}</li>
                                @endforeach                               
                            </ul>
                            <span class="label label-primary">Delegados</span>
                        </td>    
                    </tr>
                </table>       
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
