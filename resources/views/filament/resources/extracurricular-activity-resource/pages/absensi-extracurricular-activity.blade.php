<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ekstrakurikuler</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $record->extracurricular->nama }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Pembina</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $record->extracurricular->guru->name }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tanggal</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $record->tanggal->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Materi</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $record->materi }}</p>
            </div>

            <div class="mt-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Hadir</h3>
                    <x-filament::button wire:click="updateAttendance" type="button">
                        Simpan Absensi
                    </x-filament::button>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">Nama Siswa</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($students as $index => $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $student->name }}</td>
                                    <td class="px-4 py-2">
                                        <select
                                            wire:model="attendanceData.{{ $student->id }}.status"
                                            class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        >
                                            <option value="present">Hadir</option>
                                            <option value="permit">Izin</option>
                                            <option value="sick">Sakit</option>
                                            <option value="absent">Alpha</option>
                                            <option value="dispensation">Dispensasi</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input
                                            type="text"
                                            wire:model="attendanceData.{{ $student->id }}.keterangan"
                                            class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white w-full"
                                            placeholder="Tambahkan keterangan..."
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page> 