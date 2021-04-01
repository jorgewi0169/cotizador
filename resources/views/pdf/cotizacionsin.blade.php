<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/core-img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        body {
            margin: -30px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;

        }

        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid #000000;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table th,
        .table td {
            padding: 0.20rem;
            /*  padding: 0.75rem; */
            vertical-align: top;
            /* border-top: 1px solid #c2cfd6; */
        }

        .table thead th {
            vertical-align: bottom;
            /*  border-bottom: 2px solid #c2cfd6; */
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000000;
            /* border: 2px solid #000000; */
        }

        th,
        td {
            display: table-cell;
            vertical-align: inherit;
            font-size: 10px;
        }

        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

    </style>
</head>

<body>

    <table class="table table-bordered table-striped table-sm">

        <tbody>


            <tr>
                <th colspan="4" style="text-align: center">
                    <img src="img/bg-img/logopdf.png" width="300px" alt="logo.svg">
                </th>
                <th colspan="{{ $espacios }}"
                    style="text-align: center; font-size: 20px; display: table-cell; vertical-align: middle;">ANALISIS
                    CUANTITATIVOS -
                    SEGUROS: VEHICULOS </th>

            </tr>
            <tr style="text-align: center;">
                <th colspan="2" style="text-align: center" scope="colgroup">NOMBRE COMPLETO/ RAZON SOCIAL</th>
                <th colspan="2" style="text-align: center" scope="colgroup">EDAD</th>
                @foreach ($cotizacion_seguro as $item)
                    <td colspan="{{ $espacios / $espacio2s }}" rowspan="4" align="center"
                        style="display: table-cell; vertical-align: middle;">
                        @if ($item->logo == '' || $item->logo == null)
                            {{ $item->nombre }}
                        @else
                            <img src="storage/seguro/{{ $item->logo }}" alt="{{ $item->nombre }}" id="imagen"
                                width="100px">
                        @endif
                    </td>
                @endforeach

            </tr>
            <tr style="text-align: center;">
                <td colspan="2" style="background: #0984e3; color: #ffffff"><b>{{ $cotizacion->cliente }}</b></td>
                <td colspan="2" style="background: #0984e3; color: #ffffff"><b>{{ $cotizacion->edad }}</b></td>

            </tr>
            <tr style="text-align: center;">
                <td colspan="4"> <b>ASESOR: </b> {{ $cotizacion->asesor . '- Cell ' . $cotizacion->asesor_telefono }}
                </td>

            </tr>
            <tr style="text-align: center;">
                <td colspan="4" style="background: #0984e3; color: #ffffff"> <b>VIGENCIA:
                        {{ $cotizacion->estado }}</b> </td>

            </tr>
            <tr style="text-align: center;">
                <td colspan="2" style="background: #0984e3; color: #ffffff"> <b>{{ $cotizacion->clasificacion }}</b>
                </td>
                <td colspan="2" style="background: #636e72; color: #ffffff" align="center"> <b>SUMA ASEGURADA</b> </td>
                @foreach ($cotizacion_seguro as $item)
                    <td style="background: #636e72; color: #ffffff"><b>TASA</b></td>
                    <td style="background: #636e72; color: #ffffff"><b>PRIMA NETA</b></td>
                @endforeach

            </tr>
            <tr style="text-align: center;">
                <td colspan="2"> {{ $cotizacion->vehiculo . ' Año ' . $cotizacion->año_vehiculo }} </td>
                <td colspan="2"> {{ '$ ' . $cotizacion->suma_asegurada }} </td>
                @foreach ($cotizacion_seguro as $item)
                    <td style="background: #d63031; color: #ffffff"><b>{{ $item->porcentaje_tasa }}%</b></td>
                    <td><b>$ {{ ($cotizacion->suma_asegurada * $item->porcentaje_tasa) / 100 }}</b></td>
                @endforeach

            </tr>
            <tr>
                <th style="text-align: center;" colspan="2">DESGRAVAMEN</th>
                <td style="text-align: center;" colspan="2"> {{ '$ ' . $cotizacion->desgravamen }} </td>

                @foreach ($cotizacion_seguro as $seguro)
                    <td align="center"> {{ $seguro->por_desgravamen }} %</td>
                    <td align="center">
                        @if ($cotizacion->desgravamen == 0 && $seguro->por_desgravamen == 0)
                            $ 0
                        @elseif($seguro->por_desgravamen == 0)
                            $ 150
                        @elseif($cotizacion->desgravamen == 0)
                            $ 0
                        @elseif($seguro->por_desgravamen > 0)
                            $
                            {{ $total = ($cotizacion->desgravamen * $seguro->por_desgravamen) / 100 < 104.5 ? 104.5 : ($cotizacion->desgravamen * $seguro->por_desgravamen) / 100 }}
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                <th style="text-align: center;" colspan="4">AUTO * AUTO</th>
                @foreach ($cotizacion_seguro as $seguro)
                    <td align="center"> $ {{ $seguro->auto_auto }}</td>
                    <td align="center"> {{ $seguro->auto_auto_aplica == 1 ? '$ ' . $seguro->auto_auto : '' }}
                    </td>
                @endforeach

            </tr>
            <tr>
                <th style="text-align: center;" colspan="4">0 DEDUCIBLE </th>
                @foreach ($cotizacion_seguro as $seguro)
                    <td align="center" colspan="{{ $espacios / $espacio2s }}"> {{ $seguro->cero_deducible }} %
                    </td>
                @endforeach


            </tr>
            <tr>
                <th style="text-align: center;" colspan="4">AMPARAO PATROMONIAL</th>
                @foreach ($cotizacion_seguro as $seguro)
                    <td align="center" colspan="{{ $espacios / $espacio2s }}"> {{ $seguro->amparo_patrimonial }} %
                    </td>
                @endforeach

            </tr>
            <tr>
                <th style="text-align: center;" colspan="4">DISPOSITIVO DE RASTREO</th>
                @foreach ($cotizacion_seguro as $seguro)
                    <td align="center" colspan="{{ $espacios / $espacio2s }}">
                        {{ $seguro->dis_rastreo_aplica == 1 ? '$ ' . $seguro->dispositivo_rastreo : '$ 0' }} </td>
                @endforeach


            </tr>
            <tr>
                <th style="text-align: center;" colspan="4"> </th>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center"><b>P.NETA</b></td>
                    <td align="center">
                        <b>US$
                            {{ $item->auto_auto_aplica == 1 ? ($cotizacion->suma_asegurada * $item->porcentaje_tasa) / 100 + $item->auto_auto : ($cotizacion->suma_asegurada * $item->porcentaje_tasa) / 100 }}</b>
                    </td>
                @endforeach


            </tr>
            {{-- COVERTURAS ------------------------------------- --}}
            <tr>
                <th colspan="4" style="background: #b2bec3; color: #ffffff;  font-size: 15px; text-align: center;">
                    Coberturas
                </th>
                <td colspan="{{ $espacios }}"></td>

            </tr>

            @foreach ($coverturas_mostrar as $key => $item)
                <tr>
                    <td colspan="4">{{ $item->nombre }}</td>

                    @foreach ($covertura as $cover)
                        @if ($cover[$key]->tipo_variable == 'TEXTO' && $cover[$key]->descripcion != '')
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">
                                {{ $cover[$key]->descripcion }}</td>
                        @elseif($cover[$key]->tipo_variable == 'MONTO' && $cover[$key]->monto!= 0)
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">US$
                                {{ $cover[$key]->monto }}</td>
                        @elseif($cover[$key]->tipo_variable == 'PORCENTAJE')
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">{{ $cover[$key]->monto }} %
                            </td>
                        @else
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">
                                @if ($cover[$key]->aplica == 'SI')
                                    <img src="img/bg-img/check.png" width="10px">
                                @else
                                    <img src="img/bg-img/unchecked.png" width="10px">
                                @endif
                            </td>
                        @endif
                    @endforeach

                </tr>
            @endforeach

            <tr>
                <th colspan="4" style="background: #b2bec3; color: #ffffff;  font-size: 15px; text-align: center;">
                    Deducibles
                </th>
                <td colspan="{{ $espacios }}"></td>

            </tr>

            @foreach ($deducibles_mostrar as $key => $item)
                <tr>
                    <td colspan="4">{{ $item->nombre }}</td>

                    @foreach ($deducible as $dedu)
                        @if ($dedu[$key]->tipo_variable == 'TEXTO' && $dedu[$key]->descripcion != '')
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">
                                {{ $dedu[$key]->descripcion }}</td>
                        @elseif($dedu[$key]->tipo_variable == 'MONTO' && $dedu[$key]->monto!= 0)
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">US$ {{ $dedu[$key]->monto }}
                            </td>
                        @elseif($dedu[$key]->tipo_variable == 'PORCENTAJE')
                            <td align="center" colspan="{{ $espacios / $espacio2s }}"> {{ $dedu[$key]->monto }} %
                            </td>
                        @else
                            <td align="center" colspan="{{ $espacios / $espacio2s }}">
                                @if ($cover[$key]->aplica == 'SI')
                                    <img src="img/bg-img/check.png" width="10px">
                                @else
                                    <img src="img/bg-img/unchecked.png" width="10px">
                                @endif
                            </td>
                        @endif
                    @endforeach

                </tr>
            @endforeach
            <tr>
                <td rowspan="11" colspan="2" align="center"
                    style="background: #e3ebef; font-size: 15px; display: table-cell; vertical-align: middle;">
                    <b style="color: red;">REQUISITOS:</b> COPIA DE CEDULA + CERTIFICADO VOTACION + PLANILLA DE
                    SERVICIOS BASICOS + FORMULARIO +
                    FORMA DE PAGO + CORREO ELECTRONICO
                    <a href="http://www.merbrok.com.ec/">WWW.MERBROK.COM.EC</a>
                </td>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>PRIMA NETA:</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC"></td>
                    <td align="center" style="background: #B3E5FC">US$
                        {{ $item->auto_auto_aplica == 1 ? $item->prima_neta + $item->auto_auto : $item->prima_neta }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>SUPER BANCOS:</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC"> {{ $item->s_bancos_por }} %</td>
                    <td align="center" style="background: #B3E5FC">
                        {{ round(($item->prima_neta * $item->s_bancos_por) / 100, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>S. CAMPESINO:</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC"> {{ $item->s_campesino_por }} %</td>
                    <td align="center" style="background: #B3E5FC">
                        {{ round(($item->prima_neta * $item->s_campesino_por) / 100, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>S.S.C. NO COBRADO (2001 -
                        2007)</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC"> </td>
                    <td align="center" style="background: #B3E5FC"> </td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>DERECHOS DE EMISION:</b>
                </td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC"> </td>
                    <td align="center" style="background: #B3E5FC"> {{ $item->derecho_emicion }}</td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>INTERES DE
                        FINANCIACION:</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC">{{ $item->interes_financiamiento_por }} % </td>
                    <td align="center" style="background: #B3E5FC">
                        {{ round((($item->prima_neta + round(($item->prima_neta * $item->s_bancos_por) / 100, 2) + $item->derecho_emicion + round(($item->prima_neta * $item->s_campesino_por) / 100, 2)) * $item->interes_financiamiento_por) / 100, 2) }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #595959; color: #ffffff" colspan="2"><b>IVA:</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center" style="background: #B3E5FC">{{ $item->iva_por }} % </td>
                    <td align="center" style="background: #B3E5FC"> 2</td>
                @endforeach
            </tr>
            <tr>
                <td style=" font-size: 15px; text-align: center;" colspan="{{ $espacios + 2 }}"><b>SIN DESCUENTO</b>
                </td>

            </tr>
            <tr>
                <td align="center" style="background: #FFF9C4; color: red" colspan="2"><b>VALOR A PAGAR VEHICULO</b>
                </td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center"> </td>
                    <td align="center">
                        {{ 'US$ ' . ($seguro->dis_rastreo_aplica == 1 ? '$ ' . ($item->total_general - $item->total_desgravamen - $item->dispositivo_rastreo) : $item->total_general - $item->total_desgravamen) }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #FFF9C4; color: red" colspan="2"><b>DESGRAVAMEN</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center"></td>
                    <td align="center"> {{ 'US$ ' . $item->total_desgravamen }}</td>
                @endforeach
            </tr>
            <tr>
                <td align="center" style="background: #FFEB3B; color: red" colspan="2"><b>VALOR A PAGAR VEHICULO +
                        DESGRAVAMEN +
                        DISPOSITIVO RASTREO:</b></td>
                @foreach ($cotizacion_seguro as $item)
                    <td align="center"></td>
                    <td align="center">{{ 'US$ ' . $item->total_general }}</td>
                @endforeach
            </tr>










        </tbody>

    </table>


</body>

</html>
