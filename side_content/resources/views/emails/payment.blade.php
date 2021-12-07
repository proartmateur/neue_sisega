<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body style="height: 750px;">
<div class="content">
    <div class="phase">
        <p>{{$data->user}} ha registrado un pago pendiente:</p>
        <p> <span style="font-weight: bold;">Fecha: </span> {{$data->date}}</p>
        <p> <span style="font-weight: bold;">Banco: </span> {{$data->provider_bank}}</p>
        <p> <span style="font-weight: bold;">Cuenta: </span> {{$data->provider_account}}</p>
        <p> <span style="font-weight: bold;">CLABE: </span> {{$data->provider_clabe}}</p>
        <p> <span style="font-weight: bold;">Monto: </span> {{$data->amount}}</p>
        <p> <span style="font-weight: bold;">Comentarios: </span> {{$data->comments}}</p>
    </div>
</div>
</body>
</html>