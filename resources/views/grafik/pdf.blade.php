<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>SmartGrow Report</title>

    <style>

        body{
            font-family: sans-serif;
            color:#1f2937;
            padding:20px;
        }

        h1{
            color:#2D5A27;
            margin-bottom:4px;
            font-size:34px;
        }

        .subtitle{
            color:#6b7280;
            font-size:13px;
            margin-bottom:30px;
        }

        .info-box{
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-radius:12px;
            padding:18px;
            margin-bottom:30px;
        }

        .info-grid{
            width:100%;
        }

        .info-grid td{
            padding:6px 0;
            font-size:12px;
        }

        .label{
            font-weight:bold;
            color:#6b7280;
            width:180px;
        }

        .status{
            display:inline-block;
            background:#dcfce7;
            color:#166534;
            padding:4px 10px;
            border-radius:999px;
            font-size:10px;
            font-weight:bold;
        }

        .section-title{
            margin-top:30px;
            margin-bottom:15px;
            color:#2D5A27;
            font-size:18px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        th{
            background:#2D5A27;
            color:white;
            padding:14px;
            font-size:11px;
            text-transform:uppercase;
        }

        td{
            border:1px solid #e5e7eb;
            padding:12px;
            font-size:11px;
            text-align:center;
        }

        tr:nth-child(even){
            background:#f9fafb;
        }

        .analysis-box{
            margin-top:30px;
            background:#f0fdf4;
            border:1px solid #bbf7d0;
            border-radius:14px;
            padding:20px;
        }

        .analysis-title{
            color:#166534;
            font-size:16px;
            font-weight:bold;
            margin-bottom:12px;
        }

        .analysis-text{
            font-size:12px;
            line-height:1.8;
            color:#374151;
        }

        .footer{
            margin-top:40px;
            text-align:center;
            font-size:10px;
            color:#9ca3af;
        }

    </style>

</head>

<body>

    <!-- =======================================================
    HEADER
    ======================================================= -->

    <h1>

        SmartGrow Analytics Report

    </h1>

    <p class="subtitle">

        Smart Greenhouse Monitoring System

    </p>



    <!-- =======================================================
    GREENHOUSE INFO
    ======================================================= -->

    <div class="info-box">

        <table class="info-grid">

            <tr>

                <td class="label">

                    Greenhouse Name

                </td>

                <td>
                    {{ $greenhouse->name ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">

                    Location

                </td>

                <td>
                    {{ $greenhouse->location ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">

                    Generated Date

                </td>

                <td>

                    {{ now()->translatedFormat('d F Y • H:i') }}

                </td>

            </tr>

            <tr>

                <td class="label">

                    Data Period

                </td>

                <td>

                    {{ $filterInfo }}

                </td>

            </tr>

            <tr>

                <td class="label">

                    Data Range

                </td>

                <td>

                    @if($labels->count())

                        {{ $labels->first() }}

                        -

                        {{ $labels->last() }}

                    @else

                        Tidak ada data

                    @endif

                </td>

            </tr>

            <tr>

                <td class="label">

                    System Status

                </td>

                <td>

                    <span class="status">

                        ACTIVE

                    </span>

                </td>

            </tr>

        </table>

    </div>



    <!-- =======================================================
    SENSOR DATA TABLE
    ======================================================= -->

    <div class="section-title">

        Sensor Monitoring Data

    </div>

    <table>

        <thead>

            <tr>

                <th>Waktu</th>

                <th>Soil (%)</th>

                <th>Temperature (°C)</th>

                <th>Humidity (%)</th>

                <th>Light (Lux)</th>

            </tr>

        </thead>

        <tbody>

            @forelse($labels as $index => $label)

            <tr>

                <td>

                    {{ $label }}

                </td>

                <td>

                    {{ $soil[$index] ?? 0 }}%

                </td>

                <td>

                    {{ $temp[$index] ?? 0 }}°C

                </td>

                <td>

                    {{ $hum[$index] ?? 0 }}%

                </td>

                <td>

                    {{ $light[$index] ?? 0 }} Lux

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="5">

                    Tidak ada data tersedia

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>



    <!-- =======================================================
    ANALYSIS
    ======================================================= -->

    @php

        $avgSoil = collect($soil)->filter()->avg();
        $avgTemp = collect($temp)->filter()->avg();
        $avgHum  = collect($hum)->filter()->avg();
        $avgLight = collect($light)->filter()->avg();

    @endphp

    <div class="analysis-box">

        <div class="analysis-title">

            Monitoring Analysis

        </div>

        <div class="analysis-text">

            <strong>1. Soil Moisture Analysis</strong><br>

            Rata-rata kelembapan tanah berada di angka

            <strong>{{ round($avgSoil, 1) }}%</strong>.

            @if($avgSoil < 45)

                Kondisi tanah cenderung kering sehingga sistem irigasi
                perlu lebih sering diaktifkan untuk menjaga stabilitas tanaman.

            @elseif($avgSoil <= 70)

                Kondisi tanah berada pada kategori ideal dan cukup baik
                untuk pertumbuhan tanaman greenhouse.

            @else

                Kondisi tanah terlalu lembab sehingga perlu pengurangan
                intensitas penyiraman untuk menghindari overwatering.

            @endif

            <br><br>

            <strong>2. Temperature Analysis</strong><br>

            Rata-rata suhu greenhouse tercatat sebesar

            <strong>{{ round($avgTemp, 1) }}°C</strong>.

            @if($avgTemp > 28)

                Suhu greenhouse relatif tinggi dan kipas ventilasi
                disarankan aktif lebih lama.

            @elseif($avgTemp >= 20)

                Suhu greenhouse berada pada kondisi stabil dan aman.

            @else

                Suhu greenhouse relatif rendah sehingga tanaman tertentu
                mungkin memerlukan pencahayaan tambahan.

            @endif

            <br><br>

            <strong>3. Humidity Analysis</strong><br>

            Rata-rata kelembapan udara sebesar

            <strong>{{ round($avgHum, 1) }}%</strong>.

            @if($avgHum < 40)

                Udara greenhouse tergolong kering dan dapat mempengaruhi
                pertumbuhan tanaman.

            @elseif($avgHum <= 80)

                Kelembapan udara berada pada kondisi optimal.

            @else

                Kelembapan udara terlalu tinggi dan berpotensi
                menyebabkan jamur atau penyakit tanaman.

            @endif

            <br><br>

            <strong>4. Light Intensity Analysis</strong><br>

            Intensitas cahaya rata-rata tercatat sebesar

            <strong>{{ round($avgLight, 1) }} Lux</strong>.

            @if($avgLight < 300)

                Cahaya yang diterima tanaman relatif rendah sehingga
                lampu UV tambahan disarankan aktif lebih lama.

            @elseif($avgLight <= 800)

                Intensitas cahaya berada pada kondisi baik
                untuk pertumbuhan tanaman.

            @else

                Intensitas cahaya cukup tinggi dan tanaman perlu
                monitoring suhu tambahan.

            @endif

        </div>

    </div>



    <!-- =======================================================
    FOOTER
    ======================================================= -->

    <div class="footer">

        Generated automatically by SmartGrow Monitoring System
        © {{ date('Y') }}

    </div>

</body>

</html>