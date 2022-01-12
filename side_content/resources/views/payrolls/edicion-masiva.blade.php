@extends('layouts.main')

@section('styles')
    <style>
        .dt-buttons{
            display: none;
        }
        .aIcon{
            height: 100px;
            width: 100px;
            display: block;
            margin: 0px auto;
            background-size: cover;
            background-position:  center;
            border-radius: 4px;
        }
        table.table-bordered.dataTable tbody td{
            text-align: center;
        }
        table.dataTable thead tr th{
            text-align: center;
        }
    </style>
@endsection

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Nóminas</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/payrolls')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Nóminas</span>
            </a>
        </li>
    </ul>

@endsection

@section('content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Nóminas edición masiva
                    </h3>
                </div>
            </div>
        </div>
    </div>


        <style type="text/css">
            .linea-campos-nomina{
                margin-bottom: 20px;
            }
            .nominas-juntas{
                padding: 20px 10px 20px 20px;
                background-color: #ffffff;
                margin-bottom: 40px;
            }
            .nj-commentarios{
                height: 100px !important;
            }
            .nj-nombre{
                font-weight: bold;
                font-size: 18px;
                font-family: Roboto;
                color: #575962;
                float: left;
            }
            .aIcon.foto{
            	background-color: #cdcdcd;
            	float: left;
            	margin: 0px 20px 0px 0px;
            }
        </style>
        <?php /* 'route'=>'payrolls.clonar' */?>


        @foreach($query as $q)
         {!! Form::model($q, ['route'=>'payrolls.edicion.masiva.guarda.una.nomina', 'method'=>'POST', 'id'=>'form-clonar-'.$q->id ]) !!}
         <?php /*{{method_field('PUT')}}*/?>


        <div class="nominas-juntas">
            <div class="row linea-campos-nomina">
                <div class="col-xs-12 col-md-6">

                    <div class="aIcon foto" style="background-image: url('https://www.sisega.app/{{$q->foto}}')"></div>
                    <div class="nj-nombre">{!!$q->name_empleado!!} <br> {!!($q->tipo_empleado == 1)?'Empleado':'Destajista'!!}</div>
                    <input class="form-control" name="id" type="hidden" value="{!! $q->id !!}">
                    <input class="form-control" name="employee_id" type="hidden" value="{!! $q->employee_id !!}">

                    <input class="form-control" name="tipo_empleado" type="hidden" value="{!! $q->tipo_empleado !!}">
                    <input class="form-control" name="salario" id="salario_{!! $q->id !!}" type="hidden" value="{{$q->salary_week}}">
                </div>
            </div>
            <div class="row linea-campos-nomina">
                <div class="col-xs-12 col-md-5">
                     <span style="color: red" class="required-val">* </span>Obra <br>

                      {!! Form::select('public_work_id', $public_works, null, ['class'=>'form-control', 'placeholder'=>'Seleccione una obra', 'id'=>'public_work_id'])!!}
                </div>
            </div>


             <div class="row">
                @if($q->tipo_empleado == 1)
                <input class="form-control" name="total_salary" id="total_salary_{!!$q->id!!}" type="hidden" value="{!! $q->total_salary !!}">
                <div class="col-xs-12 col-md-10">
                    <div class="row linea-campos-nomina">
                        <div class="col-xs-12 col-md-3">
                            <span style="color: red" class="required-val">* </span> Fecha Del Pago <br>
                            <input class="form-control" name="date" type="date" value="{!! $q->date !!}">
                        </div>


                        <div class="col-xs-12 col-md-3">
                            <span style="color: red" class="required-val">* </span> Días Trabajados <br>
                            <input class="form-control days_worked" id="days_worked_{!!$q->id!!}" name="days_worked" type="number" value="{!! $q->days_worked !!}"  data-formid="{!!$q->id!!}">
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="color: red" class="required-val">* </span> Horas Trabajadas <br>
                            <input class="form-control hours_worked" id="hours_worked_{!!$q->id!!}" readonly="" name="hours_worked" type="number" value="{!! $q->hours_worked !!}" data-formid="{!!$q->id!!}">
                        </div>
                        <div class="col-xs-12 col-md-3">
                            Horas Extras <br>
                            <input class="form-control extra_hours" id="extra_hours_{!!$q->id!!}" name="extra_hours" type="number" value="{!! $q->extra_hours !!}"  data-formid="{!!$q->id!!}">
                        </div>


                    </div>
                    <div class="row linea-campos-nomina">
                        <div class="col-xs-12 col-md-6">
                            Comentarios <br>
                            <textarea class="form-control nj-commentarios" name="comments" cols="50" rows="10">{!! $q->comments !!}</textarea>
                        </div>
                        <div class="col-xs-12 col-md-3">

                            <!--Bonos <br>-->
                            <?php

                            /*

                            $payroll2 = \App\Payroll::find($q->id);

                            $total_bonus = 0;

                            $bonustiene = '';
                            $bonus_uniforme = '';
                            $bonus_asistencia = '';

                            foreach ($payroll2->Bonuses as $bonus){
                                $total_bonus += (int)$bonus->amount;

                                if($bonus->id == 1){
                                    $bonus_uniforme = 'checked';
                                }
                                else{
                                     $bonus_asistencia = 'checked';
                                }

                            }








                            foreach ($bonus_todos as $b){

                                if($b->id == 1){


                                    echo $b->name.' &nbsp;<input type="checkbox" id="bonus-1-'.$q->id.'" value="1" class="abonuscheck" data-payrollid="'.$q->id.'" '.$bonus_uniforme.' name="bono_uniforme" data-valor="'.$b->amount.'"><br><br>';
                                }
                                else{


                                    echo $b->name.' <input type="checkbox" id="bonus-2-'.$q->id.'" value="2" class="abonuscheck" data-payrollid="'.$q->id.'" '.$bonus_asistencia.'  name="bono_asistencia" data-valor="'.$b->amount.'">';
                                }

                            }
                            */
                            ?>

                        </div>
                        <div class="col-xs-12 col-md-3">
                            Total A Pagar <br>
                            <?php echo  "<div id='total-txt-".$q->id."'>$".number_format($q->total_salary,'2','.',',')."</div>";?>
                             <br><br>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-xs-12 col-md-10">
                    <div class="row linea-campos-nomina">
                        <div class="col-xs-12 col-md-3">
                            <span style="color: red" class="required-val">* </span> Fecha Del Pago <br>
                            <input class="form-control" name="date" type="date" value="{!! $q->date !!}">
                        </div>


                        <div class="col-xs-12 col-md-3">
                         <span style="color: red" class="required-val">* </span> Total A Pagar <br>
                            <input class="form-control" step=".01" id="total_salary_{!!$q->id!!}" name="total_salary" type="number" value="{!! $q->total_salary !!}">


                        </div>
                        <div class="col-xs-12 col-md-3">

                        </div>
                        <div class="col-xs-12 col-md-3">

                        </div>


                    </div>
                    <div class="row linea-campos-nomina">
                        <div class="col-xs-12 col-md-6">
                            Comentarios <br>
                            <textarea class="form-control nj-commentarios" name="comments" cols="50" rows="10">{!! $q->comments !!}</textarea>
                        </div>
                        <div class="col-xs-12 col-md-3">
                        </div>
                        <div class="col-xs-12 col-md-3">

                        </div>
                    </div>
                </div>
                @endif
                <div class="col-xs-12 col-md-2">
                    <div class="row">
                        <div class="col-xs-12">
                            <br>
                            <button type="button" class="btn-guardar-clon btn btn-success mr-10" data-formid="{!!$q->id!!}" id="btn-guardar-clon-{!!$q->id!!}">
                                    <i class="fa"></i> Guardar
                            </button>
                            <div id="mensaje-ajax-{!!$q->id!!}"></div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
         {!!Form::close() !!}
        @endforeach

</div>
@endsection

@section('scripts')
    <script type="text/javascript">


        function calculate(id) {
            var salary = $("#salario_"+id).val();
            var days_worked = $("#days_worked_"+id).val();
            var extra = $("#extra_hours_"+id).val();
            var days = days_worked !== '' ? days_worked: 0;
            var dairy = parseFloat(salary)/6;
            var total = days * parseFloat(dairy);

            if(extra!==''){
                var extra_amount = parseInt(extra);
                total = total + extra_amount;
            }

            var bonus_total = 0;

            if($('#bonus-1-'+id).is(':checked')){
                bonus_total = bonus_total + $('#bonus-1-'+id).data('valor');
            }
            if($('#bonus-2-'+id).is(':checked')){
                bonus_total = bonus_total + $('#bonus-2-'+id).data('valor');
            }
            total = total + bonus_total;

            const moneyFormat = (value) =>
            new Intl.NumberFormat('en-US', {
                // style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            }).format(value);

            var format_total = '$'+moneyFormat(total);
            $('#total-txt-'+id).html(format_total);
            $('#total_salary_'+id).val(total);
        }

        $(document).ready(function(){

             $(".days_worked").on("keyup keydown change",function(event){
                var formid = $(this).data('formid');
                var hours = $(this).val() != '' ? parseInt($(this).val())*8 : 0;
                $('#hours_worked_'+formid).val(hours);

                calculate(formid);
            });

            $(".extra_hours").on("keyup keydown change",function(event){
                var formid = $(this).data('formid');
                calculate(formid);
            });




            $(document).on('click','.abonuscheck', function(){
                let aPayrollid = $(this).data('payrollid');
                let aValor = $(this).val();
                let aPrecio = $(this).data('valor');
                let aChecado = 0;

                if ($(this).is(':checked')) {
                    aChecado = 1;
                }
                else{
                    aChecado = 0;
                }
                calculate(aPayrollid);
            });








            $('.btn-guardar-clon').on('click', function(){
                var formid = $(this).data('formid');

                var valores = $('#form-clonar-'+formid).serialize();

                $('#btn-guardar-clon-'+formid).css('display','none');
                $('#mensaje-ajax-'+formid).html('Guardando...');


                 //alert('Datos serializados: '+valores);

                //$('#form-clonar-'+formid).submit();


                 $.ajax({
                    url: '{{route('payrolls.edicion.masiva.guarda.una.nomina')}}',
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    type: 'POST',
                    dataSrc:"",
                    data:valores,
                    success:function(data){

                        if(data == 1){
                            $('#btn-guardar-clon-'+formid).css('display','block');
                            $('#mensaje-ajax-'+formid).html('Datos guardados.');
                        }
                        else{
                            $('#btn-guardar-clon-'+formid).css('display','block');
                            $('#mensaje-ajax-'+formid).html('Ocurrio un error, recargue y vuelva a intentar.');
                        }






                        /*if(data[0] == 1){
                            $('#bonostotal-'+aPayrollid).html(data[1]);
                            $('#sueldototal-'+aPayrollid).html(data[2]);
                        }*/


                    }
                 });
            });

        });
    </script>
@endsection
