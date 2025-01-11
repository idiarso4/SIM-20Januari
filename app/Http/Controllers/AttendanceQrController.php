<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceQrController extends Controller
{
    public function generateClassQr($classId, $subjectId)
    {
        $timestamp = Carbon::now()->timestamp;
        $token = "class_{$classId}_{$subjectId}_" . Str::random(10) . "_{$timestamp}";
        
        session(['class_qr_token' => $token]);
        
        $qrcode = QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->generate($token);
            
        return response($qrcode)->header('Content-Type', 'image/svg+xml');
    }

    public function generateExtraQr($extraId)
    {
        $timestamp = Carbon::now()->timestamp;
        $token = "extra_{$extraId}_" . Str::random(10) . "_{$timestamp}";
        
        session(['extra_qr_token' => $token]);
        
        $qrcode = QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->generate($token);
            
        return response($qrcode)->header('Content-Type', 'image/svg+xml');
    }

    public function validateClassToken(string $token)
    {
        $storedToken = session('class_qr_token');
        
        if (!$storedToken || $token !== $storedToken) {
            return response()->json(['status' => 'error', 'message' => 'Invalid QR code'], 400);
        }
        
        // Extract data from token
        $parts = explode('_', $token);
        $classId = $parts[1];
        $subjectId = $parts[2];
        $timestamp = end($parts);
        
        // Check if QR code is not expired (valid for 5 minutes)
        $tokenTime = Carbon::createFromTimestamp($timestamp);
        if ($tokenTime->diffInMinutes(now()) > 5) {
            return response()->json(['status' => 'error', 'message' => 'QR code expired'], 400);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Valid QR code',
            'data' => [
                'class_id' => $classId,
                'subject_id' => $subjectId
            ]
        ]);
    }

    public function validateExtraToken(string $token)
    {
        $storedToken = session('extra_qr_token');
        
        if (!$storedToken || $token !== $storedToken) {
            return response()->json(['status' => 'error', 'message' => 'Invalid QR code'], 400);
        }
        
        // Extract data from token
        $parts = explode('_', $token);
        $extraId = $parts[1];
        $timestamp = end($parts);
        
        // Check if QR code is not expired (valid for 5 minutes)
        $tokenTime = Carbon::createFromTimestamp($timestamp);
        if ($tokenTime->diffInMinutes(now()) > 5) {
            return response()->json(['status' => 'error', 'message' => 'QR code expired'], 400);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Valid QR code',
            'data' => [
                'extracurricular_id' => $extraId
            ]
        ]);
    }
} 