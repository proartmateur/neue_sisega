@extends('layouts.main')

@section('breadcrumb')
    <h3 class="m-subheader__title m-subheader__title--separator">Crear nómina</h3>
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
                        Nueva nómina - {{$employee->name}}
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['route'=>'payrolls.store', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right']) !!}
            <input type="hidden" name="employee_id" value="{{$employee->id}}" id="employee_id">
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
                            <span style="color: red" class="required-val">* </span>
                            {!! Form::label('Total a pagar') !!}
                            {!! Form::number('total_amount', null, ['class' => 'form-control', 'step'=>'.01', 'id'=>'total_amount']) !!}
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
                            {!! Form::date('date', \Carbon\Carbon::now('America/Mexico_City'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <a  class="btn btn-secondary" href="{{URL::to('/employees/getPayrolls/'.$employee->id)}}">Cancelar</a>
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
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liEmployees').addClass('start');
        });
    </script>
@endsection
