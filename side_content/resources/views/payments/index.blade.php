@extends('layouts.main')

@section('breadcrumb')
    <h3 class="m-subheader__title m-subheader__title--separator">Pagos</h3>
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
            <a href="{!!URL::to('/providers/getOrders/'.$order->provider_id)!!}" class="m-nav__link">
                <span class="m-nav__link-text">Órdenes de compra</span>
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/providers/getPayments/'.$order->id)!!}" class="m-nav__link">
                <span class="m-nav__link-text">Pagos</span>
            </a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
            <input type="hidden" name="order_id" value="{{$order->id}}}" id="order_id">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Pagos
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <a href="{{URL::to('/providers/createPayment/'.$order->id)}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nuevo pago</span>
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
                <table class="table table-striped- table-bordered table-hover table-checkable display responsive nowrap" id="payments-table">
                    <thead>
                    <tr>
                        <th>N° de pago</th>
                        <th>Fecha de pago</th>
                        <th>Monto</th>
                        <th>Comentarios</th>

                        @if(Auth::user()->role==1)
                        <th>Estatus</th>
                        @endif

                        <th>Acciones</th>
                        <th>Archivo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr class="odd gradeX">
                            <td>{{$payment->consecutive}}</td>
                            <td>{{$payment->date}}</td>
                            <td>{{$payment->format_amount}}</td>
                            <td>{{$payment->comments}}</td>

                            @if(Auth::user()->role==1)
                            <td>
                                <div class="m-checkbox-list">
                                    <label class="m-checkbox">
                                        <input type="checkbox" onchange="change_status('{{$payment->id}}', this)" @if($payment->status=='2') checked="checked" @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                            @endif

                            <td>
                                <a href="{{URL::to('/providers/editPayment/'.$payment->id)}}" title="Editar" class="btn btn-success" id="button">
                                    <i class="flaticon flaticon-edit"></i><span></span>
                                </a>

                                @if(Auth::user()->role==1)
                                <a href="#myModal" data-toggle="modal" click=modalDelete data-id="{{$payment->id}}" title="Eliminar" class="btn btn-danger openBtn">
                                    <i class="flaticon flaticon-delete"></i><span></span>
                                </a>
                                @endif
                            </td>
                            <td>
                                @if($payment->pdf != null)
                                    <a target="_blank" href="{{$payment->pdf}}" title="Descargar" ><i class="fa fa-download"></i></a>
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
                        <button type="button" data-dismiss="modal" class="btn grey-salt">Cancelar</button>
                        <button type="button"  class="btn yellow-lemon" onclick="deletePayment()">Aceptar</button>
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

            $('#payments-table').dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                }
            });
        });

        function change_status(payment_id, checkbox) {
            var token = $("#token").val();
            var status = checkbox.checked;
            var order_id = $("#order_id").val();

            $.ajax({
                url: "/providers/change_status",
                headers: {'X-CSRF-TOKEN': token},
                type: "POST",
                data:{id: payment_id, status:status, order_id:order_id},
                success: function() {
                    window.location = "/providers/getPayments/"+order_id;
                    $("#message").fadeIn();
                }
            });
        }

        $('.openBtn').on('click',function(){
            id = $(this).data('id');

            var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar el pago?");
            nodeName.appendChild(nameNode);
            $("#bodyDelete").empty();
            document.getElementById("bodyDelete").appendChild(nodeName);
        });

        function deletePayment(){
            var token = $("#token").val();
            var order_id = $("#order_id").val();

            $.ajax({
                url: "/providers/deletePayment/"+id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/providers/getPayments/"+order_id;
                    $("#message").fadeIn();
                }
            });
        }
    </script>
@endsection