<?php

namespace App\Filament\Resources\BackupResource\Pages;

use App\Filament\Resources\BackupResource;
use App\Models\Backup;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Exception;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Buat Backup')
                ->icon('heroicon-o-plus')
                ->requiresConfirmation()
                ->modalHeading('Buat Backup Database')
                ->modalDescription('Apakah Anda yakin ingin membuat backup database?')
                ->modalSubmitActionLabel('Ya, Buat Backup')
                ->modalCancelActionLabel('Batal')
                ->action(function () {
                    try {
                        // Get database configuration
                        $host = config('database.connections.mysql.host');
                        $database = config('database.connections.mysql.database');
                        $username = config('database.connections.mysql.username');
                        $password = config('database.connections.mysql.password');

                        // Create backup filename
                        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';
                        
                        // Ensure backups directory exists
                        Storage::makeDirectory('backups');
                        
                        // Get absolute path for backup file
                        $storagePath = storage_path('app/backups');
                        $backupPath = $storagePath . '/' . $filename;
                        
                        // Create backup command
                        $command = sprintf(
                            'mysqldump --no-tablespaces -h %s -u %s -p%s %s > %s',
                            escapeshellarg($host),
                            escapeshellarg($username),
                            escapeshellarg($password),
                            escapeshellarg($database),
                            escapeshellarg($backupPath)
                        );

                        // Execute backup
                        exec($command, $output, $returnCode);
                        
                        if ($returnCode !== 0) {
                            throw new Exception('Gagal membuat backup database. Error code: ' . $returnCode);
                        }

                        // Verify file exists and has content
                        if (!file_exists($backupPath) || filesize($backupPath) === 0) {
                            throw new Exception('File backup tidak berhasil dibuat atau kosong');
                        }

                        // Create backup record
                        Backup::create([
                            'name' => $filename,
                            'path' => 'backups/' . $filename,
                            'created_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Backup berhasil dibuat')
                            ->success()
                            ->send();

                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Error saat membuat backup')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }

                    $this->refresh();
                }),
        ];
    }
} 