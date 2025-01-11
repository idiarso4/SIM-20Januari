<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center space-y-4">
        <div class="text-center">
            <h2 class="text-2xl font-bold">{{ $className }}</h2>
            <p class="text-lg">{{ $subjectName }}</p>
            <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="mx-auto">
        </div>

        <div class="text-center text-sm text-gray-500">
            <p>Scan QR Code untuk melakukan presensi</p>
        </div>
    </div>
</x-filament-panels::page> 