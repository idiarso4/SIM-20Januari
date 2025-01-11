@php
    $classRoomId = null;
    $attendanceData = [];
    
    if ($getRecord() && $getRecord()->class_room_id) {
        $classRoomId = $getRecord()->class_room_id;
    } elseif (isset($getContainer()->getParentComponent()->getState()['class_room_id'])) {
        $classRoomId = $getContainer()->getParentComponent()->getState()['class_room_id'];
    }
    
    $students = $classRoomId ? 
        \App\Models\Student::where('class_room_id', $classRoomId)
            ->orderBy('nama_lengkap')
            ->get() : 
        collect();

    // Kalkulasi kehadiran
    $totalHadir = 0;
    $totalIzin = 0;
    $totalSakit = 0;
    $totalAlpha = 0;
    $totalDispensasi = 0;

    if ($getRecord()) {
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
        }
    }
@endphp

<div>
    @if($classRoomId)
        <h3 class="text-lg font-medium">Kehadiran Siswa</h3>
        
        <div class="mt-4 mb-4 bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-5 gap-4 text-center">
                <div>
                    <div class="font-semibold text-green-600">Hadir: {{ $totalHadir }}</div>
                </div>
                <div>
                    <div class="font-semibold text-blue-600">Izin: {{ $totalIzin }}</div>
                </div>
                <div>
                    <div class="font-semibold text-yellow-600">Sakit: {{ $totalSakit }}</div>
                </div>
                <div>
                    <div class="font-semibold text-red-600">Alpha: {{ $totalAlpha }}</div>
                </div>
                <div>
                    <div class="font-semibold text-purple-600">Dispensasi: {{ $totalDispensasi }}</div>
                </div>
            </div>
            <div class="mt-2 text-center text-gray-600">
                Total Siswa: {{ $students->count() }}
            </div>
        </div>

        <div class="mt-4 space-y-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-4">NIS</th>
                            <th class="text-left py-2 px-4">Nama Siswa</th>
                            <th class="text-left py-2 px-4">Status</th>
                            <th class="text-left py-2 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $attendance = $getRecord() ? 
                                    \App\Models\StudentAttendance::where('teaching_activity_id', $getRecord()->id)
                                        ->where('student_id', $student->id)
                                        ->first() : 
                                    null;
                                $currentStatus = $attendance ? $attendance->status : 'hadir';
                                $currentKeterangan = $attendance ? $attendance->keterangan : '';
                            @endphp
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $student->nis }}</td>
                                <td class="py-2 px-4">{{ $student->nama_lengkap }}</td>
                                <td class="py-2 px-4">
                                    <select 
                                        class="w-full border-gray-300 rounded-lg shadow-sm"
                                        wire:model="data.attendance_data.{{ $student->id }}.status"
                                    >
                                        <option value="hadir" @selected($currentStatus === 'hadir')>Hadir</option>
                                        <option value="izin" @selected($currentStatus === 'izin')>Izin</option>
                                        <option value="sakit" @selected($currentStatus === 'sakit')>Sakit</option>
                                        <option value="alpha" @selected($currentStatus === 'alpha')>Alpha</option>
                                        <option value="dispensasi" @selected($currentStatus === 'dispensasi')>Dispensasi</option>
                                    </select>
                                </td>
                                <td class="py-2 px-4">
                                    <input 
                                        type="text" 
                                        placeholder="Keterangan (opsional)"
                                        class="w-full border-gray-300 rounded-lg shadow-sm"
                                        wire:model="data.attendance_data.{{ $student->id }}.keterangan"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium mb-4">Catatan Kejadian Penting</h3>
            <textarea
                wire:model="data.important_notes"
                class="w-full border-gray-300 rounded-lg shadow-sm"
                rows="4"
                placeholder="Masukkan catatan kejadian penting selama pembelajaran (opsional)"
            ></textarea>
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-gray-500">Silakan pilih kelas terlebih dahulu untuk menampilkan daftar kehadiran siswa.</p>
        </div>
    @endif
</div> 