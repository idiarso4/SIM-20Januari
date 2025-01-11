<?php

namespace App\Filament\Resources\TeachingActivityResource\Pages;

use App\Filament\Resources\TeachingActivityResource;
use Filament\Resources\Pages\Page;
use App\Models\User;
use App\Models\StudentAttendance;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class AbsensiTeachingActivity extends Page
{
    use InteractsWithRecord;

    protected static string $resource = TeachingActivityResource::class;

    protected static string $view = 'filament.resources.teaching-activity-resource.pages.absensi-teaching-activity';
    
    protected static ?string $slug = 'absensi';

    public $students;
    public $state = [];
    public $attendances = [];

    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->loadData();
    }

    protected function loadData(): void
    {
        // Ambil semua siswa di kelas ini
        $this->students = User::where('class_room_id', $this->record->class_room_id)
            ->where('role', 'siswa')
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        // Ambil data kehadiran yang sudah ada
        $existingAttendances = StudentAttendance::where('teaching_activity_id', $this->record->id)
            ->get()
            ->keyBy('student_id');

        // Inisialisasi state untuk setiap siswa
        foreach ($this->students as $student) {
            $attendance = $existingAttendances->get($student->id);
            
            $this->state[$student->id] = [
                'status' => $attendance ? $attendance->status : 'present',
                'keterangan' => $attendance ? $attendance->keterangan : '',
            ];
        }

        // Load catatan kejadian penting
        $this->state['important_notes'] = $this->record->important_notes ?? '';
    }

    public function save(): void
    {
        try {
            DB::beginTransaction();

            // Simpan catatan kejadian penting
            $this->record->update([
                'important_notes' => $this->state['important_notes'] ?? null
            ]);

            // Simpan data kehadiran
            foreach ($this->state as $studentId => $data) {
                if (!is_numeric($studentId)) continue; // Skip key yang bukan ID siswa

                StudentAttendance::updateOrCreate(
                    [
                        'teaching_activity_id' => $this->record->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? '',
                    ]
                );
            }

            DB::commit();

            $this->loadData(); // Reload data setelah simpan

            Notification::make()
                ->title('Data kehadiran dan catatan berhasil disimpan')
                ->success()
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal menyimpan data')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->url(TeachingActivityResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        return static::$resource::getUrl('absensi', $parameters, $isAbsolute, $panel, $tenant);
    }
} 