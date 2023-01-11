@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))


@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <h1 class="text-center">Datos del Jugador: {{$dataTypeContent->name}}</h1>
                    <div class="row">
                        <div class="col-sm-6">
                            {{-- <div  class=" table-responsive"> --}}
                                <table class="table mitable">
                                    <thead>
                                        <tr class="active">
                                            <th colspan="2">Datos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Nombre</td>
                                            <td>{{$dataTypeContent->name}}</td>
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
                                            <td>Fecha Nacimiento</td>
                                            <td>{{$dataTypeContent->nacido}}</td>
                                        </tr>
                                        <tr>
                                            <td>Categoria</td>
                                            <td>{{$dataTypeContent->jug_categoria}}</td>
                                        </tr>
                                        <tr>
                                            <td>Club Actual</td>
                                            <td>{{$dataTypeContent->clube_id}}</td>
                                        </tr>
                                        <tr>
                                            <td>Foto</td>
                                            <td>{{$dataTypeContent->foto}}</td>
                                        </tr>
                                        <tr>
                                            <td>WhatsApp</td>
                                            <td>{{$dataTypeContent->phone}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            {{-- </div> --}}
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
