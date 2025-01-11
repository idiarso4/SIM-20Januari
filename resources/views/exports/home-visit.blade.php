<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan Rumah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KUNJUNGAN RUMAH</h1>
        <p>SMA IDIARSO SIMBANG</p>
    </div>

    <div class="content">
        <div class="info">
            <span class="label">Nama Siswa:</span>
            {{ $visit->student->name }}
        </div>
        <div class="info">
            <span class="label">Guru BK:</span>
            {{ $visit->counselor->name }}
        </div>
        <div class="info">
            <span class="label">Tanggal:</span>
            {{ $visit->visit_date->format('d F Y') }}
        </div>
        <div class="info">
            <span class="label">Waktu:</span>
            {{ $visit->visit_time->format('H:i') }}
        </div>
        <div class="info">
            <span class="label">Alamat:</span>
            {{ $visit->address }}
        </div>
        <div class="info">
            <span class="label">Bertemu Dengan:</span>
            {{ $visit->met_with }}
        </div>
        <div class="info">
            <span class="label">Status:</span>
            @switch($visit->status)
                @case('planned')
                    Direncanakan
                    @break
                @case('completed')
                    Selesai
                    @break
                @case('cancelled')
                    Dibatalkan
                    @break
                @case('rescheduled')
                    Dijadwalkan Ulang
                    @break
            @endswitch
        </div>

        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Poin Diskusi:</div>
            <div style="text-align: justify;">{{ $visit->discussion_points }}</div>
        </div>

        @if($visit->agreements)
        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Kesepakatan:</div>
            <div style="text-align: justify;">{{ $visit->agreements }}</div>
        </div>
        @endif

        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Rekomendasi:</div>
            <div style="text-align: justify;">{{ $visit->recommendations }}</div>
        </div>

        @if($visit->follow_up_plan)
        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Rencana Tindak Lanjut:</div>
            <div style="text-align: justify;">{{ $visit->follow_up_plan }}</div>
        </div>
        @endif

        @if($visit->notes)
        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Catatan Tambahan:</div>
            <div style="text-align: justify;">{{ $visit->notes }}</div>
        </div>
        @endif
    </div>

    <div class="signature">
        <p>{{ now()->format('d F Y') }}</p>
        <p>Guru Bimbingan Konseling</p>
        <div class="signature-line"></div>
        <p>{{ $visit->counselor->name }}</p>
    </div>
</body>
</html> 