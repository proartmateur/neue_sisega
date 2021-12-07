<div data-repeater-list="concepts_list" class="col-lg-12">
    <div data-repeater-item class="form-group m-form__group row align-items-center concept">
        <div class="col-2"></div>
        <div class="col-lg-5">
            <span style="color: red" class="required-val">* </span>
            {!! Form::label('Concepto') !!}
            {!! Form::text('concept', null, ['class' => 'form-control']) !!}

            <span style="color: red" class="required-val">* </span>
            {!! Form::label('Unidad') !!}
            {!! Form::text('measurement', null, ['class' => 'form-control']) !!}

            <span style="color: red" class="required-val">* </span>
            {!! Form::label('Cantidad') !!}
            {!! Form::number('quantity', null, ['class' => 'form-control quantity', 'step'=>'.01', 'id'=>'quantity', 'onkeyup'=>'calculate()']) !!}

            {{--<span style="color: red" class="required-val">* </span>--}}
            {{--{!! Form::label('P.U. SISEGA') !!}
            {!! Form::number('sisega_price', null, ['class' => 'form-control', 'step'=>'.01']) !!}--}}

            <span style="color: red" class="required-val">* </span>
            {!! Form::label('P.U. compra') !!}
            {!! Form::number('purchase_price', null, ['class' => 'form-control purchase_price', 'step'=>'.01', 'id'=>'purchase_price', 'onkeyup'=>'calculate()']) !!}

            <div class="d-md-none m--margin-bottom-10"></div>
        </div>

        <div class="col-md-4">
            <div data-repeater-delete="" class="btn-sm btn btn-danger m-btn m-btn--icon m-btn--pill delete_concept">
                <span>
                    <i class="la la-trash-o"></i>
                    <span>Eliminar</span>
                </span>
            </div>
        </div>

        <hr style="border-top: 3px solid #bbb;">
    </div>
</div>