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
    </ul>
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Editar orden - {{$provider->name}}
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['url' => '/providers/updateOrder', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right']) !!}
        <input type="hidden" name="order_id" value="{{$order->id}}" id="order_id">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span style="color: red" class="required-val">* </span>
                        {!! Form::label('Obra') !!}
                        {!! Form::select('public_work_id', $public_works, $order->public_work_id, ['class'=>'form-control', 'placeholder'=>'Seleccione una obra', 'id'=>'public_work_id'])!!}
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <div class="m-checkbox-list">
                            <label class="m-checkbox">
                                <input type="checkbox" name="iva" class="include_iva" @if($order->iva != '0') checked @endif onclick="calculate()"> Pago con IVA
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        {!! Form::label('Componentes', null, ['style'=>'font-weight: bold']) !!}
                    </div>
                </div>

                <div id="m_repeater_1">
                    <div class="form-group m-form__group row component_repeater" id="m_repeater_1">
                        @include('orders.update_concepts_repeater')
                    </div>
                    <div class="m-form__group form-group row">
                        <label class="col-lg-2 col-form-label"></label>
                        <div class="col-lg-4">
                            <div data-repeater-create="" class="btn btn btn-sm btn-info m-btn m-btn--icon m-btn--pill m-btn--wide" id="component-repeater-button">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Agregar</span>
                        </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        {!! Form::label('Subtotal:', null, ['style'=>'font-weight: bold']) !!}
                        {!! Form::label('0', null, ['style'=>'font-weight: bold','id'=>'subtotal_amount']) !!}
                        <br>
                        {!! Form::label('IVA:', null, ['style'=>'font-weight: bold']) !!}
                        {!! Form::label('0', null, ['style'=>'font-weight: bold','id'=>'iva_amount']) !!}
                        <br>
                        {!! Form::label('Total:', null, ['style'=>'font-weight: bold']) !!}
                        {!! Form::label('0', null, ['style'=>'font-weight: bold','id'=>'total_amount']) !!}
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a  class="btn btn-secondary" href="{{URL::to('/providers/getOrders/'.$provider->id)}}">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!!Form::close() !!}
    </div>
@endsection

@section('scripts')
    {!! Html::script("vendors/jquery.repeater/src/lib.js") !!}
    {!! Html::script("vendors/jquery.repeater/src/jquery.input.js") !!}
    {!! Html::script("vendors/jquery.repeater/src/repeater.js") !!}
    {!! Html::script("assets/demo/default/custom/crud/forms/widgets/form-repeater.js") !!}

    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liProviders').addClass('start');

            $('.component_repeater').repeater({
                isFirstItemUndeletable: true
            });

            calculate();

            $('.quantity').keyup(function(event){
                calculate();
            });

            $('.purchase_price').keyup(function(event){
                calculate();
            });
        });

        $(document).on('click', '.delete_concept', function () {
            calculate();
        });

        function calculate() {
            var total = 0;
            var subtotal = 0;
            var iva = 0;

            var checkedValue = document.querySelector('.include_iva').checked;

            jQuery('.concept').each(function(e) {
                var quantity = $(this).find('.quantity').val();
                var purchase_price = $(this).find('.purchase_price').val();

                console.log(quantity);
                console.log(purchase_price);

                var amount = 0;

                if(quantity == '' || purchase_price == ''){
                    amount = 0;
                }else{
                    amount = (parseFloat(quantity)*parseFloat(purchase_price))
                }

                subtotal = subtotal + amount;
            });

            if(checkedValue){
                iva = (parseFloat(subtotal)*.16);
            }

            total = iva + subtotal;

            const moneyFormat = (value) =>
            new Intl.NumberFormat('en-US', {
                // style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            }).format(value);

            var format_iva = '$'+moneyFormat(iva);
            var format_subtotal = '$'+moneyFormat(subtotal);
            var format_total = '$'+moneyFormat(total);

            document.getElementById('subtotal_amount').innerHTML = format_subtotal;
            document.getElementById('iva_amount').innerHTML = format_iva;
            document.getElementById('total_amount').innerHTML = format_total;
        }
    </script>
@endsection
