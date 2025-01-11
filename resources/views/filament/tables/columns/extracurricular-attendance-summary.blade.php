@php
    $totalHadir = 0;
    $totalIzin = 0;
    $totalSakit = 0;
    $totalAlpha = 0;

    $attendances = \App\Models\ExtracurricularAttendance::where('extracurricular_activity_id', $getRecord()->id)->get();
    foreach ($attendances as $attendance) {
        switch ($attendance->status) {
            case 'hadir':
                $totalHadir++;
                break;
            case 'izin':
                $totalIzin++;
                break;
            case 'sakit':
                $totalSakit++;
                break;
            case 'alpha':
                $totalAlpha++;
                break;
        }
    }

    $totalSiswa = $getRecord()->extracurricular->students()->count();
@endphp

<div class="space-y-1">
    <div class="text-sm">
        <span class="text-green-600 font-medium">Hadir: {{ $totalHadir }}</span>
        <span class="text-blue-600 font-medium ml-2">Izin: {{ $totalIzin }}</span>
        <span class="text-yellow-600 font-medium ml-2">Sakit: {{ $totalSakit }}</span>
        <span class="text-red-600 font-medium ml-2">Alpha: {{ $totalAlpha }}</span>
    </div>
    <div class="text-xs text-gray-500">
        Total Siswa: {{ $totalSiswa }}
    </div>
</div> 