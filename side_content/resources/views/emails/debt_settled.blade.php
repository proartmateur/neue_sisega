<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body style="height: 750px;">
<div class="content">
    <div class="phase">
        <p>Detalle de pago realizado:</p>
        <p> <span style="font-weight: bold;">Fecha: </span> {{$notification->date}}</p>
        <p> <span style="font-weight: bold;">Monto: </span> {{$notification->amount}}</p>
        <p> <span style="font-weight: bold;">Comentarios: </span> {{$notification->comments}}</p>
    </div>
</div>
</body>
</html>