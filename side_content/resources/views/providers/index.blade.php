@extends('layouts.main')

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Proveedores</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/providers')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Proveedores</span>
            </a>
        </li>
    </ul>

@endsection

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Proveedores
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            @if(Auth::user()->role==1)
                            <a href="{{URL::route('providers.create')}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nuevo proveedor</span>
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
                <table class="table table-striped- table-bordered table-hover table-checkable display responsive nowrap" id="mtable1">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Tipo</th>
                        <th data-priority="1">Nombre</th>
                        {{--<th>Obras</th>--}}
                        <th>Restante a pagar</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($providers as $provider)
                        <tr class="odd gradeX">
                            <td></td>
                            <td>{{$provider->type == 1 ? 'Contratista' : 'Proveedor'}}</td>
                            <td>{{$provider->name}} {{$provider->surnames}}</td>
                            {{--<td>
                                @if (isset($provider->PublicWorks))
                                    @foreach($provider->PublicWorks as $public_work)
                                        <li>{{$public_work->name}}</li>
                                    @endforeach
                                @endif
                            </td>--}}
                            <td>{{$provider->format_bill}}</td>
                            <td>
                                <a href="{{URL::to('/providers/getOrders', $provider->id)}}" title="Órdenes de compra" class="btn btn-success">
                                    <i class="flaticon flaticon-interface-11"></i><span></span>
                                </a>
                                <a href="{{  URL::route('providers.edit', $provider->id) }}" title="Editar" class="btn btn-success" id="button">
                                    <i class="flaticon flaticon-edit"></i><span></span>
                                </a>

                                @if(Auth::user()->role==1)
                                <a href="#myModal" data-toggle="modal" data-name="{{$provider->name}}" click=modalDelete data-id="{{$provider->id}}" title="Eliminar" class="btn btn-danger openBtn">
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

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" id="bodyDelete"></div>
                    <div class="modal-footer">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <button type="button" data-dismiss="modal" class="btn grey-salt">Cancelar</button>
                        <button type="button"  class="btn yellow-lemon" onclick="deleteProvider()">Aceptar</button>
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
            $('#liProviders').addClass('start');

            $('#mtable1').dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },
                "columnDefs": [ {
                    "className": 'control',
                    "orderable": false,
                    "targets":   0
                } ]
            });
        });

        $('.openBtn').on('click',function(){
            id = $(this).data('id');
            var name = $(this).data('name');

            var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar el proveedor "+ name +"?");
            nodeName.appendChild(nameNode);
            $("#bodyDelete").empty();
            document.getElementById("bodyDelete").appendChild(nodeName);
        });

        function deleteProvider(){
            var token = $("#token").val();

            $.ajax({
                url: "providers/"+id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/providers";
                    $("#message").fadeIn();
                }
            });
        }
    </script>
@endsection