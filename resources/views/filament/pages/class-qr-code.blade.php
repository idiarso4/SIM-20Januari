<x-filament-panels::page>
    <form wire:submit="generateQr">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">
                Generate QR Code
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page> 