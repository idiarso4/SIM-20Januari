<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'date' => $this->created_at->toDateString(),
            'is_late' => $this->when($this->start_time, function() {
                return $this->isLate();
            }),
            'total_hours' => $this->when($this->end_time, function() {
                return $this->getTotalHours();
            }),
        ];
    }
} 