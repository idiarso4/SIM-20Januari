<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\TeacherDuty;
use App\Policies\TeacherDutyPolicy;
use App\Models\StudentPermit;
use App\Policies\StudentPermitPolicy;
use App\Policies\DutyTeacherPermitPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TeacherDuty::class => TeacherDutyPolicy::class,
        StudentPermit::class => StudentPermitPolicy::class,
        'App\Models\StudentPermit' => DutyTeacherPermitPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
} 