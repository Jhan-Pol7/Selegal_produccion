<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ticket de cita {{ $a->id }}</title>
<style>
@page { size: 80mm auto; margin: 3mm; }
* { box-sizing: border-box; }
body { font: 12px Arial, sans-serif; color: #000; width: 70mm; max-width: 100%; margin: 0 auto; text-align: center; }
h1 { font-size: 17px; margin: 4px; } h2 { font-size: 14px; margin: 12px; } p { margin: 7px 0; }
.rule { border-top: 1px dashed #000; margin: 10px 0; }
.details { text-align: left; overflow-wrap: anywhere; }
.barcode { display: block; width: 60mm; max-width: 100%; height: 17mm; margin: 8px auto 2px; }
.print { margin: 20px; }
@media print { .print { display: none; } body { margin: 0 auto; } }
</style>
</head>
<body>
<h1>SELEGAL</h1><p>Centro de Conciliación Extrajudicial</p><p>R.D. N.° 364-2026-JUS/DGDPAJ/DCMA</p>
<div class="rule"></div><h2>CONSTANCIA DE CITA</h2>
<div class="details"><p><b>Solicitud:</b> {{ $a->request_id }}</p><p><b>Nombre:</b> {{ $a->name }}</p><p><b>Materia:</b> {{ $a->matter }}</p><p><b>Fecha:</b> {{ \Carbon\Carbon::parse($a->day)->format('d/m/Y') }}</p><p><b>Hora:</b> {{ $a->time }}</p><p><b>Estado:</b> {{ $a->status }}</p></div>
<div class="rule"></div>
@php
// Code 39: narrow and wide bars, including start/stop characters and quiet zones.
$patterns = ['0'=>'nnnwwnwnn','1'=>'wnnwnnnnw','2'=>'nnwwnnnnw','3'=>'wnwwnnnnn','4'=>'nnnwwnnnw','5'=>'wnnwwnnnn','6'=>'nnwwwnnnn','7'=>'nnnwnnwnw','8'=>'wnnwnnwnn','9'=>'nnwwnnwnn','*'=>'nwnnwnwnn'];
$code = str_pad((string) $a->id, 8, '0', STR_PAD_LEFT);
$x = 10;
$bars = [];
foreach (str_split('*'.$code.'*') as $character) {
    foreach (str_split($patterns[$character]) as $index => $width) {
        $units = $width === 'w' ? 3 : 1;
        if ($index % 2 === 0) $bars[] = [$x, $units];
        $x += $units;
    }
    $x++;
}
$barcodeWidth = $x + 10;
@endphp
<svg class="barcode" viewBox="0 0 {{ $barcodeWidth }} 50" preserveAspectRatio="none" role="img" aria-label="Código de barras de cita {{ $code }}" xmlns="http://www.w3.org/2000/svg" shape-rendering="crispEdges">
@foreach($bars as [$position, $width])<rect x="{{ $position }}" y="0" width="{{ $width }}" height="50" fill="#000"/>@endforeach
</svg>
<b>{{ $code }}</b><p>Gracias por confiar en SELEGAL</p><button class="print" onclick="window.print()">Imprimir ticket</button>
</body></html>
