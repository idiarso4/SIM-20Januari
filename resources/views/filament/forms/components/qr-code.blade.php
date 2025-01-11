@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$state = $getState();
$classRoomId = $getRecord()?->class_room_id ?? $state['class_room_id'] ?? null;
$timestamp = time(); // atau menggunakan now()

if ($classRoomId) {
    // Generate QR dengan timestamp
    $qrCode = QrCode::size(150)->generate(route('verify.class', [
        'id' => $classRoomId,
        'timestamp' => $timestamp,
        'token' => hash('sha256', $classRoomId . $timestamp . config('app.key'))
    ]));
}
@endphp

<!-- Auto refresh setiap 30 detik -->
<div id="qr-container">
    @if($classRoomId)
        <div>
            {!! $qrCode !!}
        </div>
        <script>
            setInterval(function() {
                location.reload();
            }, 30000); // Refresh setiap 30 detik
        </script>
    @else
        <div>
            <p>Silahkan pilih kelas terlebih dahulu</p>
        </div>
    @endif
</div> 