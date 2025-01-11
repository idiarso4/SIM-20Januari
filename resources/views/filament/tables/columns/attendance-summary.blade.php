@php
<<<<<<< Updated upstream
    $totalHadir = 0;
    $totalIzin = 0;
    $totalSakit = 0;
    $totalAlpha = 0;
    $totalDispensasi = 0;

    $attendances = \App\Models\StudentAttendance::where('teaching_activity_id', $getRecord()->id)->get();
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
            case 'dispensasi':
                $totalDispensasi++;
                break;
        }
=======
    use Illuminate\Support\Facades\DB;
    
    // Get attendance data with student details
    $attendances = $getRecord()->studentAttendances->groupBy('status');

    // Count each status
    $summary = [
        'hadir' => $attendances->get('hadir', collect())->count(),
        'izin' => $attendances->get('izin', collect())->count(),
        'sakit' => $attendances->get('sakit', collect())->count(),
        'alpha' => $attendances->get('alpha', collect())->count(),
        'dispensasi' => $attendances->get('dispensasi', collect())->count(),
    ];

    // Get total students in the extracurricular
    $totalStudents = $getRecord()->extracurricular->students()->count();

    // If no attendance records, set all students as alpha
    if ($attendances->isEmpty() && $totalStudents > 0) {
        $summary['alpha'] = $totalStudents;
>>>>>>> Stashed changes
    }

    $totalSiswa = \App\Models\Student::where('class_room_id', $getRecord()->class_room_id)->count();
@endphp

<<<<<<< Updated upstream
<div class="space-y-1">
    <div class="text-sm">
        <span class="text-green-600 font-medium">Hadir: {{ $totalHadir }}</span>
        <span class="text-blue-600 font-medium ml-2">Izin: {{ $totalIzin }}</span>
        <span class="text-yellow-600 font-medium ml-2">Sakit: {{ $totalSakit }}</span>
=======
<div class="space-y-2">
    <div class="flex items-center gap-2 flex-wrap">
        <span class="px-2 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
            Hadir: {{ $summary['hadir'] }}
        </span>
        <span class="px-2 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
            Izin: {{ $summary['izin'] }}
        </span>
        <span class="px-2 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
            Sakit: {{ $summary['sakit'] }}
        </span>
        <span class="px-2 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
            Alpha: {{ $summary['alpha'] }}
        </span>
        <span class="px-2 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
            Dispensasi: {{ $summary['dispensasi'] }}
        </span>
>>>>>>> Stashed changes
    </div>
    <div class="text-sm">
        <span class="text-red-600 font-medium">Alpha: {{ $totalAlpha }}</span>
        <span class="text-purple-600 font-medium ml-2">Dispensasi: {{ $totalDispensasi }}</span>
    </div>
    <div class="text-xs text-gray-500">
        Total Siswa: {{ $totalSiswa }}
    </div>
</div> 