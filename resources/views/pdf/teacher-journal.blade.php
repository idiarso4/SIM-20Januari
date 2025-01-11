<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agenda Harian Guru</title>
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
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            padding: 5px;
        }
        .activity {
            margin-bottom: 10px;
            padding-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Agenda Harian Guru</h2>
        <p>Tanggal: {{ \Carbon\Carbon::parse($journal->tanggal)->format('d/m/Y') }}</p>
        <p>Guru: {{ $journal->guru->name }}</p>
    </div>

    <div class="section">
        <div class="section-title">Kegiatan Pembelajaran</div>
        @if($teaching_activities->isNotEmpty())
            <table>
                <tr>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Materi</th>
                </tr>
                @foreach($teaching_activities as $activity)
                <tr>
                    <td>{{ $activity->class_room->name }}</td>
                    <td>{{ $activity->subject }}</td>
                    <td>{{ $activity->material }}</td>
                </tr>
                @endforeach
            </table>
        @else
            <p>Tidak ada kegiatan pembelajaran</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Penilaian</div>
        @if($assessments->isNotEmpty())
            <table>
                <tr>
                    <th>Kelas</th>
                    <th>Jenis</th>
                    <th>Mata Pelajaran</th>
                    <th>Nama Penilaian</th>
                </tr>
                @foreach($assessments as $assessment)
                <tr>
                    <td>{{ $assessment->class_room->name }}</td>
                    <td>{{ $assessment->type }}</td>
                    <td>{{ $assessment->subject }}</td>
                    <td>{{ $assessment->assessment_name }}</td>
                </tr>
                @endforeach
            </table>
        @else
            <p>Tidak ada kegiatan penilaian</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Kegiatan Ekstrakurikuler</div>
        @if($extracurriculars->isNotEmpty())
            <table>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Deskripsi</th>
                </tr>
                @foreach($extracurriculars as $extra)
                <tr>
                    <td>{{ $extra->name }}</td>
                    <td>{{ $extra->description }}</td>
                </tr>
                @endforeach
            </table>
        @else
            <p>Tidak ada kegiatan ekstrakurikuler</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Refleksi Harian</div>
        <div class="activity">
            <strong>Pencapaian:</strong>
            <p>{{ $journal->pencapaian }}</p>
        </div>
        <div class="activity">
            <strong>Kendala:</strong>
            <p>{{ $journal->kendala }}</p>
        </div>
        <div class="activity">
            <strong>Solusi:</strong>
            <p>{{ $journal->solusi }}</p>
        </div>
        <div class="activity">
            <strong>Rencana Tindak Lanjut:</strong>
            <p>{{ $journal->rencana_tindak_lanjut }}</p>
        </div>
    </div>
</body>
</html> 