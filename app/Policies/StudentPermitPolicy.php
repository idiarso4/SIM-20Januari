<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentPermit;
use Illuminate\Auth\Access\HandlesAuthorization;
use Carbon\Carbon;
use App\Models\TeacherDuty;

class StudentPermitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_student::permit');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentPermit $studentPermit): bool
    {
        return $user->can('view_student::permit');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_student::permit');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentPermit $studentPermit): bool
    {
        return $user->can('update_student::permit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentPermit $studentPermit): bool
    {
        return $user->can('delete_student::permit');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_student::permit');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, StudentPermit $studentPermit): bool
    {
        return $user->can('force_delete_student::permit');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_student::permit');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, StudentPermit $studentPermit): bool
    {
        return $user->can('restore_student::permit');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_student::permit');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, StudentPermit $studentPermit): bool
    {
        return $user->can('replicate_student::permit');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_student::permit');
    }

    public function viewDutyTeacherPermits(User $user): bool
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
}
