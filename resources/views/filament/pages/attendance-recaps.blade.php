<x-filament-panels::page>
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    <x-filament::section>
        <div class="grid grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-lg font-semibold text-gray-700">Total</div>
                <div class="text-3xl font-bold">{{ $this->summary['total'] }}</div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4">
                <div class="text-lg font-semibold text-green-700">Hadir</div>
                <div class="text-3xl font-bold text-green-600">{{ $this->summary['hadir'] }}</div>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4">
                <div class="text-lg font-semibold text-yellow-700">Izin</div>
                <div class="text-3xl font-bold text-yellow-600">{{ $this->summary['izin'] }}</div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-4">
                <div class="text-lg font-semibold text-blue-700">Sakit</div>
                <div class="text-3xl font-bold text-blue-600">{{ $this->summary['sakit'] }}</div>
            </div>
            <div class="bg-red-50 rounded-lg shadow p-4">
                <div class="text-lg font-semibold text-red-700">Alpha</div>
                <div class="text-3xl font-bold text-red-600">{{ $this->summary['alpha'] }}</div>
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page> 