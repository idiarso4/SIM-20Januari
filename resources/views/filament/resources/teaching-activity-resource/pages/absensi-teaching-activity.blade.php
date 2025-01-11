<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <x-filament::input.wrapper>
                    <x-filament::input.label>Kelas</x-filament::input.label>
                    <x-filament::input type="text" value="{{ $record->kelas->nama }}" disabled />
                </x-filament::input.wrapper>
            </div>

            <div>
                <x-filament::input.wrapper>
                    <x-filament::input.label>Mata Pelajaran</x-filament::input.label>
                    <x-filament::input type="text" value="{{ $record->mata_pelajaran }}" disabled />
                </x-filament::input.wrapper>
            </div>

            <div>
                <x-filament::input.wrapper>
                    <x-filament::input.label>Tanggal</x-filament::input.label>
                    <x-filament::input type="text" value="{{ $record->tanggal->format('d/m/Y') }}" disabled />
                </x-filament::input.wrapper>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">Nama Siswa</th>
                        <th class="px-4 py-2">Status Kehadiran</th>
                        <th class="px-4 py-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $student->name }}</td>
                        <td class="px-4 py-2">
                            <x-filament::input.wrapper>
                                <select 
                                    class="w-full border-gray-300 rounded-lg shadow-sm"
                                    wire:model.live="state.{{ $student->id }}.status"
                                >
                                    <option value="present">Hadir</option>
                                    <option value="permit">Izin</option>
                                    <option value="sick">Sakit</option>
                                    <option value="absent">Alpha</option>
                                    <option value="dispensation">Dispensasi</option>
                                </select>
                            </x-filament::input.wrapper>
                        </td>
                        <td class="px-4 py-2">
                            <x-filament::input.wrapper>
                                <x-filament::input 
                                    type="text" 
                                    wire:model.live="state.{{ $student->id }}.keterangan"
                                    placeholder="Keterangan (opsional)"
                                />
                            </x-filament::input.wrapper>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium mb-4">Catatan Kejadian Penting</h3>
            <x-filament::input.wrapper>
                <textarea
                    wire:model.live="state.important_notes"
                    class="w-full border-gray-300 rounded-lg shadow-sm"
                    rows="4"
                    placeholder="Masukkan catatan kejadian penting selama pembelajaran (opsional)"
                ></textarea>
            </x-filament::input.wrapper>
        </div>

        <div class="mt-4">
            <x-filament::button
                wire:click="save"
                type="button"
            >
                Simpan Kehadiran
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page> 