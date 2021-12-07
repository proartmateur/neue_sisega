@extends('layouts.main')

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Obras</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/public-works')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Obras</span>
            </a>
        </li>
    </ul>

@endsection

@section('content')
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Obras
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            @if(Auth::user()->role==1)
                            <a href="{{URL::route('public-works.create')}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nueva obra</span>
                                </span>
                            </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="table-toolbar">
                    <div class="row">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-md-6">
                        <label class="col-form-label col-lg-3 col-sm-12" style="text-align: right">Mostrar inactivos:</label>
                        <a class="m-btn btn">
                            <span class="m-switch m-switch--outline m-switch--icon m-switch--accent">
                                <label>
                                    <input type="checkbox" name="show" class="switch"  onclick="change_status(this.checked)">
                                    <span></span>
                                </label>
                            </span>
                        </a>
                    </div>
                </div>

                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable responsive nowrap" id="mtable1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Presupuesto</th>
                            <th>Residente(s)</th>
                            <th>Fecha fin</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($public_works as $public_work)
                            <tr class="odd gradeX">
                                <td>{{$public_work->name}}</td>
                                <td>{{$public_work->format_budget}}</td>
                                <td>
                                    @if (isset($public_work->supervisors))
                                        @foreach($public_work->supervisors as $supervisor)
                                            <li>{{$supervisor->name}}</li>
                                        @endforeach
                                    @endif
                                </td>
                                <td @if($public_work->mark) style="color: red" @endif>{{$public_work->end_date}}</td>
                                <td>
                                    <a href="{{  URL::route('public-works.show', $public_work->id) }}" title="Detalles" class="btn btn-info ">
                                        <i class="flaticon flaticon-eye"></i><span></span>
                                    </a>

                                    @if(Auth::user()->role==1)
                                    <a href="{{URL::route('public-works.edit', $public_work->id)}}" title="Editar" class="btn btn-success" id="button">
                                        <i class="flaticon flaticon-edit"></i><span></span>
                                    </a>
                                    <a href="#myModal" data-toggle="modal" data-name="{{$public_work->name}}" click=modalDelete data-id="{{$public_work->id}}" title="Eliminar" class="btn btn-danger openBtn">
                                        <i class="flaticon flaticon-delete"></i><span></span>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" id="bodyDelete">
                        <p>¿Seguro que desea eliminar la obra?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <input type="hidden" name="_token" value="" id="txt-borrar-obra">
                        <button type="button" data-dismiss="modal" class="btn grey-salt" id="btn-cerrar-eliminar-obra">Cancelar</button>
                        <button type="button"  class="btn yellow-lemon" onclick="deleteCate()">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liPublic_works').addClass('start');

            $('#mtable1').dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                }
            });
        });

        $('.openBtn').on('click',function(){
             id = $(this).data('id');
             var name = $(this).data('name');

             var nodeName=document.createElement("p");
             var nameNode=document.createTextNode("¿Seguro que desea eliminar la obra?");
             nodeName.appendChild(nameNode);
             $("#bodyDelete").empty();
             document.getElementById("bodyDelete").appendChild(nodeName);

             $('#txt-borrar-obra').val(id);

            


         });

        function setModal(id) {

             var nodeName=document.createElement("p");
             var nameNode=document.createTextNode("¿Seguro que desea eliminar la obra?");
             nodeName.appendChild(nameNode);
             $("#bodyDelete").empty();
             document.getElementById("bodyDelete").appendChild(nodeName);

             $('#txt-borrar-obra').val(id);


           /*var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar la obra?");
            nodeName.appendChild(nameNode);
            //$("#bodySpecialDelete").empty();
            $('#bodyDelete').empty();
            document.getElementById("bodyDelete").appendChild(nodeName);

            var dismiss = document.createElement("BUTTON");
            dismiss.innerHTML = "Cancelar";
            dismiss.setAttribute("data-dismiss", "modal");
            dismiss.setAttribute("class", "btn grey-salt");

            var confirm = document.createElement("BUTTON");
            confirm.innerHTML = "Aceptar";
            confirm.setAttribute("onclick", "specialDeletePublicWork(" + id + ")");
            confirm.setAttribute("class", "btn yellow-lemon");

            $("#footerSpecialDelete").empty();

            document.getElementById("footerSpecialDelete").appendChild(dismiss);
            document.getElementById("footerSpecialDelete").appendChild(confirm);*/

        }

        function change_status(status) {
            $.ajax({
                url: "/public-works/change_status",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                type: 'POST',
                dataSrc:"",
                data:{status:status},
                success:function(data){
                    if(data === ''){
                        swal({
                            "title": "Error de búsqueda.",
                            "text": "No se encuentran resultados.",
                            "type": "warning",
                            "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide",
                            "confirmButtonText": "Aceptar"
                        });

                        $('#mtable1').DataTable().destroy();
                        $('#mtable1').DataTable({
                            "aoColumns": [
                                {"bSortable": false },
                                {"bSortable": false },
                                {"bSortable": false },
                                {"bSortable": false },
                                {"bSortable": false }
                            ],
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                            },
                            "bDestroy": true
                        }).clear().draw();

                    }else{
                        $('#mtable1').DataTable().destroy();
                        $('#mtable1').DataTable(
                            {
                                "data":data[0],
                                "columns":[
                                    {data:"name"},
                                    {data:"budget"},
                                    {data:"supervisors"},
                                    {data:"end_date"},
                                    {data:"buttons"}
                                ],
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                                },
                                /*"scrollX": true*/
                            }
                        );
                    }
                }
            });
        }

        function deleteCate(){
            var token = $("#token").val();

            $('#btn-cerrar-eliminar-obra').trigger('click');

            $.ajax({
                url: "public-works/borrar-obra/"+$('#txt-borrar-obra').val(),
                headers: {'X-CSRF-TOKEN': token},
                type: "GET",
                success: function() {
                    window.location = "/public-works";
                    $("#message").fadeIn();



                }
            });
        }

        function specialDeletePublicWork(public_work_id) {
            var token = $("#token").val();

            $.ajax({
                url: "public-works/"+ public_work_id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/public-works";
                    $("#message").fadeIn();
                }
            });
        }
    </script>

@endsection








