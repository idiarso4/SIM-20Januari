<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Surat Izin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Surat Izin</h1>
                <p class="text-gray-600">SMKN 1 Punggelan</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Nama Siswa</label>
                    <p class="mt-1 text-lg font-semibold">{{ $permit->student->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Kelas</label>
                    <p class="mt-1">{{ $permit->student->class_room?->name ?? '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Tanggal</label>
                    <p class="mt-1">{{ $permit->permit_date->format('d F Y') }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Waktu</label>
                    <p class="mt-1">{{ $permit->start_time->format('H:i') }} - {{ $permit->end_time ? $permit->end_time->format('H:i') : '...' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Alasan</label>
                    <p class="mt-1">{{ $permit->reason }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Disetujui Oleh</label>
                    <p class="mt-1">{{ $permit->approver->name }}</p>
                    <p class="text-sm text-gray-500">{{ $permit->approver->mata_pelajaran ?? 'Wali Kelas' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1">
                        @if($permit->isApproved())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Tidak Valid
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Diverifikasi pada: {{ now()->format('d F Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html> 