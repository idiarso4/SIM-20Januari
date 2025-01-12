<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use App\Imports\GuruImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuruExport;
use App\Exports\GuruTemplateExport;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class GuruResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Data Guru';

    protected static ?string $modelLabel = 'Guru';

    protected static ?string $pluralModelLabel = 'Data Guru';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'import',
            'export'
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'guru');
            })
            ->orderBy('created_at', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(table: User::class, ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('assignGuruRole')
                        ->label('Assign Role Guru (Massal)')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            $guruRole = Role::firstOrCreate(['name' => 'guru']);
                            $count = 0;

                            $records->each(function ($user) use ($guruRole, &$count) {
                                if (!$user->hasRole('guru')) {
                                    $user->assignRole($guruRole);
                                    $count++;
                                }
                            });

                            Notification::make()
                                ->title($count . ' user berhasil ditambahkan role guru')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('removeGuruRole')
                        ->label('Hapus Role Guru (Massal)')
                        ->icon('heroicon-o-user-minus')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            $count = 0;

                            $records->each(function ($user) use (&$count) {
                                if ($user->hasRole('guru')) {
                                    $user->removeRole('guru');
                                    $count++;
                                }
                            });

                            Notification::make()
                                ->title($count . ' user berhasil dihapus role guru')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('new_guru')
                    ->label('Tambah Guru')
                    ->url(route('filament.admin.resources.gurus.create'))
                    ->icon('heroicon-o-plus'),

                Tables\Actions\Action::make('import')
                    ->label('Import Data Guru')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('File Excel')
                            ->disk('public')
                            ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        try {
                            $storage = \Illuminate\Support\Facades\Storage::disk('public');
                            $filePath = $storage->path($data['file']);

                            if (!$storage->exists($data['file'])) {
                                throw new \Exception('File tidak ditemukan di: ' . $filePath);
                            }

                            Excel::import(new GuruImport, $filePath);

                            // Clean up the temp file
                            $storage->delete($data['file']);

                            FilamentNotification::make()
                                ->title('Berhasil')
                                ->body('Data guru berhasil diimport')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            FilamentNotification::make()
                                ->title('Gagal Import')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(route('download.template.guru'))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('export')
                    ->label('Download Data')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(new GuruExport, 'data_guru.xlsx');
                    }),

                Tables\Actions\Action::make('reset')
                    ->label('Reset Data')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        User::whereHas('roles', function ($query) {
                            $query->where('name', 'guru');
                        })->delete();

                        FilamentNotification::make()
                            ->title('Berhasil')
                            ->body('Data guru berhasil direset')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }

    public static function generateTemplate()
    {
        return Excel::download(new GuruTemplateExport, 'template_guru.xlsx');
    }
}
