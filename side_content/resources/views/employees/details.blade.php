@extends('layouts.main')

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Empleados</h3>
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

    <br>
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject font-dark sbold uppercase"></span>
            </div>

        </div>
        <div class="portlet-body form">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Nombre') !!}
                            <div  class="form-control">
                                <samp>{{$employee->name}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Apellidos') !!}
                            <div class="form-control">
                                <samp>{{$employee->last_name}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Fecha de nacimiento') !!}
                            <div class="form-control">
                                <samp>{{$employee->birthdate}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Código empleado') !!}
                            <div class="form-control">
                                <samp>{{$employee->id}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Celular') !!}
                            <div class="form-control">
                                <samp>{{$employee->cell_phone}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Dirección') !!}
                            <div class="form-control">
                                <samp>{{$employee->direction}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Número de IMSS') !!}
                            <div class="form-control">
                                <samp>{{$employee->imss_number}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('CURP') !!}
                            <div class="form-control">
                                <samp>{{$employee->curp}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('RFC') !!}
                            <div class="form-control">
                                <samp>{{$employee->rfc}}</samp>
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Aptitudes') !!}
                            <div class="form-control">
                                 <samp>{{$employee->aptitudes}}</samp>
                            </div>
                        </div>
                    </div>--}}

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Puesto') !!}
                            <div class="form-control">
                                <samp>{{$employee->stall}}</samp>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Sueldo por semana ') !!}
                            <div class="form-control">
                                <samp>{{$employee->salary_week}}</samp>
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Obras asignada') !!}
                            <div class="form-control">
                                @foreach($employee->publicWorks as $employees)
                                    {{$employees->name}}
                                @endforeach
                            </div>
                        </div>
                    </div>--}}

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Fecha de registro') !!}
                            <div class="form-control">
                                <samp>{{$employee->registration_date}}</samp>
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Teléfono') !!}
                            <div class="form-control">
                                <samp>{{$employee->phone}}</samp>
                            </div>
                        </div>
                    </div>--}}

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                            {!! Form::label('Estatus') !!}
                            <select class="form-control m-input" name="status" id="status">
                                <option disabled {{isset($employee) ? $employee->status != '' ? '' : 'selected' : 'selected'}}>Seleccione un estatus</option>
                                <option value="1" {{isset($employee) ? $employee->status == 1 ?  'selected' : '' : ''}}>Activo</option>
                                <option value="1" {{isset($employee) ? $employee->status == 2 ?  'selected' : '' : ''}}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <div class="col-2"></div>
                        <div class=" col-lg-5 ">
                        {!! Form::label('Imagen') !!}
                            <samp><img src="{{$employee->photography}}" with="100" height="100"></samp>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                            <div class="col-10">
                                <a  class="btn btn-secondary" href="{{URL::route('employees.index')}}">Cerrar</a>
                            </div>
                        </div>
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
            $('#liEmployees').addClass('start')
        });
    </script>
@endsection

