@extends('voyager::master')
@php
    $partidos = App\Partido::where("fixture_id", $dataTypeContent->id)->with("fixture", "planilla_a", "planilla_b")->get();
    $fixture = App\Fixture::find($dataTypeContent->id);
    $clube=App\Clube::find($dataTypeContent->descansa_id);
    $miuser = App\Models\User::find($dataTypeContent->user_id);
@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <br>
            <div class="col-sm-3">
                <table class="table mitable">
                        <tr class="active">
                            <td colspan="2" class="text-center"><strong>Datos del Fixture - </strong> <span class="label label-primary"> #{{ $fixture->id }}</span></td>
                        </tr>
                        <tr>
                            {{-- <td class="text-center"><strong class="text-center">Titulo: </strong></td> --}}
                            <td class="text-center" colspan="2">
                                {{ $fixture->title }}
                                <br>
                                <span class="label label-primary">Titulo</span>
                            </td>
                        </tr>
                        <tr>
                            {{-- <td><strong>Descansa: </strong></td> --}}
                            <td class="text-center" colspan="2">
                                {{-- {{ $descansa->clubes->name }} --}}
                                {{$clube->name}}
                                <br>
                                <span class="label label-primary">Descansa</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Editor: </strong></td>
                            <td>{{ $miuser->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Creado: </strong></td>
                            <td>{{ $fixture->created_at }}</td>
                        </tr>          
                </table>     
                {{-- @if($isSoftDeleted)
                    <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                        <i class="voyager-trash"></i> <span class="hidden-xs btn-sm btn-block">{{ __('voyager::generic.restore') }}</span>
                    </a>
                @else
                    <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger btn-block delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                        <i class="voyager-trash"></i> <span class="">{{ __('voyager::generic.delete') }}</span>
                    </a>
                @endif          --}}
            </div>

            <div class="col-sm-9">
                <div class="table-responsive">
                    <table class="table mitable">
                        <thead>
                            <tr class="active">
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Equipo A</th>
                                <th>-</th>
                                <th>Equipo B</th>
                                {{-- <th>Categoria</th> --}}
                                {{-- <th>Veedor</th> --}}
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partidos as $item)
                                @php
                                    $club_a = App\Clube::find($item->planilla_a->clube_id);
                                    $club_b = App\Clube::find($item->planilla_b->clube_id);
                                    $veedor = App\Delegado::find($item->veedor_id);
                                @endphp
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->fecha }}</td>
                                    <td><span class="label label-warning">{{ $item->hora }}</span></td>
                                    <td>
                                        <a href="/admin/clubes/{{ $club_a->id }}">
                                            {{ $club_a->name  }}
                                        </a>                                       
                                    </td>
                                    <td><span class="label label-primary">vs</span></td>
                                    <td>
                                        <a href="/admin/clubes/{{ $club_b->id }}">
                                            {{ $club_b->name  }}
                                        </a>    
                                    </td>
                                    {{-- <td>{{ $item->categoria }}</td> --}}
                                    {{-- <td>{{ $veedor->name }}</td> --}}
                                    <td><a href="/admin/partidos/{{ $item->id }}" class="btn btn-sm btn-block btn-dark">Ver</a></td>
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
