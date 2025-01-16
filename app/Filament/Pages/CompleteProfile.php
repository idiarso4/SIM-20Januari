<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;

class CompleteProfile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.complete-profile';
    protected static ?string $title = 'Complete Profile';
    
    // Define the form property
    public string $name = '';
    
    protected static string $routeBaseName = 'filament.admin.pages.complete-profile';
    
    public function mount(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('filament.admin.auth.login'));
            return;
        }
        
        $this->name = Auth::user()->name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                    ])
            ]);
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        Auth::user()->update($validated);
        
        $this->notify('success', 'Profile updated successfully.');
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->action('submit'),
        ];
    }

    public static function getSlug(): string
    {
        return 'complete-profile';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
} 