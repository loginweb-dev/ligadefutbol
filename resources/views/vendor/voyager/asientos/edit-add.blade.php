@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());

    $nomina = App\JugadoresPlanilla::where('activo', true)->with('clubes')->get();
    $jugadores = App\Jugadore::all();
    $clubes = App\Clube::all();
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    
                    
                    <div class="row">
                 
                        <div class="col-md-12">
                           

                            
                            <div class="col-md-6 form-group">
                                <label for="select_tipo">Tipo</label>
                                <div style="border-style: outset;">                                
                                    <select class="form-control select2" name="select_tipo" id="select_tipo">
                                        <option value="Ingreso">Ingreso</option>
                                        <option value="Egreso">Egreso</option> 
 
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="select_detalle">Detalle</label>
                                <div style="border-style: outset;">                                

                                    <select class="form-control select2" name="select_detalle" id="select_detalle">
                                        
 
                                    </select>
                                </div>
                            </div>
                            <br>

                           


                            <div class="col-md-4 form-group">
                                <label for="select_planilla">Planilla</label>
                                <div style="border-style: outset;">                                

                                    <select class="form-control select2" name="select_planilla" id="select_planilla">
                                        @foreach ($nomina  as $item)
                                            <option value="{{ $item->id }}">{{ $item->clubes->name }} - {{$item->categoria_jugadores}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="select_jugador">Jugador</label>
                                <div style="border-style: outset;">                                
                                    <select class="form-control select2" name="select_jugador" id="select_jugador">
                                        @foreach ($jugadores  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="select_club">Club</label>
                                <div style="border-style: outset;">                                
                                    <select class="form-control select2" name="select_club" id="select_club">
                                        @foreach ($clubes  as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="text_descripcion">Descripción</label>
                                <textarea class="form-control" name="text_descripcion" id="text_descripcion" rows="5"></textarea>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="input_monto">Monto</label>
                                <div style="border-style: outset;">                               
                                    <input  id="input_monto" type="number" min="1" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <br>
                                <div>
                                    <button class="btn btn-dark btn-block" onclick="misave()">Guardar Asiento</button>
                                </div>
                            </div>

                            <div class="col-md-6 form-group" hidden>
                                <label for="input_editor">Editor</label>
                                <div style="border-style: outset;">
                                    <input class="form-control" id="input_editor" type="number">
                                </div>
                            </div>

                          


                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>

        async function cargar_detalle(tipo) {
            $('#select_detalle').find('option').remove().end()
            var detalles=""
            if (tipo=="Ingreso") {
                detalles= [{id:1, value:"Mensualidades"}, {id:2, value:"Arbitraje"}, {id:3, value:"Tarjetas Amarillas"}, {id:4, value:"Wal Kolbert"}, {id:5, value:"Multas por Faltas a Reunión"}, {id:6, value:"Multas o Sanciones"}, {id:7, value:"Otros Ingresos"}, {id:8, value:"Mortuoria"}, {id:9, value:"Pago de Colaboración"}]
                for (let index = 0; index < detalles.length; index++) {
                    $('#select_detalle').append($('<option>', {
                        value: detalles[index].value,
                        text: detalles[index].value
                    }));
                }
            }
            else{
                detalles= [{id:1, value:"Arbitro"}, {id:2, value:"Pasa Pelotas"}, {id:3, value:"Médico"}, {id:4, value:"Cobrador"}, {id:5, value:"Planillero"}, {id:6, value:"Veedor"}]
                for (let index = 0; index < detalles.length; index++) {
                    $('#select_detalle').append($('<option>', {
                        value: detalles[index].value,
                        text: detalles[index].value
                    }));
                }
            }

        }

        async function misave() {
            midata={
                tipo:$("#select_tipo").val(),
                detalle:$("#select_detalle").val(),
                monto:$("#input_monto").val(),
                editor_id:$("#input_editor").val(),
                planilla_id:$("#select_planilla").val(),
                clube_id:$("#select_club").val(),
                jugador_id:$("#select_jugador").val(),
                observacion:$("#text_descripcion").val(),
                estado:"Pagado",
                monto_pagado: $("#input_monto").val(),
                monto_restante: 0
            }
            var asiento= await axios.post("{{setting('admin.url')}}api/asiento/save", midata)

            if (asiento.data) {
                location.href="{{setting('admin.url')}}admin/asientos"
            }
        }

        $("#select_tipo").change(async function () { 
           cargar_detalle(this.value)                         
        })
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

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

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();

            cargar_detalle("Ingreso")
            $("#input_editor").val("{{Auth::user()->id}}")                         
        });
    </script>
@stop
