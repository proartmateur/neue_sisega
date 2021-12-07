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
            <a href="{!!URL::to('/providers/getPayments/'.$order->id)!!}" class="m-nav__link">
                <span class="m-nav__link-text">Pagos</span>
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
                        Editar pago
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['url' => '/providers/updatePayment', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right', 'id'=>'payment_form', 'files'=>'true']) !!}
            <input type="hidden" name="payment_id" value="{{$payment->id}}" id="payment_id">
            <input type="hidden" name="mail" value="false" id="mail">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Fecha de pago') !!}
                            {!! Form::date('date' ,$payment->date, ['class' => 'form-control' ]) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Monto') !!}
                            {!! Form::number('amount', $payment->amount, ['class' => 'form-control', 'step'=>'.01', 'max'=>$debt, 'id'=>'amount']) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            {{--<span  style="color: red" class="required-val">* </span>--}}
                            {!! Form::label('Comentarios ') !!}
                            {!! Form::textarea('comments' ,$payment->comments, ['class' => 'form-control' ]) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            {!! Form::label('PDF') !!}
                            {!! Form::file('pdf', null, ['class' => 'form-control', 'accept'=>'pdf']) !!}
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10">
                                {{--<button type="submit" class="btn btn-success">Guardar</button>--}}
                                <button type="button" id="btn-submit" class="btn btn-success">Guardar</button>
                                <a  class="btn btn-secondary" href="{{URL::to('/providers/getPayments/'.$order->id)}}">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!!Form::close() !!}
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var debt = {!!json_encode($debt)!!};

        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liProviders').addClass('start');
        });

        $('#btn-submit').on('click',function(e){
            e.preventDefault();
            var form = $(this).parents('form');

            var amount = $("#amount").val();

            console.log(amount);

            if(parseFloat(amount) === debt){
                swal({
                    "title": "El monto especificado liquida la orden de compra.",
                    "text": "¿Desea enviar un correo de notificación a los residentes?",
                    "type": "info",
                    "showCancelButton": true,
                    "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide",
                    "confirmButtonText": "Enviar",
                    "cancelButtonText": "No enviar"
                }).then(function (result) {
                    if (result.value) {
                        $("#mail").val(true);
                        form.submit();
                    }else {
                        $("#mail").val(false);
                        form.submit();
                    }
                });
            }else{
                if(parseFloat(amount) > debt){
                    Swal.fire({
                        type: 'error',
                        text: 'El monto especificado no puede exceder el saldo de la orden de compra.'
                    });

                    $("#amount").val(debt);
                }else{
                    $("#mail").val(false);
                    form.submit();
                }
            }
        });
    </script>
@endsection
