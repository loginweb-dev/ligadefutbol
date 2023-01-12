@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    $equipos = App\Clube::all();
    $planillas = App\JugadoresPlanilla::where("activo", "Aprobado")->with("clubes")->get();
    $delegados = App\Delegado::all();
    $temporadas = App\Temporada::all();
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="container-fluid">
        <br>
        <div class="row">
                                  
            <div class="col-sm-3">   
                <label for="">Temporada</label>   
                <div class="form-group miselect">                                    
                    <select name="" id="temporada_id" class="select2 form-control">
                        @foreach ($temporadas as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach                                    
                    </select>
                </div>

                <label for="">Equipo "A"</label>   
                <div class="form-group miselect">                                    
                    <select name="" id="equipo_a" class="select2 form-control">
                        @foreach ($planillas as $item)
                            <option value="{{ $item->id }}">{{ $item->clubes->name }}</option>
                        @endforeach                                    
                    </select>
                </div>
                <label for="">Equipo "B"</label>     
                <div class="form-group miselect">
                                
                    <select name="" id="equipo_b" class="select2 form-control">
                        @foreach ($planillas as $item)
                            <option value="{{ $item->id }}">{{ $item->clubes->name }}</option>
                        @endforeach                                    
                    </select>                    
                </div>
                <div class="form-group">
                    <label for="">Hora</label>
                    
                    <div class="input-group">
                        <input type="time" name="" id="mihora" class="form-control">
                        <span class="input-group-btn">
                          <button class="btn btn-dark btn-lg" type="button" onclick="add()">Agregar</button>
                        </span>
                      </div>
                </div>
                <div class="form-group">
                    <label for="">Titulo</label>
                    <textarea name="" id="title" cols="0" rows="3" class="form-control">{{ setting('features.title') }}</textarea>
                </div>
              
                <label for="">Equipo que descansa</label>             
                <div class="miselect">
                    <select name="" id="descansa_id" class="select2 form-control">
                        @foreach ($planillas as $item)
                            <option value="{{ $item->id }}">{{ $item->clubes->name }}</option>
                        @endforeach                                    
                    </select>
                </div> 
                                
                <div class="form-group">
                    <button class="btn btn-primary btn-sm btn-block" type="button" onclick="save()">Guardar</button>
                </div>
                
            </div>

            <div class="col-sm-9">            
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>
                                <label for="">Categoria</label>
                                <div class="form-group miselect">
                                    <select name="" id="delegado_id" class="select2 form-control">
                                        @foreach ($delegados as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach                                    
                                    </select>
                                </div>
                            </td>
                            <td>
                                <label for="">Veedor</label>
                                <div class="form-group miselect">
                                    <select name="" id="categoria" class="select2 form-control">                        
                                            <option value="Senior">Senior</option>
                                            <option value="Especial">Especial</option>                                                       
                                    </select>
                                </div>
                            </td>
                            <td>
                                <label for="">Fecha</label>
                                <div class="input-group">
                                    <input type="date" name="" id="mifecha" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-dark btn-md" type="button" onclick="add_date()">Agregar</button>
                                        <button class="btn btn-secundary btn-md" type="button" onclick="remove_list()">Limpiar</button>
                                    </span>
                                </div>   
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- <label for="">Lista o Feature</label> --}}
                <div class="form-group table-responsive">
                    
                    <table class="table mitable" id="example">
                        <thead>
                            <tr class="active">
                                <th scope="col">#</th>
                                <th scope="col">Hora</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Equipo A</th>
                                <th scope="col">-</th>
                                <th scope="col">Equipo B</th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
        localStorage.removeItem('encuentros');
        
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
        var encuentros = []
        function add() {          
                             
            var planilla_a_id =  $("#equipo_a option:selected").val()
            var planilla_b_id = $("#equipo_b option:selected").val()
            var milocal = localStorage.getItem("encuentros") ? JSON.parse(localStorage.getItem("encuentros")) : []
            var midesf = false
            for (let index = 0; index < milocal.length; index++) {
                if ((milocal[index].planilla_a_id == planilla_a_id) || (milocal[index].planilla_b_id == planilla_b_id)) {
                    midesf = true
                }      
            }
            if ((planilla_a_id == planilla_b_id) || midesf) {
                toastr.error("Elige equipos diferentes para el partido")
            } else {                
        
                $('#example').append("<tr><td>"+(count++)+"</td><td><span class='label label-warning'>"+$("#mihora").val()+"</span></td><td>"+$("#categoria option:selected").text()+"</td><td>"+$("#equipo_a option:selected").text()+"</td><td><span class='label label-primary'>vs</span></td><td>"+$("#equipo_b option:selected").text()+"</td></tr>");
                encuentros.push({
                    fecha: $("#mifecha").val(),
                    hora: $("#mihora").val(),
                    planilla_a_id: planilla_a_id,
                    planilla_b_id: planilla_b_id,
                    veedor_id: $('#delegado_id :selected').val(),
                    categoria: $('#categoria :selected').val(),
                    temporada_id: $("#temporada_id :selected").val()
                })
                localStorage.setItem("encuentros", JSON.stringify(encuentros));
                Toast.fire({
                    icon: 'success',
                    title: 'successfully'
                })
                $("#title").focus()
        }
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        function add_date() {       
            var midel = $('#delegado_id :selected').text()                               
            $('#example').append("<tr><td colspan='6' class='text-center'><span class='label label-success'>"+$("#mifecha").val()+"</span><br>"+midel+"</td></tr>");
            Toast.fire({
                icon: 'success',
                title: 'successfully'
            })
        }

        //Guardar fixture---------------------------------------------------------
        async function save() {
            var milocal = JSON.parse(localStorage.getItem("encuentros"))
            var midesf = false
            var mides = $("#descansa_id :selected").val()
            for (let index = 0; index < milocal.length; index++) {
                if (milocal[index].planilla_a_id == mides || milocal[index].planilla_b_id == mides) {
                    midesf = true
                }      
            }
            if (midesf) {
                toastr.error("cambia de equipo para el descanso.")
            } else {
                     
                Swal.fire({
                    title: 'Estas Segur@ de Guardar el FEATURE?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'SI',
                    cancelButtonText: 'NO'
                    }).then(async (result) => {
                    if (result.isConfirmed) {      
                        toastr.info("Enviando datos...")    
                        var midata = {
                            title: $("#title").val(),
                            user_id: "{{ Auth::user()->id }}",
                            descansa_id: $("#descansa_id").val(),
                            temporada_id: $("#temporada_id :selected").val()
                        } 
                        var newf = await axios.post("/api/features/save", midata)
                        var enc = JSON.parse(localStorage.getItem("encuentros"))
                        for (let index = 0; index < enc.length; index++) {
                            var newecn =enc[index]
                            newecn["fixture_id"] = newf.data.id
                            newecn["editor_id"] = "{{ Auth::user()->id }}"
                            var newf = await axios.post("/api/partidos/save/", newecn)
                        }
                        location.href = "/admin/fixtures"
                    }
                }) 
            }
        }

        function remove_list() {                                       
            $('#example tbody tr').remove();
            localStorage.removeItem('encuentros');
            count = 1
            Toast.fire({
                icon: 'success',
                title: 'successfully'
            })
        }
        
        var today = new Date().toISOString().split('T')[0];
        var now = new Date(Date.now());
        var mitime = now.getHours() + ":" + now.getMinutes();
        $('#mifecha').val(today);
        $('#mihora').val(mitime);
    </script>
@stop
