<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan Rumah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 30px;
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
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #000;
        }
        .letterhead {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        .letterhead img {
            height: 80px;
            margin-bottom: 10px;
        }
        .letterhead h2 {
            margin: 0;
            font-size: 20px;
        }
        .letterhead p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="letterhead">
        <h2>SMA IDIARSO SIMBANG</h2>
        <p>Jl. Raya Simbang No. 123, Simbang, Jawa Tengah</p>
        <p>Telp: (0123) 456789 | Email: info@idiarsosimbang.sch.id</p>
    </div>

    <div class="header">
        <h1>LAPORAN KUNJUNGAN RUMAH</h1>
        <p>Nomor: {{ date('Y') }}/BK/KR/{{ str_pad($homeVisit->id, 3, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="content">
        <div class="info">
            <span class="label">Nama Siswa:</span>
            {{ $homeVisit->student->name }}
        </div>
        <div class="info">
            <span class="label">Kelas:</span>
            {{ $homeVisit->student->class_room->name ?? '-' }}
        </div>
        <div class="info">
            <span class="label">Tanggal Kunjungan:</span>
            {{ $homeVisit->visit_date->isoFormat('dddd, D MMMM Y') }}
        </div>
        <div class="info">
            <span class="label">Waktu:</span>
            {{ $homeVisit->visit_time->format('H:i') }} WIB
        </div>
        <div class="info">
            <span class="label">Alamat:</span>
            {{ $homeVisit->address }}
        </div>
        <div class="info">
            <span class="label">Bertemu dengan:</span>
            {{ $homeVisit->met_with }}
        </div>

        <div style="margin-top: 20px;">
            <div style="font-weight: bold;">Poin Diskusi:</div>
            <div style="text-align: justify; margin-top: 5px;">{{ $homeVisit->discussion_points }}</div>
        </div>

        @if($homeVisit->agreements)
        <div style="margin-top: 20px;">
            <div style="font-weight: bold;">Kesepakatan:</div>
            <div style="text-align: justify; margin-top: 5px;">{{ $homeVisit->agreements }}</div>
        </div>
        @endif

        <div style="margin-top: 20px;">
            <div style="font-weight: bold;">Rekomendasi:</div>
            <div style="text-align: justify; margin-top: 5px;">{{ $homeVisit->recommendations }}</div>
        </div>

        @if($homeVisit->follow_up_plan)
        <div style="margin-top: 20px;">
            <div style="font-weight: bold;">Rencana Tindak Lanjut:</div>
            <div style="text-align: justify; margin-top: 5px;">{{ $homeVisit->follow_up_plan }}</div>
        </div>
        @endif
    </div>

    <div style="margin-top: 50px; text-align: right;">
        <div>Simbang, {{ now()->isoFormat('D MMMM Y') }}</div>
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="width: 200px; text-align: center;">
            <p>Wali Kelas</p>
            <div style="margin-top: 80px; border-bottom: 1px solid #000;"></div>
            <p>{{ $homeVisit->student->class_room->homeroom_teacher->name ?? '.........................' }}</p>
        </div>

        <div style="width: 200px; text-align: center;">
            <p>Guru BK</p>
            <div style="margin-top: 80px; border-bottom: 1px solid #000;"></div>
            <p>{{ $homeVisit->counselor->name }}</p>
        </div>

        <div style="width: 200px; text-align: center;">
            <p>Orang Tua/Wali</p>
            <div style="margin-top: 80px; border-bottom: 1px solid #000;"></div>
            <p>{{ $homeVisit->met_with }}</p>
        </div>
    </div>

    <div style="margin-top: 50px; text-align: center;">
        <p>Mengetahui,</p>
        <p>Kepala Sekolah</p>
        <div style="margin-top: 80px;"></div>
        <div style="border-bottom: 1px solid #000; width: 200px; margin: 0 auto;"></div>
        <p>Dr. H. Idiarso, M.Pd.</p>
        <p>NIP. 196001011985031001</p>
    </div>
</body>
</html> 