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
                            Nóminas
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            {{--<a href="{{URL::to('/employees/createPayroll/'.$employee->id)}}"  class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air" id="btn">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span> Nueva nómina</span>
                                </span>
                            </a>--}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="table-toolbar">
                    <div class="row">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-md-4">
                        <label style="text-align: right">Rango de fechas:</label>
                        <div class='input-group'>
                            {!!Form::text('range',null,['class'=>'form-control m-input', 'placeholder'=>'Selecciona el rango de fechas', 'id'=>'range'])!!}
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <label style="text-align: right">Obra:</label>
                        <div class='input-group'>
                            {!! Form::select('public_work_id', $public_works, null, ['class'=>'form-control', 'placeholder'=>'Seleccione una obra', 'id'=>'public_work_id'])!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label style="text-align: right">&nbsp;</label>
                        <div class='input-group'>
                            <button type="button" id="search-payroll" name="search-payroll" class="btn btn-success mr-10">
                                <i class="fa fa-search"></i> Filtrar
                            </button>&#160;&#160;
                            <button type="button" id="export-excel" name="export-excel" class="btn btn-success mr-10">
                                <i class="fa fa-file-excel"></i> Excel
                            </button>&#160;&#160;
                            <button type="button" id="export-pdf" name="export-pdf" class="btn btn-success mr-10">
                                <i class="fa fa-file-pdf"></i> PDF
                            </button>&#160;&#160;
                            <div style="height: 10px; width: 100%;"></div>
                            <button type="button" id="btn-clonar" name="clonar" class="btn btn-success mr-10">
                                <i class="fa"></i> Clonar
                            </button>&#160;&#160;
                            <button type="button" id="btn-edicion-masiva" name="clonar" class="btn btn-success mr-10">
                                <i class="fa"></i> Edición
                            </button>
                        </div>
                    </div>
                </div>

                <div id="contenedor-clonar" style="display: none;">
                <hr>
                <div class="form-group m-form__group row">

                    <div class="col-md-8">
                        <div class="m-portlet__head" style="padding-left: 0px; border-bottom: 0px;">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        Clonar nómina
                                    </h3>
                                </div>
                            </div>
                        </div>
                        - Para realizar una clonación de nómina, primero debe realizar un filtrado. <br>
                        - Después de filtrar seleccione una fecha de pago para la nueva nómina. <br>
                        - Finalmente clic en hacer clonación. <br><br>


                        {!! Form::open(['route'=>'payrolls.clonar','method'=>'POST', 'id'=>'form-clonar']) !!}

                        <span  style="color: red" class="required-val">* </span>
                        Fecha de pago para la nueva nomina: <br>
                        <div class="row">
                            <div class="col-md-6">

                                {!! Form::date('date', null, ['class' => 'form-control', 'id' => 'fecha-pago']) !!}
                                <input type="hidden" name="clonar-rango" id="clonar-rango">
                                <input type="hidden" name="clonar-obra" id="clonar-obra">

                            </div>
                            <div class="col-md-6">
                                <button type="button" id="btn-hacer-clonacion" name="hacer-clonar" class="btn btn-success mr-10">
                                <i class="fa"></i> Hacer clonación
                            </button>
                            </div>
                        </div>
                        {!!Form::close() !!}


                    </div>
                </div>
                <br>
                <hr>
                <br><br>
                </div>


                <table class="table table-striped- table-bordered table-hover table-checkable display nowrap" id="payrolls-table">
                    <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Sueldo</th>
                        <th>Hrs extras</th>
                        {{-- <th>Bono $</th> --}}
                        <th>Total</th>
                        {{-- <th>Bonos</th> --}}
                        <th>Obra</th>
                        <th>Tipo</th>

                        @if(Auth::user()->role < 3)
                        <th>Acciones</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    {{--
                    @foreach($payrolls as $payroll)
                        <tr class="odd gradeX">
                            <td>
                                @foreach($payroll->Bonuses as $bonus)
                                    <p>{{$bonus->name}}</p>
                                @endforeach
                            </td>
                            <td>
                                @foreach($payroll->Bonuses as $bonus)
                                    <p>{{$bonus->pivot->date}}</p>
                                @endforeach
                            </td>
                            <td>{{$payroll->full_name}}</td>
                            <td>{{$payroll->public_work}}</td>
                            <td>{{$payroll->bank}}</td>
                            <td>{{$payroll->account}}</td>
                            <td>{{$payroll->clabe}}</td>
                            <td>{{$payroll->format_total}}</td>

                            @if(Auth::user()->role < 3)
                            <td>
                                <a href="{{  URL::route('payrolls.edit', $payroll->id) }}" title="Editar" class="btn btn-success" id="button">
                                    <i class="flaticon flaticon-edit"></i><span></span>
                                </a>
                                <a href="#myModal" data-toggle="modal" click=modalDelete data-id="{{$payroll->id}}" title="Eliminar" class="btn btn-danger openBtn">
                                    <i class="flaticon flaticon-delete"></i><span></span>
                                </a>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    --}}
                    </tbody>

                    <tfoot>
                    <tr>
                        <th @if(Auth::user()->role==1) colspan="5" @else colspan="4" @endif style="text-align:right">
                            <p style="text-align: right" id="total-footer">Total: 0</p>
                        </th>
                    </tr>
                    </tfoot>
                </table>

                {{--Formulario para PDF--}}
                {!! Form::open(['url' => '/payrolls/export_pdf', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right', 'style'=>'visible: none', 'id'=>'form_report']) !!}
                    <input type="hidden" name="date_range" value="" id="date_range">
                    <input type="hidden" name="public_work" value="" id="public_work">
                {!!Form::close() !!}

                {{--Formulario para Excel--}}
                {!! Form::open(['url' => '/payrolls/export_excel', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>'m-form m-form--fit m-form--label-align-right', 'style'=>'visible: none', 'id'=>'form_excel']) !!}
                    <input type="hidden" name="date_range_excel" value="" id="date_range_excel">
                    <input type="hidden" name="public_work_excel" value="" id="public_work_excel">
                {!!Form::close() !!}
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" id="bodyDelete"></div>
                    <div class="modal-footer">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <button type="button" data-dismiss="modal" class="btn grey-salt">Cancelar</button>
                        <button type="button"  class="btn yellow-lemon" onclick="deletePayroll()">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mySpecialModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" id="bodySpecialDelete"></div>
                    <div class="modal-footer" id="footerSpecialDelete">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var role = {{Auth::user()->role}};

        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liPayroll').addClass('start');

            var table = $('#payrolls-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },
                "scrollX": true
            });

            $('#btn-clonar').on('click', function(){
                    if(!$(this).hasClass('abierto')){
                        $('#contenedor-clonar').css('display','block');
                        $('#btn-clonar').addClass('abierto');
                    }
                    else{
                        $('#contenedor-clonar').css('display','none');
                        $('#btn-clonar').removeClass('abierto');
                    }
            });
            $('#btn-hacer-clonacion').on('click', function(){
                var range = $('#range').val();
                var public_work_id = $('#public_work_id').val();
                var fecha_pago = $('#fecha-pago').val();
                var ok = 1;

                if(range == ''){
                    ok = 0;
                    alert('Debe seleccionar un rango de fecha.');
                }

                if(public_work_id == ''){
                    public_work_id = 0;
                    //$('#clonar-rango').val();
                }
                if(fecha_pago == ''){
                    ok = 0;
                    alert('Debe seleccionar una fecha de pago.');
                }

                if(ok == 1){

                    $('#clonar-rango').val(range);
                    $('#clonar-obra').val(public_work_id);

                    $('#form-clonar').submit();
                }


            });

            $('#btn-edicion-masiva').on('click', function(){
                var range = $('#range').val();
                var public_work_id = $('#public_work_id').val();
                var ok = 0;


                if(range == ''){
                    ok = 0;
                    alert('Debe seleccionar un rango de fecha.');
                }

                if(public_work_id == ''){
                    public_work_id = 0;
                }

                if(ok == 0){

                     var urledicion = '/payrolls/edicion-masiva/'+range.replace(/ /g, "")+'/'+public_work_id;
                     //https://www.sisega.app/payrolls/edicion-masiva/07-11-2021/07-11-2021/0

                     window.location.href = urledicion;
                }


            });



            $(document).on('click','.abonuscheck', function(){
                let aPayrollid = $(this).data('payrollid');
                let aValor = $(this).val();
                let aChecado = 0;

                if ($(this).is(':checked')) {
                    aChecado = 1;
                }
                else{
                    aChecado = 0;
                }


                 $.ajax({
                    url:'/payrolls/actualizar_bonus',
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    type: 'POST',
                    dataSrc:"",
                    data:{aPayrollid:aPayrollid, aValor:aValor, aChecado:aChecado},
                    success:function(data){
                        if(data[0] == 1){
                            $('#bonostotal-'+aPayrollid).html(data[1]);
                            $('#sueldototal-'+aPayrollid).html(data[2]);
                        }


                    }
                 });





            });


        });

        $('.openBtn').on('click',function(){
            id = $(this).data('id');

            var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar la nómina?");
            nodeName.appendChild(nameNode);
            $("#bodyDelete").empty();
            document.getElementById("bodyDelete").appendChild(nodeName);
        });

        function setModal(id) {
            var nodeName=document.createElement("p");
            var nameNode=document.createTextNode("¿Seguro que desea eliminar la nómina?");
            nodeName.appendChild(nameNode);
            $("#bodySpecialDelete").empty();
            document.getElementById("bodySpecialDelete").appendChild(nodeName);

            var dismiss = document.createElement("BUTTON");
            dismiss.innerHTML = "Cancelar";
            dismiss.setAttribute("data-dismiss", "modal");
            dismiss.setAttribute("class", "btn grey-salt");

            var confirm = document.createElement("BUTTON");
            confirm.innerHTML = "Aceptar";
            confirm.setAttribute("onclick", "specialDeletePayroll(" + id + ")");
            confirm.setAttribute("class", "btn yellow-lemon");

            $("#footerSpecialDelete").empty();
            document.getElementById("footerSpecialDelete").appendChild(dismiss);
            document.getElementById("footerSpecialDelete").appendChild(confirm);
        }

        $('#search-payroll').click(function(e){
            var range = $('#range').val();
            var public_work_id = $('#public_work_id').val();

            if(range === ''){
                swal({
                    "title": "No se puede realizar la búsqueda.",
                    "text": "Se requiere un rango de fechas para llevar a cabo la consulta.",
                    "type": "warning",
                    "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide",
                    "confirmButtonText": "Aceptar"
                });
            }
            else{
                $.ajax({
                    url:'/payrolls/search_payrolls',
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    type: 'POST',
                    dataSrc:"",
                    data:{range:range, public_work_id:public_work_id},
                    success:function(data){
                        if(data === ''){
                            swal({
                                "title": "Error de búsqueda.",
                                "text": "No se encuentran resultados con las fechas especificadas.",
                                "type": "warning",
                                "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide",
                                "confirmButtonText": "Aceptar"
                            });

                            $('#payrolls-table').DataTable().destroy();

                            if(role === 1 || role === 2){
                                $('#payrolls-table').DataTable({
                                    "aoColumns": [
                                        {"bSortable": false },
                                        {"bSortable": false },
                                        {"bSortable": false },
                                        {"bSortable": false },
                                        {"bSortable": false }
                                    ],
                                    "language": {
                                        "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                                    },
                                    "bDestroy": true,
                                    "scrollX": true
                                }).clear().draw();
                            }else{
                                $('#payrolls-table').DataTable({
                                    "aoColumns": [
                                        {"bSortable": false },
                                        {"bSortable": false },
                                        {"bSortable": false },
                                        {"bSortable": false }
                                    ],
                                    "language": {
                                        "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                                    },
                                    "bDestroy": true,
                                    "scrollX": true
                                }).clear().draw();
                            }

                            document.getElementById("total-footer").innerHTML = "Total: 0";
                        }
                        else{

                            $('#payrolls-table').DataTable().destroy();

                            if(role === 1 || role === 2){
                                $('#payrolls-table').DataTable(
                                    {
                                        "data":data[0],
                                        "columns":[
                                            {data:"photography"},
                                            {data:"full_name"},
                                            {data:"sueldo"},
                                            {data:"extras"},
                                            //{data:"bono"},
                                            {data:"total_salary"},
                                             //{data:"htmlbonus"},
                                             {data:"public_work"},
                                             {data:"type"},
                                            {data:"id", render: function (data, type, row) {
                                                if(data[1] == 1){
                                                    return '<a href="/payrolls/' + data[0] + '/edit" title="Editar" class="btn btn-success" id="button"><i class="flaticon flaticon-edit"></i><span></span></a>&nbsp;'+
                                                        '<a href="#mySpecialModal" data-toggle="modal" click=modalDelete title="Eliminar" class="btn btn-danger" onclick="setModal(' + data[0] + ')"><i class="flaticon flaticon-delete"></i><span></span></a>';
                                                }else{
                                                    return '<a href="/payrolls/editPieceworkerPayroll/' + data[0] + '" title="Editar" class="btn btn-success" id="button"><i class="flaticon flaticon-edit"></i><span></span></a>&nbsp;'+
                                                        '<a href="#mySpecialModal" data-toggle="modal" click=modalDelete title="Eliminar" class="btn btn-danger" onclick="setModal(' + data[0] + ')"><i class="flaticon flaticon-delete"></i><span></span></a>';
                                                }
                                            }}
                                        ],
                                        "language": {
                                            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                                        },
                                        "scrollX": true,
                                        "dom": 'Bfrtip',
                                        "buttons": [{
                                            "extend": 'excel',
                                            "title": '',
                                            "exportOptions": {
                                                "columns": [0, 1, 2, 3, 4, 5]
                                            },
                                            "customize": function (xlsx) {
                                                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                                                $('row c[r^="D"]', sheet).attr( 's', '52' );
                                                $('row c[r="D1"]', sheet).attr( 's', ['2', '50'] );

                                                $('row c[r^="E"]', sheet).attr( 's', '52' );
                                                $('row c[r="E1"]', sheet).attr( 's', ['2', '52'] );
                                            }
                                        }
                                        ]
                                    }
                                );
                            }else{
                                $('#payrolls-table').DataTable(
                                    {
                                        "data":data[0],
                                        "columns":[
                                            {data:"photography"},
                                            {data:"full_name"},
                                            {data:"sueldo"},
                                            {data:"extras"},
                                            {data:"bono"},
                                            {data:"total_salary"},
                                             {data:" "},
                                            {data:"public_work"},
                                            {data:"type"},
                                        ],
                                        "language": {
                                            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                                        },
                                        "scrollX": true,
                                        "dom": 'Bfrtip',
                                        "buttons": [{
                                            "extend": 'excel',
                                            "title": '',
                                            "exportOptions": {
                                                "columns": [0, 1, 2, 3, 4, 5]
                                            }
                                        }
                                        ]
                                    }
                                );
                            }

                            document.getElementById("total-footer").innerHTML = "Total: " + data[1];
                        }
                    }
                });
            }
        });

        $("#export-excel").on("click", function(e) {
            /*$('#payrolls-table').DataTable().button('.buttons-excel').trigger();*/
            //alert('hola excel');
            e.preventDefault();

            var token = $("#token").val();

            var range = $('#range').val();
            var public_work_id = $('#public_work_id').val();

            $('#date_range_excel').val(range);
            $('#public_work_excel').val(public_work_id);

            document.getElementById("form_excel").submit();
        });

        $("#export-pdf").click(function (e) {
            e.preventDefault();

            var token = $("#token").val();

            var range = $('#range').val();
            var public_work_id = $('#public_work_id').val();

            $('#date_range').val(range);
            $('#public_work').val(public_work_id);

            document.getElementById("form_report").submit();

            /*var formData = new FormData();

            formData.append('range', range);
            formData.append('public_work_id', public_work_id);

            $.ajax({
                url: "/payrolls/export_pdf",
                headers: {'X-CSRF-TOKEN': token},
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                }
            });*/
        });

        $('#range').daterangepicker({
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " / ",
                "applyLabel": "Guardar",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sá"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Setiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            "opens": "center"
        });

        function deletePayroll(){
            var token = $("#token").val();

            $.ajax({
                url: "payrolls/"+id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/payrolls";
                    $("#message").fadeIn();
                }
            });
        }

        function specialDeletePayroll(payroll_id) {
            var token = $("#token").val();

            $.ajax({
                url: "payrolls/"+ payroll_id,
                headers: {'X-CSRF-TOKEN': token},
                type: "DELETE",
                success: function() {
                    window.location = "/payrolls";
                    $("#message").fadeIn();
                }
            });
        }
    </script>
@endsection
