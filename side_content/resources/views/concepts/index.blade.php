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
            <a href="{!!URL::to('/providers/getOrders/'.$order->provider_id)!!}" class="m-nav__link">
                <span class="m-nav__link-text">Órdenes de compra</span>
            </a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <input type="hidden" name="order_id" value="{{$order->id}}}" id="order_id">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Conceptos
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                        </li>
                    </ul>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="table-toolbar">
                    <div class="row">
                    </div>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable display responsive nowrap" id="concepts-table">
                    <thead>
                    <tr>
                        <th>Número</th>
                        <th>Concepto</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        {{--<th>P.U. SISEGA</th>--}}
                        <th>P.U. compra</th>
                        {{--<th>Diferencia</th>--}}
                        <th>Importe</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($concepts as $concept)
                        <tr class="odd gradeX">
                            <td>{{$concept->id}}</td>
                            <td>{{$concept->concept}}</td>
                            <td>{{$concept->measurement}}</td>
                            <td>{{$concept->quantity}}</td>
                            {{--<td>{{$concept->format_sisega_price}}</td>--}}
                            <td>{{$concept->format_purchase_price}}</td>
                            {{--<td>{{$concept->difference}}</td>--}}
                            <td>{{$concept->amount}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="8" style="text-align:right">
                            <p style="text-align: right">Subtotal: {{$subtotal}}</p>
                            <p style="text-align: right">Iva: {{$iva}}</p>
                            <p style="text-align: right">Total: {{$total}}</p>
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liProviders').addClass('start');

            $('#concepts-table').dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                }
            });
        });
    </script>
@endsection