<?php

namespace App\Http\Controllers;

use App\Models\TeachingActivity;
use Illuminate\Http\Request;

class TeachingActivityController
{
    public function store(Request $request)
    {
        $exists = TeachingActivity::where('guru_id', auth()->id())
            ->whereDate('tanggal', $request->tanggal)
            ->exists();
        
        if ($exists) {
            return back()->with('error', 'Anda sudah memiliki catatan mengajar untuk tanggal ini');
        }
        
        // lanjutkan proses penyimpanan
    }
} 