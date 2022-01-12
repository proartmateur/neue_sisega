@extends('layouts.main')

<style>
    .hide {
        display: none;
    }
</style>

@section('breadcrumb')
    <h3 class="m-subheader__title m-subheader__title--separator">Crear empleado</h3>
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
                        Nuevo empleado
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['route'=>'employees.store','method'=>'POST','files' => true, 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right']) !!}
        <div class="m-portlet m-portlet--tab">

            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-2">
                    </div>

                    <div class="col-lg-5">
                        <?php $creation_error = Session::get('creation_error'); ?>
                        @if($creation_error != "")
                                <div class="alert alert-danger" role="alert">
                                    {{ $creation_error }}
                                </div>
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-2">

                    </div>
                    <div class="col-lg-5">
                        <span style="color: red" class="required-val">* </span>
                        {!! Form::label('Tipo') !!}
                        {!! Form::select('type',['1' => 'Empleado', '2' => 'Destajista'], null,['class'=>'form-control', 'placeholder'=>'Seleccione un tipo', 'id'=>'type', 'onchange'=>"typeSelected()"])!!}
                    </div>
                </div>

                <fieldset id="employee">
                    @include('employees.form')
                </fieldset>

                <fieldset id="pieceworker" disabled="disabled" class="hide">
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
                            <a class="btn btn-secondary" href="{{URL::route('employees.index')}}">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!!Form::close() !!}
    </div>
@endsection

@section('scripts')
    {!! Html::script("assets/js/validate.js") !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#listMenu').find('.start').removeClass('start');
            $('#liEmployees').addClass('start');

            var element = document.getElementById("type");
            element.value = '1';
        });

        function typeSelected() {
            var type = $("#type").val();

            var employee = document.getElementById("employee");
            var pieceworker = document.getElementById("pieceworker");

            if (type !== '') {
                if (type === "1") {
                    $("#employee").prop('disabled', false);
                    $("#pieceworker").prop('disabled', true);

                    employee.classList.remove('hide');
                    pieceworker.classList.add('hide');
                } else {
                    $("#employee").prop('disabled', true);
                    $("#pieceworker").prop('disabled', false);

                    employee.classList.add('hide');
                    pieceworker.classList.remove('hide');
                }
            } else {
                $("#employee").prop('disabled', true);
                $("#pieceworker").prop('disabled', true);

                employee.classList.add('hide');
                pieceworker.classList.add('hide');
            }
        }
    </script>
@endsection
