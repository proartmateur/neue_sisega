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
{{--<div class="form-group m-form__group row">
    <div class="col-2"></div>
    <div class="col-lg-5">
        <span style="color: #ff0000" class="required-val">* </span>
        {!! Form::label('Apellido(s)') !!}
        {!! Form::text('last_name' ,null, ['class' => 'form-control' ]) !!}
    </div>
</div>--}}
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
{{--<div class="form-group m-form__group row">
    <div class="col-2"></div>
    <div class="col-lg-5">
        <span  style="color: red" class="required-val">* </span>
        {!! Form::label('Aptitudes ') !!}
        {!! Form::text('aptitudes' ,null, ['class' => 'form-control' ]) !!}
    </div>
</div>--}}
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
        {{--@if (isset($employee))
            <input type="text" name="salary_week" class="form-control" id="salary_week" value="{{$employee->salary_week}}">
        @else
            <input type="text" name="salary_week" class="form-control" id="salary_week" value="0.00">
        @endif--}}
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
{{--<div class="form-group m-form__group row">
    <div class="col-2"></div>
    <div class="col-lg-5">
        <span  style="color: red" class="required-val">* </span>
        {!! Form::label('Teléfono') !!}
        {!! Form::number('phone' ,null, ['class' => 'form-control' ]) !!}
    </div>
</div>--}}

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
        {{-- <span style="color: #ff0000" class="required-val">* </span> --}}
        {!! Form::label('Banco') !!}
        {!! Form::text('bank' ,null, ['class' => 'form-control' ]) !!}
    </div>
</div>

<div class="form-group m-form__group row">
    <div class="col-2"></div>
    <div class="col-lg-5">
        {{-- <span  style="color: red" class="required-val">* </span> --}}
        {!! Form::label('Clabe') !!}
        {!! Form::number('clabe' ,null, ['class' => 'form-control' ]) !!}
    </div>
</div>

<div class="form-group m-form__group row">
    <div class="col-2"></div>
    <div class="col-lg-5">
        {{-- <span  style="color: red" class="required-val">* </span> --}}
        {!! Form::label('Cuenta') !!}
        {!! Form::number('account' ,null, ['class' => 'form-control' ]) !!}
    </div>
</div>