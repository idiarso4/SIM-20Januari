<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Konseling Siswa</title>
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
        <h1>LAPORAN KONSELING SISWA</h1>
        <p>SMA IDIARSO SIMBANG</p>
    </div>

    <div class="content">
        <div class="info">
            <span class="label">Nama Siswa:</span>
            {{ $counseling->student->name }}
        </div>
        <div class="info">
            <span class="label">Guru BK:</span>
            {{ $counseling->counselor->name }}
        </div>
        <div class="info">
            <span class="label">Tanggal:</span>
            {{ $counseling->counseling_date->format('d F Y') }}
        </div>
        <div class="info">
            <span class="label">Waktu:</span>
            {{ $counseling->start_time->format('H:i') }} - {{ $counseling->end_time ? $counseling->end_time->format('H:i') : 'Selesai' }}
        </div>
        <div class="info">
            <span class="label">Jenis Konseling:</span>
            @switch($counseling->type)
                @case('individu')
                    Konseling Individu
                    @break
                @case('kelompok')
                    Konseling Kelompok
                    @break
                @case('karir')
                    Bimbingan Karir
                    @break
                @case('akademik')
                    Bimbingan Akademik
                    @break
            @endswitch
        </div>
        <div class="info">
            <span class="label">Jenis Masalah:</span>
            @switch($counseling->case_type)
                @case('akademik')
                    Masalah Akademik
                    @break
                @case('pribadi')
                    Masalah Pribadi
                    @break
                @case('sosial')
                    Masalah Sosial
                    @break
                @case('karir')
                    Masalah Karir
                    @break
            @endswitch
        </div>
        <div class="info">
            <span class="label">Status:</span>
            @switch($counseling->status)
                @case('open')
                    Baru
                    @break
                @case('in_progress')
                    Dalam Proses
                    @break
                @case('completed')
                    Selesai
                    @break
                @case('need_visit')
                    Perlu Kunjungan
                    @break
            @endswitch
        </div>

        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Deskripsi Masalah:</div>
            <div style="text-align: justify;">{{ $counseling->problem_desc }}</div>
        </div>

        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Solusi/Penanganan:</div>
            <div style="text-align: justify;">{{ $counseling->solution }}</div>
        </div>

        @if($counseling->follow_up)
        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Tindak Lanjut:</div>
            <div style="text-align: justify;">{{ $counseling->follow_up }}</div>
        </div>
        @endif

        @if($counseling->notes)
        <div class="info" style="margin-top: 20px;">
            <div style="font-weight: bold; margin-bottom: 10px;">Catatan Tambahan:</div>
            <div style="text-align: justify;">{{ $counseling->notes }}</div>
        </div>
        @endif
    </div>

    <div class="signature">
        <p>{{ now()->format('d F Y') }}</p>
        <p>Guru Bimbingan Konseling</p>
        <div class="signature-line"></div>
        <p>{{ $counseling->counselor->name }}</p>
    </div>
</body>
</html> 