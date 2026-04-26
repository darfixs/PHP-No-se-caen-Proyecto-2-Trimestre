<!DOCTYPE html>
{{--
    Plantilla de la factura PDF.
    La convierte a PDF la clase App\Clases\FacturaPdf usando DomPDF.
    Usamos estilos inline/simples porque DomPDF tiene soporte limitado
    de CSS (nada de flexbox ni grid).
--}}
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $cuota->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { color: #0d47a1; margin: 0 0 6px 0; font-size: 20px; }
        h2 { color: #0d47a1; font-size: 14px; margin: 18px 0 6px 0; }
        .cabecera { border-bottom: 2px solid #0d47a1; padding-bottom: 10px; margin-bottom: 14px; }
        .caja {
            border: 1px solid #ccc;
            padding: 8px 10px;
            margin-bottom: 10px;
            width: 100%;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table th, table td {
            border: 1px solid #bbb;
            padding: 6px 8px;
            text-align: left;
        }
        table th { background-color: #e3f2fd; color: #0d47a1; }
        .total-row td { font-weight: bold; background: #f3f6ff; font-size: 13px; }
        .pie { margin-top: 30px; font-size: 11px; color: #666; text-align: center; }
        .estado-pagada { color: #2e7d32; font-weight: bold; }
        .estado-pendiente { color: #ef6c00; font-weight: bold; }
    </style>
</head>
<body>

    <div class="cabecera">
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 60%;">
                    <h1>Nosecaen S.L.</h1>
                    <div>Grupo SiempreColgados</div>
                    <div>CIF: B-99999999</div>
                    <div>Huelva, España</div>
                </td>
                <td style="border: none; text-align: right;">
                    <h1>FACTURA</h1>
                    <div><strong>Nº:</strong> {{ str_pad($cuota->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div><strong>Fecha:</strong> {{ $cuota->fecha_emision?->format('d/m/Y') }}</div>
                    <div>
                        <strong>Estado:</strong>
                        @if($cuota->pagada)
                            <span class="estado-pagada">PAGADA</span>
                        @else
                            <span class="estado-pendiente">PENDIENTE</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <h2>Datos del cliente</h2>
    <div class="caja">
        <strong>{{ $cliente->nombre }}</strong><br>
        CIF: {{ $cliente->cif }}<br>
        Teléfono: {{ $cliente->telefono }}<br>
        Correo: {{ $cliente->correo }}<br>
        País: {{ $cliente->pais?->nombre }}<br>
        @if($cliente->cuenta_corriente)
            IBAN: {{ $cliente->cuenta_corriente }}
        @endif
    </div>

    <h2>Detalle</h2>
    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th style="width: 120px; text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $cuota->concepto }}</td>
                <td style="text-align: right;">
                    {{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->moneda }}
                </td>
            </tr>
            <tr class="total-row">
                <td style="text-align: right;">TOTAL</td>
                <td style="text-align: right;">
                    {{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->moneda }}
                </td>
            </tr>
        </tbody>
    </table>

    @if($cuota->pagada && $cuota->fecha_pago)
        <p style="margin-top: 12px;">
            <strong>Pagada el:</strong> {{ $cuota->fecha_pago?->format('d/m/Y') }}
        </p>
    @endif

    @if($cuota->notas)
        <h2>Notas</h2>
        <div class="caja">{{ $cuota->notas }}</div>
    @endif

    <div class="pie">
        Esta factura ha sido generada automáticamente por la aplicación
        de gestión de Nosecaen S.L.
    </div>

</body>
</html>
