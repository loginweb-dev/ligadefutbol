@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    $equipos = App\Clube::all();
    $delegados = App\Delegado::all();
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    {{-- <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector') --}}
    <div class="col-sm-offset-2  col-sm-8">
        <div class="text-center">
            <h2 class="">FORMULARIO DE NUEVO FIXTURE</h2>
            {{-- <H4>MUTUAL DE EX JUFADORES DE FUTBOL TRINIDAD</H4> --}}
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-sm-5">
                <label for="">Titulo</label>
                <textarea class="form-control" name="" id="" cols="" rows="4"></textarea>
            </div>
            <div class="col-sm-7">
                <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>
                            <label for="">Fecha</label>
                            <input type="date" name="" id="mifecha" class="form-control">
                        </td>
                        <td>
                            <label for="">Hora</label>
                            <input type="time" name="" id="mihora" class="form-control">
                        </td>
                        <td>
                            <label for="">Veedor</label>
                            <div style="border-style: outset;">
                                <select name="" id="delegado_id" class="select2 form-control">
                                    @foreach ($delegados as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach                                    
                                </select>
                            <div>
                        </td>


                    </tr>
                    <tr>

                        <td>
                            <label for="">Equipo "A"</label>
                            <div style="border-style: outset;">
                                <select name="" id="equipo_a" class="select2 form-control">
                                    @foreach ($equipos as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach                                    
                                </select>
                            <div>
                        </td>
                        <td>
                            <label for="">Equipo "B"</label>
                            <div style="border-style: outset;">
                                <select name="" id="equipo_b" class="select2 form-control">
                                    @foreach ($equipos as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach                                    
                                </select>
                            <div>
                        </td>

                        <td>
                            <label for="">Descansa</label>
                            <div style="border-style: outset;">
                                <select name="" id="" class="select2 form-control">
                                    @foreach ($equipos as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach                                    
                                </select>
                            <div>
                        </td>
                    </tr>
                </table>
                </div>
            </div>
            <div class="col-sm-5">
                <button  onclick="save()" class="btn btn-primary btn-block">Guardar Fecha</button>
            </div>
            <div class="col-sm-7">
                <button  onclick="add()" class="btn btn-dark btn-block">Agregar Encuentro</button>
            </div>
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Hora</th>
                                <th scope="col">Equipo A</th>
                                <th scope="col">Goles</th>
                                <th scope="col"></th>
                                <th scope="col">Equipo B</th>
                                <th scope="col">Goles</th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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


        var example1 = new BSTable("example", {
			editableColumns:"4,7",
			onEdit:function() {
				console.log("EDITED");
			},
			advanced: {
				columnLabel: ''
			}
		});

        // var example1 = new BSTable("example");
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
        });

        var count = 1 
        function add() {
            Swal.fire({
                title: 'Estas Segur@ de Agregar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then(async (result) => {
                if (result.isConfirmed) {                                        
                    $('#example').append("<tr><td>"+(count++)+"</td><td>"+$("#mifecha").val()+"</td><td>"+$("#mihora").val()+"</td><td>"+$("#equipo_a option:selected").text()+"</td><td>0</td><td>VS</td><td>"+$("#equipo_b option:selected").text()+"</td><td>0</td></tr>");
                    example1.init();
                }
            }) 
        }

        function save() {
            Swal.fire({
                title: 'Estas Segur@ de Guardar la FECHA?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
                }).then(async (result) => {
                if (result.isConfirmed) {           
                    location.href = "/admin/fixtures"
                }
            }) 
        }

    </script>
@stop
