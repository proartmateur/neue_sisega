@extends('layouts.main')

@section('breadcrumb')

    <h3 class="m-subheader__title m-subheader__title--separator">Obras</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/public-works')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Obras</span>
            </a>
        </li>
    </ul>

@endsection

@section('content')
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="m-portlet m-portlet--tab">
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Detalle de obra
                        </h3>
                    </div>
                </div>
            </div>
     
            <div class="m-portlet__body">
                <div class="table-toolbar">
                    <div class="row">
                    </div>
                </div>
                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="mtable1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Presupuesto</th>
                            <th>Residentes</th>
                            <th>Fecha fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="odd gradeX">
                            <td>{{$public_work->name}}</td>
                            <td>{{$public_work->budget}}</td>
                            <td>
                                @if (isset($public_work->supervisors))
                                    @foreach($public_work->supervisors as $supervisor)
                                        <li>{{$supervisor->name}}</li>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{$public_work->end_date}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>

        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions">
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-10">
                        <a  class="btn btn-secondary" href="{{URL::route('public-works.index')}}">Cerrar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liPublic_works').addClass('start');
        });
    </script>

@endsection