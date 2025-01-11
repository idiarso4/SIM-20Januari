<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit="save">
            {{ $this->form }}
        </form>

        @if($this->getQrCodeUrl())
            <div class="p-6 bg-white rounded-lg shadow">
                <div class="text-center">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                        QR Code
                    </h3>
                    <div class="flex justify-center">
                        <img src="{{ $this->getQrCodeUrl() }}" alt="QR Code" class="w-64 h-64">
                    </div>
                    <p class="mt-4 text-sm text-gray-500">
                        This QR code will expire in 5 minutes.
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page> 