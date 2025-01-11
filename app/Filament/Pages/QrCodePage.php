<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Extracurricular;

class QrCodePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Code';
    protected static ?string $navigationGroup = 'Attendance Management';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.qr-code';

    public ?string $selectedType = null;
    public ?string $classId = null;
    public ?string $subjectId = null;
    public ?string $extraId = null;

    public function mount(): void
    {
        $this->selectedType = 'prayer';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('selectedType')
                            ->label('QR Code Type')
                            ->options([
                                'prayer' => 'Prayer Attendance',
                                'class' => 'Class Attendance',
                                'extra' => 'Extracurricular Attendance',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function () {
                                $this->classId = null;
                                $this->subjectId = null;
                                $this->extraId = null;
                            }),

                        Select::make('classId')
                            ->label('Class')
                            ->options(ClassRoom::pluck('nama', 'id'))
                            ->visible(fn ($get) => $get('selectedType') === 'class')
                            ->required(fn ($get) => $get('selectedType') === 'class')
                            ->live(),

                        Select::make('subjectId')
                            ->label('Subject')
                            ->options(Subject::pluck('nama', 'id'))
                            ->visible(fn ($get) => $get('selectedType') === 'class')
                            ->required(fn ($get) => $get('selectedType') === 'class'),

                        Select::make('extraId')
                            ->label('Extracurricular')
                            ->options(Extracurricular::pluck('nama', 'id'))
                            ->visible(fn ($get) => $get('selectedType') === 'extra')
                            ->required(fn ($get) => $get('selectedType') === 'extra'),
                    ])
            ]);
    }

    public function getQrCodeUrl(): ?string
    {
        if (!$this->selectedType) {
            return null;
        }

        switch ($this->selectedType) {
            case 'prayer':
                return route('prayer.qr.generate');
            case 'class':
                if (!$this->classId || !$this->subjectId) {
                    return null;
                }
                return route('class.qr.generate', ['classId' => $this->classId, 'subjectId' => $this->subjectId]);
            case 'extra':
                if (!$this->extraId) {
                    return null;
                }
                return route('extra.qr.generate', ['extraId' => $this->extraId]);
            default:
                return null;
        }
    }
} 