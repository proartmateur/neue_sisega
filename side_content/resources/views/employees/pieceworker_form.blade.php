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
        {!! Form::label('Puesto o Función') !!}
        {!! Form::text('stall' ,null, ['class' => 'form-control' ]) !!}
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