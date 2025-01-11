<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackupResource\Pages;
use App\Models\Backup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationLabel = 'Backup & Restore';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $slug = 'backups';
    protected static ?int $navigationSort = 9999;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Backup')
                    ->required()
                    ->default(fn () => 'backup_' . now()->format('Y-m-d_His')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama File')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn ($record) => Str::of(Storage::size($record->path))->toHumanFileSize()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i:s'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => route('filament.admin.resources.backups.download', $record->name))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Database')
                    ->modalDescription('Apakah Anda yakin ingin melakukan restore database? Semua data saat ini akan diganti dengan data dari backup ini.')
                    ->modalSubmitActionLabel('Ya, Restore')
                    ->modalCancelActionLabel('Batal')
                    ->action(fn ($record) => static::restoreBackup($record->path)),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus backup ini?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
        ];
    }

    protected static function restoreBackup(string $path): void
    {
        $host = config('database.connections.mysql.host');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $command = sprintf(
            'mysql -h %s -u %s -p%s %s < %s',
            $host,
            $username,
            $password,
            $database,
            Storage::path($path)
        );

        exec($command);

        Notification::make()
            ->title('Database berhasil di-restore')
            ->success()
            ->send();
    }
} 