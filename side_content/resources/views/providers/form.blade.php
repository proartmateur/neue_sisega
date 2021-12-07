<div class="m-portlet m-portlet--tab">
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Tipo') !!}
                {!! Form::select('type', ['1' => 'Contratista', '2' => 'Proveedor'], null, ['class'=>'form-control', 'placeholder'=>'Seleccione un tipo', 'id'=>'type'])!!}
            </div>
        </div>

        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                {!! Form::label('Nombre') !!}
                {!! Form::text('name' ,null, ['class' => 'form-control' ]) !!}
            </div>
        </div>

        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                {!! Form::label('Función') !!}
                {!! Form::text('function' ,null, ['class' => 'form-control' ]) !!}
            </div>
        </div>

        {{--<div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                {!! Form::label('Apellido(s)') !!}
                {!! Form::text('surnames' ,null, ['class' => 'form-control' ]) !!}
            </div>
        </div>

        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span style="color: #ff0000" class="required-val">* </span>
                {!! Form::label('Nombre de la empresa') !!}
                {!! Form::text('company' ,null, ['class' => 'form-control' ]) !!}
            </div>
        </div>--}}

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
                    <a  class="btn btn-secondary" href="{{URL::route('providers.index')}}">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>
