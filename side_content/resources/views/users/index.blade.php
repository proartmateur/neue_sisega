@extends('layouts.main')

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Usuarios</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/users')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Usuarios</span>
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
                            Usuarios
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            @if(Auth::user()->role==1)
                                <a href="{{URL::route('users.create')}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nuevo usuario</span>
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
                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable display responsive nowrap" id="mtable1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Puesto</th>
                            <th>Obra asignada</th>
                            <th>Rol</th>
                            <th>Estatus</th>
                            @if(Auth::user()->role==1)
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="odd gradeX">
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->stall}}</td>
                                <td>

                                    @foreach($user->public_works as $public_work)
                                       
                                        <li>{{$public_work}}</li>
                                       
                                    @endforeach
                                </td>
                                <td>{{$user->role == 1 ? 'Administrador' : 'Residente'}}</td>
                                <td>{{$user->status == 1 ? 'Activo' : 'Suspendido'}}</td>

                                @if(Auth::user()->role==1)
                                <td>
                                    <a href="{{  URL::route('users.edit', $user->id) }}" title="Editar" class="btn btn-success " id="button">
                                        <i class="flaticon flaticon-edit"></i><span></span>
                                    </a>
                                    <a href="#myModal"
                                       data-toggle="modal" data-name="{{$user->name}}" click=modalDelete data-id="{{ $user->id}}" title="Eliminar" class="btn btn-danger openBtn">
                                        <i class="flaticon flaticon-delete"></i><span></span>
                                    </a>
                                </td>
                                @endif
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
                    <div class="modal-body" id="bodyDelete"></div>
                    <div class="modal-footer">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <button type="button" data-dismiss="modal" class="btn grey-salt">Cancelar</button>
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
            $('#liUser').addClass('start');

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
             var nameNode=document.createTextNode("¿Seguro que desea eliminar el usuario "+ name +"?");
             nodeName.appendChild(nameNode);
             $("#bodyDelete").empty();
             document.getElementById("bodyDelete").appendChild(nodeName);
         });

         function deleteCate(){
             var token = $("#token").val();

             $.ajax({
                 url: "users/"+id,
                 headers: {'X-CSRF-TOKEN': token},
                 type: "DELETE",
                 success: function() {
                     window.location = "/users";
                     $("#message").fadeIn();
                 }
             });
         }
    </script>

@endsection








