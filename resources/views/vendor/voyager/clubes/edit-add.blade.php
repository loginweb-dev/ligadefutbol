@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    $temporadas = App\Temporada::all();
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .simple-upload {
            &-dragover {
                background-color: #eef;
            }
            &-filename {
                margin-right: 0.5em;
            }
        }
    </style>
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-sm-12">
                <div class="step-app" id="demo">
                    <ul class="step-steps">
                      <li data-step-target="step1">Paso 1</li>
                      <li data-step-target="step2">Paso 2</li>
                      <li data-step-target="step3">Paso 3</li>
                    </ul>
                    <div class="step-content">
                      <div class="step-tab-panel" data-step="step1">
                        <h2>Datos del Equipo</h2>
                        <table class="table">
                            <tr>
                                <td>Temporada</td>
                                <td>
                                    <div class="miselect">
                                        <select name="temporada_id" id="temporada_id" class="form-control">
                                            @foreach ($temporadas as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td><input type="text" name="name" id="name" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>whatsapp</td>
                                <td><input type="number" name="wpp" id="wpp" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Presidente</td>
                                <td><input type="test" name="presidente" id="presidente" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Logo o Image</td>
                                <td><input type="file" name="image" id="image"></td>
                            </tr>
                            <tr>
                                <td>Vice presidente</td>
                                <td><input type="text" name="vicepresidente" id="vicepresidente" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Secretari@ General</td>
                                <td><input type="text" name="secre_general" id="secre_general" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Secretari@ Hacienda</td>
                                <td><input type="text" name="secre_hacienda" id="secre_hacienda" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Vocal</td>
                                <td><input type="text" name="vocal" id="vocal" class="form-control"></td>
                            </tr>
                        </table>
                      </div>
                      <div class="step-tab-panel" data-step="step2">
                        <h2>Usuarios y Delegados</h2>
                        <table class="table">
                            <tr>
                                <td>Usuario</td>
                                <td><input type="text" id="name" name="name" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Correo</td>
                                <td><input type="text" id="miemail" name="miemail" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Contraseña</td>
                                <td><input type="password" id="mipassword" name="mipassword" class="form-control"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td>Delegado Titular</td>
                                <td><input type="text" id="name_titular" name="name_titular" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Telefono Titular</td>
                                <td><input type="number" id="phone_titular" name="phone_titular" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Delegado Suplente</td>
                                <td><input type="text" id="name_suplente" name="name_suplente" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Telefono Suplente</td>
                                <td><input type="number" id="phone_suplente" name="phone_suplente" class="form-control"></td>
                            </tr>
                        </table>
                      </div>
                      <div class="step-tab-panel" data-step="step3">
                        <h2>Jugadores</h2>
                        <table class="table">
                            <tr>
                                <td>
                                    Jugador:
                                    <input type="text" id="mijugador" class="form-control">
                                </td>
                                <td>
                                    # de Polera
                                    <input type="number" name="" id="mipolera" class="form-control">
                                </td>
                                <td>
                                    Fecha de Nacimiento
                                    <input type="date" id="mifecha" class="form-control">
                                </td>
                                <td>
                                    Telefono
                                    <input type="number" id="mitelefono" class="form-control">
                                </td>

                                <td>
                                    <br>
                                    <a href="#" onclick="add()" class="btn btn-sm btn-dark">Agregar</a>
                                </td>
                            </tr>
                        </table>
                        <table class="table mitable" id="table_jugadores">
                            <thead>
                                <tr class="active">
                                    <th>#</th>
                                    <th>Jugador</th>
                                    <th>Nacimiento</th>
                                    <th>Polera</th>
                                    <th>Telefono</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                      </div>
                    </div>                  
                    <div class="step-footer">
                      <button data-step-action="prev" class="step-btn btn btn-dark">Anterior</button>
                      <button data-step-action="next" class="step-btn btn btn-dark">Siguiente</button>
                      <button data-step-action="finish" class="step-btn btn btn-dark">Final</button>
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
    <script src="{{ asset('js/jquery-simple-upload.js') }}"></script>
    <script>
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
            localStorage.removeItem('jugadores_equipos');
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

        $('#demo').steps({
            onFinish: function () { 
               
                // alert('complete'); 
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
                        toastr.success("Guardando equipo..")
                        var data = new FormData();
                        data.append('image', document.getElementById('image').files[0]);
                        data.append('name', $('#name').val())
                        data.append('wpp', $('#wpp').val())
                        data.append('user_id', '{{ Auth::user()->id }}')
                        data.append('presidente', $('#presidente').val())
                        data.append('vocal', $('#vocal').val())
                        data.append('status', 1)
                        data.append('secre_hacienda', $('#secre_hacienda').val())
                        data.append('vicepresidente', $('#vicepresidente').val())
                        const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                        var newclub = await axios.post("/api/clubes/save", data, config)
                        console.log(newclub.data)
                        var newclub = await axios.post("/api/clubes/rel/save", {
                            'temporada_id': $('#temporada_id :selected').val(),
                            'club_id': newclub.data.id
                        })

                        toastr.success("Guardando Usuarios y Delegados..")
                        var newclub = await axios.post("/api/usuarios/save", {
                            'name' : $('#name').val(),
                            'email' : $('#miemail').val(),
                            'password': $('#mipassword').val(),
                        })
                        var newclub = await axios.post("/api/delegados/save", {
                            'name' : $('#name_titular').val(),
                            'clube_id' : newclub.data.id
                        })
                        var newclub = await axios.post("/api/delegados/save", {
                            'name' : $('#name_suplente').val(),
                            'clube_id' : newclub.data.id
                        })

                        // toastr.success("Guardando jugadores..")
                        // var midata = localStorage.getItem("jugadores_equipos") ? JSON.parse(localStorage.getItem("jugadores_equipos")) : []
                        // for (let index = 0; index < midata.length; index++) {  
                        //     console.log(midata[index])
                        //     await axios.post("/api/jugadores/save", midata[index])
                        // }
                        // location.href = "/admin/clubes"
                    }
                })
            }
        });

        function add(){
            var jugadores_equipos = localStorage.getItem("jugadores_equipos") ? JSON.parse(localStorage.getItem("jugadores_equipos")) : []
            var jugadores = {
                'jugador' : $("#mijugador").val(),
                'fecha' : $("#mifecha").val(),
                'polera' : $("#mipolera").val(),
                'telefono' : $("#mitelefono").val()
            }
            jugadores_equipos.push(jugadores)
            localStorage.setItem("jugadores_equipos", JSON.stringify(jugadores_equipos))
            read_jugador()
            toastr.info("Jugador Agregador a lista")
            $("#mijugador").val("")
            $("#mipolera").val("")
            $("#mitelefono").val("")
        }
        
        function read_jugador(){         
            var midata = localStorage.getItem("jugadores_equipos") ? JSON.parse(localStorage.getItem("jugadores_equipos")) : []
            $('#table_jugadores tbody tr').remove();
            for (let index = 0; index < midata.length; index++) {  
                $('#table_jugadores').append("<tr><td>"+(index+1)+"</td><td>"+midata[index].jugador+"</td><td>"+midata[index].fecha+"</td><td>"+midata[index].polera+"</td><td>"+midata[index].telefono+"</td></tr>")
            }
        }

    </script>
@stop
