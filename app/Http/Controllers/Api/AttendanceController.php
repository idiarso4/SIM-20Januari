<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $key = 'attendance_' . auth()->id();
            
            if (RateLimiter::tooManyAttempts($key, 60)) { // 60 requests per minute
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please try again later.'
                ], 429);
            }
            
            RateLimiter::hit($key);
            return $next($request);
        });
    }

    public function getAttendanceToday()
    {
        $userId = auth()->id();
        $cacheKey = "attendance_today_{$userId}";
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($userId) {
            $today = now()->startOfDay();
            $endOfDay = now()->endOfDay();
            
            $attendanceToday = Attendance::select([
                'id', 'start_time', 'end_time', 'created_at'
            ])
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$today, $endOfDay])
            ->first();

            $attendanceThisMonth = Attendance::select([
                'id', 'start_time', 'end_time', 'created_at'
            ])
            ->where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Attendance retrieved successfully.',
                'data' => [
                    'today' => $attendanceToday,
                    'this_month' => $attendanceThisMonth
                ]
            ]);
        });
    }

    public function getSchedule()
    {
        $userId = auth()->id();
        $cacheKey = "schedule_user_{$userId}";
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($userId) {
            $schedule = Schedule::with(['office', 'shift'])
                              ->where('user_id', $userId)
                              ->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'User belum mendapatkan jadwal kerja.',
                    'data' => null
                ]);
            }

            $today = Carbon::today()->format('Y-m-d');
            $approvedLeave = Leave::where('user_id', Auth::user()->id)
                                  ->where('status', 'approved')
                                  ->whereDate('start_date', '<=', $today)
                                  ->whereDate('end_date', '>=', $today)
                                  ->exists();

            if ($approvedLeave) {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda tidak dapat melakukan presensi karena sedang cuti.',
                    'data' => null
                ]);
            }

            if ($schedule->is_banned) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are banned',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Success get schedule',
                    'data' => $schedule
                ]);
            }
        });
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $schedule = Schedule::with(['office', 'shift'])
                              ->where('user_id', Auth::user()->id)
                              ->first();

            if (!$schedule || !$schedule->office || !$schedule->shift) {
                return response()->json([
                    'success' => false,
                    'message' => 'User belum mendapatkan jadwal kerja atau data office/shift tidak lengkap.',
                    'data' => null
                ], 404);
            }

            if ($schedule->is_banned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda sedang dalam status banned.',
                    'data' => null
                ], 403);
            }

            // Validasi jarak dengan kantor (dalam meter)
            $maxDistance = 100; // 100 meter
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $schedule->office->latitude,
                $schedule->office->longitude
            );

            if ($distance > $maxDistance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada terlalu jauh dari lokasi kantor.',
                    'data' => ['distance' => round($distance)]
                ], 400);
            }

            $attendance = Attendance::where('user_id', Auth::user()->id)
                                  ->whereDate('created_at', now()->toDateString())
                                  ->first();

            if (!$attendance) {
                // Validasi jam masuk
                $startTime = Carbon::createFromTimeString($schedule->shift->start_time);
                $currentTime = Carbon::now();
                $lateThreshold = $startTime->copy()->addMinutes(30); // Toleransi 30 menit

                if ($currentTime->gt($lateThreshold)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Waktu presensi masuk telah lewat.',
                        'data' => null
                    ], 400);
                }

                $attendance = Attendance::create([
                    'user_id' => Auth::user()->id,
                    'schedule_latitude' => $schedule->office->latitude,
                    'schedule_longitude' => $schedule->office->longitude,
                    'schedule_start_time' => $schedule->shift->start_time,
                    'schedule_end_time' => $schedule->shift->end_time,
                    'start_latitude' => $request->latitude,
                    'start_longitude' => $request->longitude,
                    'start_time' => $currentTime->toTimeString(),
                    'end_time' => null,
                ]);
            } else {
                // Validasi jam pulang
                $endTime = Carbon::createFromTimeString($schedule->shift->end_time);
                $currentTime = Carbon::now();
                
                if ($currentTime->lt($endTime)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya pulang.',
                        'data' => null
                    ], 400);
                }

                $attendance->update([
                    'end_latitude' => $request->latitude,
                    'end_longitude' => $request->longitude,
                    'end_time' => $currentTime->toTimeString(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully.',
                'data' => $attendance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $latDelta = $lat2 - $lat1;
        $lonDelta = $lon2 - $lon1;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($lat1) * cos($lat2) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    public function getAttendanceByMonthAndYear($month, $year)
    {
        try {
            $userId = auth()->id();
            $cacheKey = "attendance_{$userId}_{$month}_{$year}";
            
            return Cache::remember($cacheKey, now()->addHours(1), function () use ($userId, $month, $year) {
                $validator = Validator::make(['month' => $month, 'year' => $year], [
                    'month' => 'required|integer|between:1,12',
                    'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation error.',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $attendanceList = Attendance::where('user_id', $userId)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->orderBy('created_at', 'desc')
                    ->paginate(31);

                return response()->json([
                    'success' => true,
                    'message' => 'Attendance retrieved successfully.',
                    'data' => $attendanceList
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving attendance data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function banned()
    {
        $schedule = Schedule::where('user_id', Auth::user()->id)->first();
        
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
                'data' => null
            ], 404);
        }

        $schedule->update([
            'is_banned' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success banned schedule',
            'data' => $schedule
        ]);
    }

    public function getPhoto()
    {
        $user = auth()->user();
        $imageUrl = $user->image_url ?? null;

        return response()->json([
            'success' => true,
            'message' => $imageUrl ? 'Success get photo profile' : 'No photo available',
            'data' => $imageUrl
        ]);
    }
}