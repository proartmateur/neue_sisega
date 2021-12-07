<div data-repeater-list="bonuses_list" class="col-lg-12">
    @if($count>0)
        @foreach($payroll->Bonuses as $bonus)
            <div data-repeater-item class="form-group m-form__group row align-items-center bonus">
                <div class="col-2"></div>
                <div class="col-lg-5">
                    <span style="color: red" class="required-val">* </span>
                    {!! Form::label('Concepto') !!}
                    {!! Form::select('bonus_id', $bonuses_array, $bonus->id, ['class'=>'form-control bonus_id', 'placeholder'=>'Ninguno - $0', 'id'=>'bonus_id', 'onchange'=>'calculate()'])!!}

                    {{--<span style="color: red" class="required-val">* </span>
                    {!! Form::label('Fecha') !!}
                    {!! Form::date('bonus_date', $bonus->pivot->date, ['class' => 'form-control']) !!}--}}

                    <div class="d-md-none m--margin-bottom-10"></div>
                </div>

                <div class="col-md-4">
                    <div data-repeater-delete="" class="btn-sm btn btn-danger m-btn m-btn--icon m-btn--pill delete_bonus">
                        <span>
                            <i class="la la-trash-o"></i>
                            <span>Eliminar</span>
                        </span>
                    </div>
                </div>

                <hr style="border-top: 3px solid #bbb;">
            </div>
        @endforeach
    @else
        <div data-repeater-item class="form-group m-form__group row align-items-center bonus">
            <div class="col-2"></div>
            <div class="col-lg-5">
                <span style="color: red" class="required-val">* </span>
                {!! Form::label('Concepto') !!}
                {!! Form::select('bonus_id', $bonuses_array, null, ['class'=>'form-control bonus_id', 'placeholder'=>'Ninguno - 0', 'id'=>'bonus_id', 'onchange'=>'calculate()'])!!}

                {{--<span style="color: red" class="required-val">* </span>
                {!! Form::label('Unidad') !!}
                {!! Form::date('bonus_date', null, ['class' => 'form-control']) !!}--}}

                <div class="d-md-none m--margin-bottom-10"></div>
            </div>

            <div class="col-md-4">
                <div data-repeater-delete="" class="btn-sm btn btn-danger m-btn m-btn--icon m-btn--pill delete_bonus">
                <span>
                    <i class="la la-trash-o"></i>
                    <span>Eliminar</span>
                </span>
                </div>
            </div>

            <hr style="border-top: 3px solid #bbb;">
        </div>
    @endif
</div>