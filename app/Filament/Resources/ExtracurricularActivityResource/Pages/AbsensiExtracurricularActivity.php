<?php

namespace App\Filament\Resources\ExtracurricularActivityResource\Pages;

use App\Filament\Resources\ExtracurricularActivityResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use App\Models\ExtracurricularActivity;
use App\Models\ExtracurricularAttendance;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class AbsensiExtracurricularActivity extends Page
{
    protected static string $resource = ExtracurricularActivityResource::class;

    protected static string $view = 'filament.resources.extracurricular-activity-resource.pages.absensi-extracurricular-activity';

    public ExtracurricularActivity $record;

    public Collection $students;
    public array $attendanceData = [];

    public function mount(ExtracurricularActivity $record): void
    {
        $this->record = $record;
        
        // Ambil data siswa yang mengikuti ekstrakurikuler ini
        $this->students = $record->extracurricular->students()
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        // Inisialisasi data absensi
        foreach ($this->students as $student) {
            $attendance = $record->studentAttendances()
                ->where('student_id', $student->id)
                ->first();

            $this->attendanceData[$student->id] = [
                'status' => $attendance?->status ?? 'present',
                'keterangan' => $attendance?->keterangan ?? '',
            ];
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function updateAttendance(): void
    {
        // Hapus data absensi yang lama
        $this->record->studentAttendances()->delete();

        // Simpan data absensi yang baru
        foreach ($this->attendanceData as $studentId => $data) {
            ExtracurricularAttendance::create([
                'extracurricular_activity_id' => $this->record->id,
                'student_id' => $studentId,
                'status' => $data['status'] ?? 'present',
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        }

        Notification::make()
            ->success()
            ->title('Absensi disimpan')
            ->body('Data absensi berhasil diperbarui.')
            ->send();
    }
} 