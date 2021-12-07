<div class="m-portlet m-portlet--tab">
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Nombre') !!}
                {!! Form::text('name' ,null, ['class' => 'form-control' ]) !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span style="color: #ff0000" class="required-val">* </span>
                {!! Form::label('Email') !!}
                {!! Form::text('email' ,null, ['class' => 'form-control' ]) !!}
            </div>    
        </div>
        <div class="form-group m-form__group row">
        <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Contraseña') !!}
                <!-- {!! Form::password('password' ,null, ['class' => 'form-control' ]) !!} -->
                <input name="password" class="form-control"  type="password" />
            </div>    
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Puesto') !!}
                {!! Form::text('stall' ,null, ['class' => 'form-control' ]) !!}
            </div>    
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Rol') !!}
                {!! Form::select('role',['1' => 'Administrador', '2' => 'Residente'], null,['class'=>'form-control', 'placeholder'=>'Seleccione un rol', 'id'=>'role'])!!}

            </div>    
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Estatus') !!}
                {!! Form::select('status',['1' => 'Activo', '2' => 'Suspendido'], null,['class'=>'form-control', 'placeholder'=>'Seleccione un estatus', 'id'=>'status'])!!}
            </div>
        </div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit">
        <div class="m-form__actions">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-10">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a  class="btn btn-secondary" href="{{URL::route('users.index')}}">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>

