<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center space-y-4">
        <div class="text-center">
            <h2 class="text-2xl font-bold">QR Code Presensi Ekstrakurikuler</h2>
            <p class="text-gray-500">QR Code akan diperbarui setiap 5 menit</p>
        </div>

        <div class="w-full max-w-xl bg-white rounded-lg shadow-md p-6">
            <form wire:submit="generateQr" class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-center mt-4">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Generate QR Code
                    </button>
                </div>
            </form>
        </div>
        
        @if($selectedExtra)
        <div class="bg-white p-4 rounded-lg shadow-md">
            <img src="{{ route('extra.qr.generate', ['extraId' => $selectedExtra]) }}" 
                alt="QR Code Presensi" 
                class="w-64 h-64">
        </div>
        
        <div class="text-center">
            <p class="text-sm text-gray-500">Scan QR Code ini menggunakan aplikasi untuk melakukan presensi</p>
            <p class="text-sm text-gray-500">QR Code hanya berlaku selama 5 menit</p>
        </div>
        
        <button type="button" onclick="window.location.reload()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
            Perbarui QR Code
        </button>
        @endif
    </div>
</x-filament-panels::page> 