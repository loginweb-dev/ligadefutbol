@extends('voyager::master')

@php
    $jugadores = App\Jugadore::where('clube_id', $dataTypeContent->id)->get();
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
                            <th>Whatsapp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jugadores as $item)
                            <tr>
                                <td><span class="label label-primary">{{ $item->id }}</span></td>
                                <td>
                                    <a href="/admin/jugadores/{{ $item->id }}">
                                        {{ $loop->index + 1 }}.- {{ $item->name }} 
                                    </a>
                                </td>
                                <td><span class="label label-success">{{ $item->edad }}</span></td>
                                <td><span class="label label-warning">{{ $item->polera }}</span></td>
                                <td>{{ $item->phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>            
            </div>
            <div class="col-sm-4">   
                <table class="table mitable">
                    <tr class="active">
                        <td>Nombre: </td>
                        <td>{{ $dataTypeContent->name }}</td>    
                    </tr>    
                    <tr>
                        <td>whatsaoo: </td>
                        <td>{{ $dataTypeContent->wpp }}</td>    
                    </tr> 
                    <tr>
                        <td>Presidente: </td>
                        <td>{{ $dataTypeContent->presidente }}</td>    
                    </tr> 
                    <tr>
                        <td>Total TA: </td>
                        <td>{{ $dataTypeContent->ta }}</td>    
                    </tr> 
                    <tr>
                        <td>Total TR: </td>
                        <td>{{ $dataTypeContent->tr }}</td>    
                    </tr> 
                    <tr>
                        <td>Total Puntos: </td>
                        <td>{{ $dataTypeContent->puntos }}</td>    
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
