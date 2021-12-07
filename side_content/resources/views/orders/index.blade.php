@extends('layouts.main')

@section('breadcrumb')
    <h3 class="m-subheader__title m-subheader__title--separator">Órdenes de compra</h3>
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
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/providers/getOrders/'.$provider->id)!!}" class="m-nav__link">
                <span class="m-nav__link-text">Órdenes de compra</span>
            </a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <input type="hidden" name="provider_id" value="{{$provider->id}}}" id="provider_id">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Órdenes de compra - {{$provider->name}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <a href="{{URL::to('/providers/createOrder/'.$provider->id)}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nueva orden</span>
                                </span>
                            </a>
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
                        <label class="col-form-label col-lg-4 col-sm-12" style="text-align: right">Mostrar órdenes pagadas:</label>
                        <a class="m-btn btn">
                            <span class="m-switch m-switch--outline m-switch--icon m-switch--accent">
                                <label>
                                    <input type="checkbox" name="show" class="switch change_status" onclick="change_status(this.checked)">
                                    <span></span>
                                </label>
                            </span>
                        </a>
                    </div>
                </div>

                <table class="table table-striped- table-bordered table-hover table-checkable display responsive nowrap" id="orders-table">
                    <thead>
                    <tr>
                        <th>Obra</th>
                        <th>Total pagado</th>
                        <th>Total presupuesto</th>
                        <th>Restante</th>
                        <th>Subtotal</th>
                        <th>IVA</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="odd gradeX">
                            <td>{{$order->public_work}}</td>
                            <td>{{$order->format_payment}}</td>
                            <td>{{$order->format_budget}}</td>
                            {{--<td>{{$order->remaining}}</td>--}}
                            <td>{{$order->format_bill}}</td>
                            <td>{{$order->format_subtotal}}</td>
                            <td>{{$order->format_iva}}</td>
                            <td>
                                <a href="{{URL::to('/providers/getPayments', $order->id)}}" title="Pagos" class="btn btn-primary">
                                    <i class="fa fa-dollar-sign"></i><span></span>
                                </a>
                                <a href="{{URL::to('/providers/getConcepts', $order->id)}}" title="Conceptos" class="btn btn-success">
                                    <i class="flaticon flaticon-interface-11"></i><span></span>
                                </a>
                                <a href="{{URL::to('/providers/editOrder/'.$order->id)}}" title="Editar" class="btn btn-success" id="button">
                                    <i class="flaticon flaticon-edit"></i><span></span>
                                </a>

                                @if(Auth::user()->role==1)
                                <a href="#myModal" data-toggle="modal" click=modalDelete data-id="{{$order->id}}" title="Eliminar" class="btn btn-danger openBtn">
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
                        <button type="button"  class="btn yellow-lemon" onclick="deleteOrder()">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mySpecialModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" id="bodySpecialDelete"></div>
                    <div class="modal-footer" id="footerSpecialDelete">
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

            $('#orders-table').dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                }
            });

            var checkedValue = document.querySelector('.change_status').checked;

            change_status(checkedValue);
        });

        $('.openBtn').on('click',function(){
            id = $(this).data('id');

            var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar la orden? Se eliminarán los pagos relacionados y no se podrán recuperar.");
            nodeName.appendChild(nameNode);
            $("#bodyDelete").empty();
            document.getElementById("bodyDelete").appendChild(nodeName);
        });

        function setModal(id) {
            var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar la orden? Se eliminarán los pagos relacionados y no se podrán recuperar.");
            nodeName.appendChild(nameNode);
            $("#bodySpecialDelete").empty();
            document.getElementById("bodySpecialDelete").appendChild(nodeName);

            var dismiss = document.createElement("BUTTON");
            dismiss.innerHTML = "Cancelar";
            dismiss.setAttribute("data-dismiss", "modal");
            dismiss.setAttribute("class", "btn grey-salt");

            var confirm = document.createElement("BUTTON");
            confirm.innerHTML = "Aceptar";
            confirm.setAttribute("onclick", "specialDeleteOrder(" + id + ")");
            confirm.setAttribute("class", "btn yellow-lemon");

            $("#footerSpecialDelete").empty();
            document.getElementById("footerSpecialDelete").appendChild(dismiss);
            document.getElementById("footerSpecialDelete").appendChild(confirm);
        }

        function change_status(status) {
            var provider_id = $("#provider_id").val();

            $.ajax({
                url: "/providers/search_orders",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                type: 'POST',
                dataSrc:"",
                data:{provider_id: provider_id, status:status},
                success:function(data){
                    console.log(data);

                    if(data === ''){
                        swal({
                            "title": "Error de búsqueda.",
                            "text": "No se encuentran resultados.",
                            "type": "warning",
                            "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide",
                            "confirmButtonText": "Aceptar"
                        });

                        $('#orders-table').DataTable().destroy();
                        $('#orders-table').DataTable({
                            "aoColumns": [
                                {"bSortable": false },
                                {"bSortable": false },
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
                        $('#orders-table').DataTable().destroy();
                        $('#orders-table').DataTable(
                            {
                                "data":data[0],
                                "columns":[
                                    {data:"public_work"},
                                    {data:"payment"},
                                    {data:"budget"},
                                    {data:"bill"},
                                    {data:"subtotal"},
                                    {data:"iva"},
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

        function deleteOrder(){
            var token = $("#token").val();
            var provider_id = $("#provider_id").val();

            $.ajax({
                url: "/providers/deleteOrder/"+id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/providers/getOrders/"+provider_id;
                    $("#message").fadeIn();
                }
            });
        }

        function specialDeleteOrder(order_id) {
            var token = $("#token").val();
            var provider_id = $("#provider_id").val();

            $.ajax({
                url: "/providers/deleteOrder/"+ order_id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/providers/getOrders/"+provider_id;
                    $("#message").fadeIn();
                }
            });
        }
    </script>
@endsection