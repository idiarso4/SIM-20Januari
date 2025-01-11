<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassVerificationController extends Controller
{
    public function verify($id)
    {
        $class = ClassRoom::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'class' => [
                'id' => $class->id,
                'nama' => $class->nama,
            ]
        ]);
    }
} 