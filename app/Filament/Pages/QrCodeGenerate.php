<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\TeachingActivity;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGenerate extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $title = 'QR Code Presensi';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.qr-code-generate';

    public $classId;
    public $subjectId;
    public $qrCode;
    public $className;
    public $subjectName;

    public function mount($classId, $subjectId): void
    {
        $this->classId = $classId;
        $this->subjectId = $subjectId;

        $class = ClassRoom::findOrFail($classId);
        $subject = Subject::findOrFail($subjectId);

        $this->className = $class->name;
        $this->subjectName = $subject->name;

        // Generate QR Code
        $teachingActivity = TeachingActivity::where([
            'guru_id' => auth()->id(),
            'class_room_id' => $classId,
            'subject_id' => $subjectId,
            'date' => now()->toDateString(),
        ])->firstOrFail();

        $this->qrCode = base64_encode(QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($teachingActivity->id));
    }
} 