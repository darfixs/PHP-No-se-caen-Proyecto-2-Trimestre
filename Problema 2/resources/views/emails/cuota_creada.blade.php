<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; color: #222;">

    <p>Estimado/a cliente de <strong>{{ $cliente->nombre }}</strong>,</p>

    <p>
        Le informamos de que se ha emitido una nueva cuota correspondiente al
        servicio de mantenimiento prestado por <strong>Nosecaen S.L.</strong>:
    </p>

    <ul>
        <li><strong>Nº factura:</strong> {{ str_pad($cuota->id, 6, '0', STR_PAD_LEFT) }}</li>
        <li><strong>Concepto:</strong> {{ $cuota->concepto }}</li>
        <li><strong>Fecha de emisión:</strong> {{ $cuota->fecha_emision?->format('d/m/Y') }}</li>
        <li>
            <strong>Importe:</strong>
            {{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->moneda }}
        </li>
    </ul>

    <p>Adjunto a este correo encontrará la factura en formato PDF.</p>

    <p>Si tiene cualquier duda, puede responder a este correo o ponerse
       en contacto con nosotros.</p>

    <p>Un saludo,<br>
       <strong>Nosecaen S.L.</strong> · Grupo SiempreColgados
    </p>

</body>
</html>
