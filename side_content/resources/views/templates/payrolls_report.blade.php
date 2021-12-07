<!DOCTYPE html>
<html lang="en" style="overflow-x: hidden">
<head>
    <meta charset="utf-8">
    <style>
        .bold {
            font-size: 11pt;
        }
        * {
            box-sizing: border-box;
        }

        .row{
            width: 100%;
            display: flex;
            min-height: 100px;
        }

        .column {
            float: left;
            width: 100%;
            /*height: 300px;*/
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .public_works_amounts tr:nth-child(even) {background-color: #f2f2f2;}

        .payrolls_table th, td{
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .payrolls_table_2 th, td{
            height: 25pt;
        }
        .payrolls_table td.chico{
            font-size: 9pt;
        }

        .payrolls_table th {
            background-color: lightblue;
        }
    </style>
</head>
<body style="overflow-x: hidden;">
    <div style="width: 100%; height: 20px; background-color: black">
        <p class="bold" style="color: white; text-align: center; margin: auto">
            {{mb_strtoupper('nÓmina semanal')}}
        </p>
    </div>

    <div class="row">
        <div class="column">

            <table class="public_works_amounts" style="width: 100%">

                <tr>
                    <td style="width: 40%;">
                        <table class="public_works_amounts" style="width: 100%">

                            @foreach($total_por_obra as $clave => $valor)
                                <tr>
                                    <td style="text-align: center"><span><b>{{$clave}}</b></span></td>
                                    <td style="text-align: center"><span><b>${{number_format(ceil($valor),'2','.',',')}}</b></span></td>
                                </tr>
                            @endforeach

                                <tr>
                                    <td style="text-align: center" bgcolor="lightblue"><span><b>TOTAL</b></span></td>
                                    <td style="text-align: center"><span><b>{{$total_salarios}}</b></span></td>
                                </tr>
                               
                        </table>
                    </td>
               
                    <td  style="width: 60%">
                         <table style="width: 100%; border: 0px;">
                             <tr style="border: 0px;">
                                        <td style="text-align: center; font-size: 20pt; border: 0px;"><span><b>Fecha: {{$end}}</b></span></td>
                                        
                             </tr>
                         </table>
                        
                    </td>
                </tr>
               
            </table>
            
        </div>
        
        <?php /*<div class="column">
            <div style="width: 100%; background-color:green; height: {{$size}}px">
                <span style="font-size: 1.9rem; font-weight: 600;">{{$total}}</span>
                {{--<p style="text-align: center; margin: auto; background-color:green; font-size: 1.9rem; font-weight: 600;">
                    {{$total}}
                </p>--}}
            </div>
        </div> 
        <div class="column">
            <div style="width: 100%; background-color:yellow; height: {{$size}}px">
                <span style="font-size: 1.9rem; font-weight: 600;">{{$end}}</span>
                {{--<p style="text-align: center; margin: auto; background-color:yellow; font-size: 1.9rem; font-weight: 600;">
                    {{$end}}
                </p>--}}
            </div>
        </div>*/?>
    </div>

    <div style="width: 100%">
        <table class="payrolls_table payrolls_table_2" style="width: 100%">
            <thead>
                <tr>
                    <th style="width: 15%;">Nombre completo</th>
                    <th>Bono</th>
                    <th>Hrs. extra</th>
                    <th>Total</th>
                    <th>Obra</th>
                    <th>Banco</th>
                    <th>Cuenta</th>
                    <th>CLABE</th>
                    <th>Tipo</th>
                    <th style="width: 10%;">Firma</th>
                </tr>
            </thead>
            <tbody>
            @foreach($array as $item)
                <tr class="odd gradeX">
                    <td>{{$item->full_name}}</td>
                    <td style="text-align: center">{{$item->total_bonus}}</td>
                    <td style="text-align: center">{{$item->extra_hours}}</td>
                    <td style="text-align: center">{{$item->total_salary}}</td>
                    <td style="text-align: center" class="chico">{{$item->public_work}}</td>
                    <td style="text-align: center" class="chico">{{$item->bank}}</td>
                    <td style="text-align: center">{{$item->account}}</td>
                    <td style="text-align: center">{{$item->clabe}}</td>
                    <td style="text-align: center">{{$item->stall}}</td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>