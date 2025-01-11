<div class="verification-container">
    <!-- Opsi 1: Scan QR -->
    <div class="qr-scanner">
        <h3>Scan QR Code</h3>
        <!-- Implementasi scanner menggunakan library seperti instascan atau html5-qrcode -->
        <div id="reader"></div>
    </div>

    <!-- Opsi 2: Input Manual -->
    <div class="manual-input">
        <h3>Input Manual</h3>
        <form action="{{ route('verify.class') }}" method="POST">
            @csrf
            <input type="text" name="class_id" placeholder="Masukkan ID Kelas">
            <button type="submit">Verifikasi</button>
        </form>
    </div>
</div>

<!-- Script untuk QR Scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Handle hasil scan QR
        window.location.href = decodedText;
    }

    function onScanFailure(error) {
        // Handle error scan
        console.warn(`QR error = ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 250} },
        /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script> 