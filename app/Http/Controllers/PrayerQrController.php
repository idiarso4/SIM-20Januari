<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PrayerQrController extends Controller
{
    public function generate()
    {
        // Generate a unique token that includes timestamp
        $timestamp = Carbon::now()->timestamp;
        $token = Str::random(10) . '_' . $timestamp;
        
        // Store token in session for validation
        session(['prayer_qr_token' => $token]);
        
        // Generate QR code with the token
        $qrcode = QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->generate($token);
            
        return response($qrcode)->header('Content-Type', 'image/svg+xml');
    }

    public function validateToken(string $token)
    {
        $storedToken = session('prayer_qr_token');
        
        if (!$storedToken || $token !== $storedToken) {
            return response()->json(['status' => 'error', 'message' => 'Invalid QR code'], 400);
        }
        
        // Extract timestamp from token
        $tokenParts = explode('_', $token);
        $timestamp = (int) end($tokenParts);
        
        // Check if QR code is not expired (valid for 5 minutes)
        $tokenTime = Carbon::createFromTimestamp($timestamp);
        if ($tokenTime->diffInMinutes(now()) > 5) {
            return response()->json(['status' => 'error', 'message' => 'QR code expired'], 400);
        }
        
        return response()->json(['status' => 'success', 'message' => 'Valid QR code']);
    }
} 