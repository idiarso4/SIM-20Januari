@php
    $teaching_activities = $teaching_activities ?? collect();
    $assessments = $assessments ?? collect();
    $extracurriculars = $extracurriculars ?? collect();
    
    // Filter out assessments with empty class or type
    $filteredAssessments = $assessments->filter(function($assessment) {
        return !empty($assessment->class_room?->name) && !empty($assessment->type);
    });

    // Check if user is a pembina
    $isPembina = auth()->user()->hasRole('pembina');
@endphp

<div>
    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-medium">Pembelajaran</h3>
            @if($teaching_activities->isNotEmpty())
                <div class="mt-2">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($teaching_activities as $activity)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $activity->class_room?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $activity->subject ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $activity->material ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-gray-500 mt-2">Tidak ada kegiatan pembelajaran</p>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-medium">Penilaian</h3>
            @if($filteredAssessments->isNotEmpty())
                <div class="mt-2">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Penilaian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($filteredAssessments as $assessment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $assessment->class_room->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $assessment->type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-gray-500 mt-2">Tidak ada kegiatan penilaian</p>
            @endif
        </div>

        @if($isPembina)
        <div>
            <h3 class="text-lg font-medium">Ekstrakurikuler</h3>
            @if($extracurriculars->isNotEmpty())
                <div class="mt-2">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($extracurriculars as $extra)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extra->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $extra->description ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-gray-500 mt-2">Tidak ada kegiatan ekstrakurikuler</p>
            @endif
        </div>
        @endif
    </div>
</div> 