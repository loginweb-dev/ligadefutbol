@extends('voyager::master')

@php
    $a = App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->planilla_a_id)->with('jugador')->get();
    $b =App\RelPlanillaJugadore::where('planilla_id', $dataTypeContent->planilla_b_id)->with('jugador')->get();
@endphp

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;

        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan
        {{-- @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan --}}
        @can('browse', $dataTypeContent)
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-sm btn-dark">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
        </a>
        @endcan
        <a href="#" class="btn btn-sm btn-success">
            <i class="glyphicon glyphicon-send"></i> <span class="hidden-xs hidden-sm">Enviar</span>
        </a>
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h2> Planilla Equipo A</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="cluba">
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
                        <tbody>
                            @foreach ($a as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->jugador->polera }}</td>
                                    <td>{{ $item->jugador->name }}</td>
                                    <td>{{ $item->jugador->edad }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <h2> Planilla Equipo B</h2>
                {{ $b }}
                {{ $dataTypeContent->planilla_b_id }}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="clubb">
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
                        <tbody>
                            @foreach ($b as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->jugador->polera }}</td>
                                    <td>{{ $item->jugador->name }}</td>
                                    <td>{{ $item->jugador->edad }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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

    </script>
@stop
