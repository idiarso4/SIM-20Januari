<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Izin Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
        }
        .content {
            margin: 20px 0;
        }
        .signature {
            margin-top: 50px;
            float: right;
            text-align: center;
        }
        .qrcode {
            margin-top: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        td {
            padding: 5px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMKN 1 PUNGGELAN</h1>
        <p>Jl. Raya Punggelan, Punggelan, Banjarnegara</p>
        <hr>
    </div>

    <div class="title">
        SURAT IZIN KELUAR SEKOLAH
    </div>

    <div class="content">
        <table>
            <tr>
                <td width="150">Nama Siswa</td>
                <td width="10">:</td>
                <td>{{ $permit->student->name }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $permit->student->class_room?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $permit->permit_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $permit->start_time->format('H:i') }} - {{ $permit->end_time ? $permit->end_time->format('H:i') : '...' }}</td>
            </tr>
            <tr>
                <td>Alasan</td>
                <td>:</td>
                <td>{{ $permit->reason }}</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <p>Punggelan, {{ $permit->approved_at->format('d F Y') }}</p>
        <p>Menyetujui,</p>
        <br><br><br>
        <p><u>{{ $permit->approver->name }}</u></p>
        <p>Guru {{ $permit->approver->mata_pelajaran ?? 'Wali Kelas' }}</p>
    </div>

    <div style="clear: both;"></div>

    <div class="qrcode" style="margin-top: 30px;">
        {!! $qrcode !!}
        <p style="font-size: 10pt; margin-top: 5px;">Scan untuk verifikasi</p>
    </div>

    <div class="footer">
        <p>Catatan: Surat izin ini harus ditunjukkan kepada Satpam yang bertugas saat keluar/masuk sekolah</p>
    </div>
</body>
</html> 