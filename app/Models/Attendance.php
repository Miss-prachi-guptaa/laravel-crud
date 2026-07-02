<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'attendance_type',
        'attendance_state',
        'status',
        'check_in_time',
        'check_out_time',
        'worked_minutes',
        'overtime_minutes',
    ];
}
