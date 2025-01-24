<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentPermit;
use Illuminate\Auth\Access\HandlesAuthorization;
use Carbon\Carbon;
use App\Models\TeacherDuty;

class DutyTeacherPermitPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if (!$user->hasRole('guru')) {
            return false;
        }

        $today = Carbon::now();
        $dayName = str_replace(
            ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            $today->format('l')
        );

        return TeacherDuty::where('teacher_id', $user->id)
            ->where('day', $dayName)
            ->where('is_active', true)
            ->whereTime('start_time', '<=', now())
            ->whereTime('end_time', '>=', now())
            ->exists();
    }

    public function view(User $user, StudentPermit $studentPermit): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, StudentPermit $studentPermit): bool
    {
        return $this->viewAny($user) && $studentPermit->status === 'pending';
    }
} 