<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class CompleteProfile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.complete-profile';
    protected static ?string $title = 'Complete Profile';
    
    public ?array $data = [];

    protected static string $routeBaseName = 'filament.admin.pages.complete-profile';
    
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('filament.admin.auth.login');
        }
        
        $this->form->fill([
            'name' => Auth::user()->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        
        Auth::user()->update([
            'name' => $data['name'],
        ]);
        
        $this->notify('success', 'Profile updated successfully.');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
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