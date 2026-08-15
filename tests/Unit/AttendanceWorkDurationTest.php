<?php

namespace Tests\Unit;

use App\Models\Attendance;
use Tests\TestCase;

class AttendanceWorkDurationTest extends TestCase
{
    public function test_work_duration_minutes_is_calculated_from_check_in_to_check_out_on_save(): void
    {
        $attendance = new Attendance();
        $attendance->check_in = '08:00:00';
        $attendance->check_out = '17:00:00';

        $attendance->syncWorkDuration();

        $this->assertSame(540, $attendance->work_duration_minutes);
        $this->assertSame(540, $attendance->calculateWorkDurationMinutes());
    }
}
