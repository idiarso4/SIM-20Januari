<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyClassController
{
    public function verify(Request $request, $id)
    {
        $timestamp = $request->timestamp;
        $token = $request->token;
        
        // Validasi token
        $expectedToken = hash('sha256', $id . $timestamp . config('app.key'));
        
        if ($token !== $expectedToken) {
            return response()->json(['error' => 'QR Code tidak valid']);
        }
        
        // Cek waktu kadaluarsa (misal 1 menit)
        if (time() - $timestamp > 60) {
            return response()->json(['error' => 'QR Code sudah kadaluarsa']);
        }
        
        // Proses verifikasi
        // ...
    }
} 