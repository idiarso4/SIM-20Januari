<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeacherDuty;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherDutyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'guru']);
    }

    public function view(User $user, TeacherDuty $teacherDuty): bool
    {
        return $user->hasRole(['admin', 'guru']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, TeacherDuty $teacherDuty): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, TeacherDuty $teacherDuty): bool
    {
        return $user->hasRole('admin');
    }
} 