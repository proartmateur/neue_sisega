@extends('layouts.main')

@section('breadcrumb')
    <h3 class="m-subheader__title m-subheader__title--separator">Editar proveedor</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/payrolls')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Nóminas</span>
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
                        Editar nómina - {{$employee->name}}
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::model($payroll, ['route'=>['payrolls.update', $payroll->id], 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right', 'method'=>'PUT']) !!}
            <input type="hidden" name="employee_id" value="{{$employee->id}}" id="employee_id">
            <input type="hidden" name="total_amount" value="" id="total_amount">
            <input type="hidden" name="type" value="{{$type}}" id="type">
            <input type="hidden" name="employee_type" value="{{$employee->type}}" id="employee_type">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Obra') !!}
                            {!! Form::select('public_work_id', $public_works, null, ['class'=>'form-control', 'placeholder'=>'Seleccione una obra', 'id'=>'public_work_id'])!!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Días trabajados') !!}
                            {!! Form::number('days_worked', null, ['class' => 'form-control', 'id'=>'days_worked']) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Horas trabajadas') !!}
                            {!! Form::number('hours_worked', null, ['class' => 'form-control', 'id'=>'hours_worked', 'readonly']) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            {{--<span style="color: red" class="required-val">* </span>--}}
                            {!! Form::label('Horas extras') !!}
                            {!! Form::number('extra_hours', null, ['class' => 'form-control', 'id'=>'extra_hours']) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            {{--<span  style="color: red" class="required-val">* </span>--}}
                            {!! Form::label('Comentarios ') !!}
                            {!! Form::textarea('comments', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Fecha del pago') !!}
                            {!! Form::date('date', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            {!! Form::label('Bonos', null, ['style'=>'font-weight: bold']) !!}
                        </div>
                    </div>

                    <div id="m_repeater_1">
                        <div class="form-group m-form__group row component_repeater" id="m_repeater_1">
                            @include('payrolls.update_bonuses_repeater')
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
                            {!! Form::label('Total a pagar:', null, ['style'=>'font-weight: bold']) !!}
                            {!! Form::label('0', null, ['style'=>'font-weight: bold','id'=>'total']) !!}
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <a  class="btn btn-secondary" href="{{URL::to('/payrolls')}}">Cancelar</a>
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
        var bonus_amounts = {!!json_encode($extra)!!};
        var salary = '{{$employee->salary_week}}';
        salary = salary.replace(',', '');

        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liPayroll').addClass('start');

            var count = {{$count}};
            console.log(count);

            if(count==0){
                $('.component_repeater').repeater({
                    /*initEmpty: true*/
                    isFirstItemUndeletable: true
                });
            }else {
                $('.component_repeater').repeater({
                    isFirstItemUndeletable: true
                });
            }

            calculate();

            /*var days_worked = document.getElementById('days_worked');
            var extra_hours = document.getElementById('extra_hours');

            days_worked.onkeyup = function(){
                var hours = days_worked.value != '' ? parseInt(days_worked.value)*8 : 0;

                document.getElementById('hours_worked').value = hours;

                calculate();
            };

            extra_hours.onkeyup = function(){
                calculate();
            };*/

            $("#days_worked").on("keyup keydown change",function(event){
                var hours = days_worked.value != '' ? parseInt(days_worked.value)*8 : 0;

                document.getElementById('hours_worked').value = hours;

                calculate();
            });

            $("#extra_hours").on("keyup keydown change",function(event){
                calculate();
            });
        });

        $(document).on('click', '.delete_bonus', function () {
            calculate();
        });

        function calculate() {
            var days_worked = $("#days_worked").val();
            var extra = $("#extra_hours").val();
            var days = days_worked !== '' ? days_worked: 0;
            var dairy = parseFloat(salary)/6;
            var total = days * parseFloat(dairy);

            if(extra!==''){
                /*var hour = dairy/8;
                var extra_amount = (parseInt(extra) * hour) * 2;*/
                var extra_amount = parseInt(extra);

                total = total + extra_amount;
            }

            var bonus_total = 0;

            jQuery('.bonus').each(function(e) {
                var index = $(this).find('.bonus_id').val();

                if(index!==''){
                    bonus_total = bonus_total + parseInt(bonus_amounts[index]);
                }
            });

            total = total + bonus_total;

            const moneyFormat = (value) =>
            new Intl.NumberFormat('en-US', {
                // style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            }).format(value);

            var format_total = '$'+moneyFormat(total);

            document.getElementById('total').innerHTML = format_total;
            document.getElementById('total_amount').value = total;
        }
    </script>
@endsection
