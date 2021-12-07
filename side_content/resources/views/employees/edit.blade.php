@extends('layouts.main')

<style>
    .hide {
        display:none;
    }
</style>

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Editar empleado</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/employees')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Empleados</span>
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
                        Editar empleado
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::model($employee,['route'=>['employees.update',$employee->id], 'method'=>'PUT','files' => true, 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right']) !!}
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Tipo') !!}  {!! $employee->type !!}
                            {!! Form::select('type',['1' => 'Empleado', '2' => 'Destajista'], null,['class'=>'form-control', 'placeholder'=>'Seleccione un tipo', 'id'=>'type', 'onchange'=>"typeSelected()"])!!}
                        </div>
                    </div>

                    <fieldset id="employee">
                        @include('employees.form')
                    </fieldset>

                    <fieldset id="pieceworker">
                        <input type="hidden" name="imss" value="0" id="imss">
                        @include('employees.pieceworker_form')
                    </fieldset>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <a  class="btn btn-secondary" href="{{URL::route('employees.index')}}">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        {{--<div class="m-portlet m-portlet--tab">
            <div class="m-portlet__body">
                <input type="hidden" name="type" value="1" id="type">

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Fotografía ') !!}
                        {!! Form::file('photography' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                @if(isset($employee))
                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class="col-lg-5">
                            <span  style="color: red" class="required-val">* </span>
                            {!! Form::label('Número de empleado') !!}
                            <div class="form-control">
                                <samp>{{$employee->id}}</samp>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Nombre completo') !!}
                        {!! Form::text('name' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Fecha de nacimiento') !!}
                        {!! Form::date('birthdate' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Celular ') !!}
                        {!! Form::number('cell_phone' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Dirección ') !!}
                        {!! Form::text('direction' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        {!! Form::label('Número de IMSS') !!}
                        {!! Form::text('imss_number' ,null, ['class' => 'form-control', 'onkeypress'=>'validateInput(event, 2)']) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <div class="m-checkbox-list">
                            <label class="m-checkbox">
                                <input type="checkbox" name="imss" @if(isset($employee)) @if($employee->imss == 0) checked @endif @endif> No está activo en IMSS
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('CURP ') !!}
                        {!! Form::text('curp' ,null, ['class' => 'form-control', 'maxlength'=>'18']) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('RFC ') !!}
                        {!! Form::text('rfc' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Puesto o Función') !!}
                        {!! Form::text('stall' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Sueldo por semana ') !!}
                        {!! Form::number('salary_week', null, ['class' => 'form-control', 'step'=>'.01']) !!}
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Fecha de registro') !!}
                        {!! Form::date('registration_date' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Estatus') !!}
                        {!! Form::select('status',['1' => 'Activo', '2' => 'Inactivo'], null,['class'=>'form-control', 'placeholder'=>'Seleccione un estatus', 'id'=>'status'])!!}
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span style="color: #ff0000" class="required-val">* </span>
                        {!! Form::label('Banco') !!}
                        {!! Form::text('bank' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Clabe') !!}
                        {!! Form::number('clabe' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-2"></div>
                    <div class="col-lg-5">
                        <span  style="color: red" class="required-val">* </span>
                        {!! Form::label('Cuenta') !!}
                        {!! Form::number('account' ,null, ['class' => 'form-control' ]) !!}
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a  class="btn btn-secondary" href="{{URL::route('employees.index')}}">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
        {!!Form::close() !!}
    </div>

@endsection

@section('scripts')
    {!! Html::script("assets/js/validate.js") !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.js"></script>
    <script type="text/javascript">
        var type = {{$employee->type}};
    
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liEmployees').addClass('start');

            var element = document.getElementById("type");
            element.value = type;

            typeSelected();
        });

        function typeSelected() {
            var type = $("#type").val();

            var employee = document.getElementById("employee");
            var pieceworker = document.getElementById("pieceworker");

            if(type !== ''){
                if(type==="1"){
                    $("#employee").prop('disabled', false);
                    $("#pieceworker").prop('disabled', true);

                    employee.classList.remove('hide');
                    pieceworker.classList.add('hide');
                }else{
                    $("#employee").prop('disabled', true);
                    $("#pieceworker").prop('disabled', false);

                    employee.classList.add('hide');
                    pieceworker.classList.remove('hide');
                }
            }else{
                $("#employee").prop('disabled', true);
                $("#pieceworker").prop('disabled', true);

                employee.classList.add('hide');
                pieceworker.classList.add('hide');
            }
        }
    </script>
@endsection
