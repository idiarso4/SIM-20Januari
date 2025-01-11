<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAttendanceReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $month;
    protected $year;
    protected $userId;

    public function __construct($userId, $month, $year)
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;
    }

    public function handle()
    {
        // Process report generation
    }
} 