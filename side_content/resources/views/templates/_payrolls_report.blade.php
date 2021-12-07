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
            width: 33.33333%;
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
            font-size: 11pt;
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
                @foreach($public_works as $public_work)
                    <tr>
                        <td style="text-align: center"><span><b>{{$public_work->name}}</b></span></td>
                        <td style="text-align: center"><span><b>{{$public_work->amount}}</b></span></td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="column">
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
        </div>
    </div>

    <div style="width: 100%">
        <table class="payrolls_table" style="width: 100%">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Bono</th>
                    <th>Hrs. extra</th>
                    <th>Total</th>
                    <th>Obra</th>
                    <th>Banco</th>
                    <th>Cuenta</th>
                    <th>CLABE</th>
                    <th>Puesto</th>
                    <th>Firma</th>
                </tr>
            </thead>
            <tbody>
            @foreach($array as $item)
                <tr class="odd gradeX">
                    <td>{{$item->full_name}}</td>
                    <td>{{$item->total_bonus}}</td>
                    <td>{{$item->extra_hours}}</td>
                    <td>{{$item->total_salary}}</td>
                    <td>{{$item->public_work}}</td>
                    <td>{{$item->bank}}</td>
                    <td>{{$item->account}}</td>
                    <td>{{$item->clabe}}</td>
                    <td>{{$item->stall}}</td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>