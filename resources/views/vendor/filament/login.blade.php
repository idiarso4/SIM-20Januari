<x-filament-panels::page.simple>
    <x-slot name="subheading">
        <div class="flex justify-center w-full">
            <div class="w-full max-w-[400px]">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-auto mb-8">
            </div>
        </div>
    </x-slot>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>

@push('styles')
<style>
    .fi-simple-main {
        --tw-bg-opacity: 1;
        background-color: rgb(255 255 255 / var(--tw-bg-opacity));
    }
</style>
@endpush 