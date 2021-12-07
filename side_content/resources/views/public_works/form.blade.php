<div class="m-portlet m-portlet--tab">
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span style="color:red" class="required-val">* </span>
                {!! Form::label('Nombre de la obra')!!}
                {!! Form::text('name',null, ['class' => 'form-control' ]) !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5" >
                {{--<span style="color:red" class="required-val">* </span>--}}
                {!! Form::label('Presupuesto de la obra') !!}
                {{--@if(isset($public_work))
                    <input type="text" name="budget" class="form-control" id="budget" value="{{$public_work->budget}}">
                @else
                    <input type="text" name="budget" class="form-control" id="budget" value="0.00">
                @endif--}}
                {!! Form::number('budget', null, ['class' => 'form-control', 'step'=>'.01']) !!}

                {{--{!! Form::text('budget',null, ['class' => 'form-control', 'id' => 'm_inputmask_7']) !!}--}}
            </div>
        </div>
        {{--<div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span style="color:red" class="required-val">* </span>
                {!! Form::label('Residente de la obra') !!}
                {!! Form::select('supervisor',$supervisor, null,['class'=>'form-control', 'placeholder'=>'Seleccione un residente', 'id'=>'supervisor'])!!}
            </div>
        </div>--}}
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Residentes de la obra') !!}
                <select class="itemName form-control js-example-basic-multiple" multiple  style = " width : 100% ; "  name="supervisors[]" id="supervisors" lang="es">
                    @foreach($supervisors as $supervisor)
                        <?php $selected_supervisors = '';  ?>
                        @if(isset($public_work->supervisors))
                            @foreach($public_work->supervisors as $item)
                                @if($item->pivot->user_id == $supervisor->id)
                                    <?php $selected_supervisors = 'selected';  ?>
                                    @break
                                @endif
                            @endforeach
                        @endif
                        <option value="{{$supervisor->id}}"  {{$selected_supervisors}}>{{$supervisor->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Fecha de inicio') !!}
                {!! Form::date('start_date', null, ['class' => 'form-control' ]) !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span  style="color: red" class="required-val">* </span>
                {!! Form::label('Fecha final') !!}
                {!! Form::date('end_date', null, ['class' => 'form-control' ]) !!}
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
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit">
        <div class="m-form__actions">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-10">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a class="btn btn-secondary" href="{{URL::route('public-works.index')}}">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>