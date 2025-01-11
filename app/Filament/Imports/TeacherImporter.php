<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

class TeacherImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nama')
                ->required()
                ->example('John Doe'),
            ImportColumn::make('email')
                ->label('Email')
                ->required()
                ->rules(['email'])
                ->example('john.doe@example.com'),
            ImportColumn::make('password')
                ->label('Password')
                ->required()
                ->example('password123')
                ->rules(['min:8']),
            ImportColumn::make('telp')
                ->label('No. Telepon')
                ->example('081234567890'),
            ImportColumn::make('alamat')
                ->label('Alamat')
                ->example('Jl. Contoh No. 123'),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = new User();
        $user->password = Hash::make($this->data['password']);
        return $user;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole('guru');
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data guru selesai dan ' . number_format($import->successful_rows) . ' ' . str('data')->plural($import->successful_rows) . ' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('data')->plural($failedRowsCount) . ' gagal diimport.';
        }

        return $body;
    }
}
