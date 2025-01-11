@php
    $extraId = null;
    $statePath = $getStatePath();
    $parentComponent = $getContainer()?->getParentComponent();
    
    if ($getRecord() && $getRecord()->extracurricular_id) {
        $extraId = $getRecord()->extracurricular_id;
    } elseif ($parentComponent && method_exists($parentComponent, 'getState')) {
        $state = $parentComponent->getState();
        if (is_array($state) && isset($state['extracurricular_id'])) {
            $extraId = $state['extracurricular_id'];
        }
    }
    
    $students = collect();
    if ($extraId) {
        $extracurricular = \App\Models\Extracurricular::with(['students' => function($query) {
            $query->with('classRoom')
                  ->orderBy('nama_lengkap');
        }])->find($extraId);
        
        if ($extracurricular) {
            $students = $extracurricular->students;
        }
    }

    // Initialize attendance data for all students
    $state = $getState() ?? [];
    if ($students->isNotEmpty() && empty($state)) {
        $attendanceData = [];
        foreach ($students as $index => $student) {
            $attendance = $getRecord() ? 
                \App\Models\ExtracurricularAttendance::where('extracurricular_activity_id', $getRecord()->id)
                    ->where('student_id', $student->id)
                    ->first() : 
                null;
            
            $attendanceData[] = [
                'student_id' => $student->id,
                'status' => $attendance ? $attendance->status : 'hadir',
                'keterangan' => $attendance ? $attendance->keterangan : ''
            ];
        }
        $setState(['attendance_data' => $attendanceData]);
    }

    $currentState = $getState() ?? ['attendance_data' => []];
@endphp

<div>
    @if($extraId && $students->isNotEmpty())
        <h3 class="text-lg font-medium">Kehadiran Siswa</h3>
        <div class="mt-4 space-y-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-4">NIS</th>
                            <th class="text-left py-2 px-4">Nama Siswa</th>
                            <th class="text-left py-2 px-4">Kelas</th>
                            <th class="text-left py-2 px-4">Status</th>
                            <th class="text-left py-2 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            @php
                                $attendanceState = $currentState['attendance_data'][$index] ?? [
                                    'student_id' => $student->id,
                                    'status' => 'hadir',
                                    'keterangan' => ''
                                ];
                            @endphp
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $student->nis }}</td>
                                <td class="py-2 px-4">{{ $student->nama_lengkap }}</td>
                                <td class="py-2 px-4">{{ $student->classRoom->name }}</td>
                                <td class="py-2 px-4">
                                    <select 
                                        class="w-full border-gray-300 rounded-lg shadow-sm"
                                        wire:model="{{ $statePath }}.attendance_data.{{ $index }}.status"
                                    >
                                        <option value="hadir">Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="alpha">Alpha</option>
                                        <option value="dispensasi">Dispensasi</option>
                                    </select>
                                </td>
                                <td class="py-2 px-4">
                                    <input 
                                        type="text" 
                                        placeholder="Keterangan (opsional)"
                                        class="w-full border-gray-300 rounded-lg shadow-sm"
                                        wire:model="{{ $statePath }}.attendance_data.{{ $index }}.keterangan"
                                    >
                                    <input 
                                        type="hidden" 
                                        wire:model="{{ $statePath }}.attendance_data.{{ $index }}.student_id" 
                                        value="{{ $student->id }}"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-gray-500">{{ $extraId ? 'Belum ada anggota yang terdaftar di ekstrakurikuler ini.' : 'Silakan pilih ekstrakurikuler terlebih dahulu untuk menampilkan daftar kehadiran siswa.' }}</p>
        </div>
    @endif
</div> 