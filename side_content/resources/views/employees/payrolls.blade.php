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
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Nóminas - {{$employee->name}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            @if($employee->type == 1)
                                <a href="{{URL::to('/employees/createPayroll/'.$employee->id)}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nueva nómina</span>
                                </span>
                                </a>
                            @else
                                <a href="{{URL::to('/employees/createPieceworkerPayroll/'.$employee->id)}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nueva nómina</span>
                                </span>
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="table-toolbar">
                    <div class="row">
                    </div>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable display responsive nowrap" id="payrolls-table">
                    <thead>
                    <tr>
                        <th>Obra</th>
                        <th>Número de días trabajados</th>
                        <th>Horas trabajadas</th>
                        <th>Horas extras</th>
                        <th>Comentarios</th>
                        <th>Bonos</th>
                        {{--<th>Fecha del día que se ganó el bono</th>--}}
                        <th>Sueldo total a pagar</th>
                        <th>Fecha del pago</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payrolls as $payroll)
                        <tr class="odd gradeX">
                            <td>{{$payroll->public_work}}</td>
                            <td>{{$payroll->days_worked}}</td>
                            <td>{{$payroll->hours_worked}}</td>
                            <td>{{$payroll->extra_hours}}</td>
                            <td>{{$payroll->comments}}</td>
                            <td>
                                @foreach($payroll->Bonuses as $bonus)
                                    <p>{{$bonus->name}}</p>
                                @endforeach
                            </td>
                            {{--<td>
                                @foreach($payroll->Bonuses as $bonus)
                                    <p>{{$bonus->pivot->date}}</p>
                                @endforeach
                            </td>--}}
                            <td>{{$payroll->format_total}}</td>
                            <td>{{$payroll->date}}</td>
                            <td>
                                @if($employee->type == 1)
                                    <a href="{{URL::to('/employees/editPayroll', $payroll->id)}}" title="Editar" class="btn btn-success" id="button">
                                        <i class="flaticon flaticon-edit"></i><span></span>
                                    </a>
                                @else
                                    <a href="{{URL::to('/employees/editPieceworkerPayroll', $payroll->id)}}" title="Editar" class="btn btn-success" id="button">
                                        <i class="flaticon flaticon-edit"></i><span></span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="9" style="text-align:right">
                            <p style="text-align: right">Total: {{$total}}</p>
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liEmployees').addClass('start');

            $('#payrolls-table').dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                }
            });
        });
    </script>
@endsection