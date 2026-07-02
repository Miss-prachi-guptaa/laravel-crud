<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Attendance;

class AttendanceService
{
    protected FaceRecognitionService $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

  public function registerFace(User $user, UploadedFile $image)
{
    if ($user->is_face_registered) {
        return response()->json([
            'success' => false,
            'message' => 'Face already registered.'
        ], 400);
    }

    $tempImagePath = $image->store('temp');
    $fullPath = Storage::path($tempImagePath);

    try {

        $descriptor = $this->faceRecognitionService
            ->generateDescriptor($fullPath);

              if (empty($descriptor)) {
            throw new Exception('Unable to generate face descriptor.');
        }

        $user->face_descriptor = json_encode($descriptor);
        $user->is_face_registered = true;
        $user->face_registered_at = now();
        $user->save();

        return [
            'success' => true,
            'message' => 'Face registered successfully.'
        ];

    } catch (\Exception $e) {

        

       return [
    'success' => false,
    'message' => $e->getMessage()
];
    }finally {

        Storage::delete($tempImagePath);

    }
}

  
   public function markAttendance(User $user, UploadedFile $image)
{
    if (!$user->is_face_registered) {
        return [
            'success' => false,
            'message' => 'Face not registered.'
        ];
    }

    $imagePath = $image->store('temp');
    $fullPath = Storage::path($imagePath);

    try {

        $currentDescriptor = $this->faceRecognitionService
            ->generateDescriptor($fullPath);

               if (empty($currentDescriptor)) {
            throw new Exception("Unable to generate face descriptor.");
        }

        $storedDescriptor = json_decode(
            $user->face_descriptor,
            true
        );

        $result = $this->faceRecognitionService
            ->compareDescriptors(
                $storedDescriptor,
                $currentDescriptor
            );

           if (!isset($result['matched'])) {
            throw new Exception("Invalid Face AI response.");
        }
  
        if (!$result['matched']) {

    return [
        'success' => false,
        'message' => 'Face verification failed.',
        'similarity' => $result['similarity']
    ];
}
         // Face verified successfully
         $today = now()->toDateString();

     $attendance = Attendance::where('employee_id', $user->empid)
          ->where('date', $today)
           ->first();

           if (!$attendance) {
             Attendance::create([
                'employee_id' => $user->empid,
                'date' => $today,
               'attendance_type' => 'FULL_DAY',
                  'attendance_state' => 'CHECKED_IN',
                 'status' => 'PRESENT',
              'check_in_time' => now(),
                  ]);

                return [
        'success' => true,
        'action' => 'CHECK_IN',
        'message' => 'Check In successful.',
        'similarity' => $result['similarity']
            ];    
           }

if ($attendance->attendance_state === 'CHECKED_IN') {

    $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
    $checkOut = now();
    $workedMinutes =(int) $checkIn->diffInMinutes($checkOut);
    $overtimeMinutes = 0;

    if ($workedMinutes > 480) {
        $overtimeMinutes = $workedMinutes - 480;
    }

    $attendanceType = 'FULL_DAY';

    if ($workedMinutes < 240) {
        $attendanceType = 'HALF_DAY';
    }

    $attendance->update([

        'check_out_time' => $checkOut,
        'worked_minutes' => $workedMinutes,
        'overtime_minutes' => $overtimeMinutes,
        'attendance_type' => $attendanceType,
        'attendance_state' => 'CHECKED_OUT'

    ]);

    return [

        'success' => true,
        'action' => 'CHECK_OUT',
        'message' => 'Check Out successful.',
        'worked_minutes' => $workedMinutes,
        'overtime_minutes' => $overtimeMinutes,
        'attendance_type' => $attendanceType,
        'similarity' => $result['similarity']

    ];

}

  return [
    'success' => true,
    'action' => 'COMPLETED',
    'message' => 'Attendance already completed for today.',
    'similarity' => $result['similarity']
    ]; 
    } finally {
        Storage::delete($imagePath);
    }
}


public function todayAttendance(User $user)
{

     
    $today = now()->toDateString();

    $attendance = Attendance::where('employee_id', $user->empid)
        ->where('date', $today)
        ->first();

    if (!$attendance) {
        return [
            'success' => true,
            'data' => [
                'attendanceState' => 'NOT_MARKED',
                'checkInTime' => null,
                'checkOutTime' => null,
                'workedMinutes' => 0,
                'workedHours' => 0,
                'status' => 'ABSENT'
            ]
        ];
    }

    return [
        'success' => true,
        'data' => [
            'attendanceState' => $attendance->attendance_state,
            'checkInTime' => $attendance->check_in_time,
            'checkOutTime' => $attendance->check_out_time,
            'workedMinutes' => $attendance->worked_minutes,
            'workedHours' => round(
                $attendance->worked_minutes / 60,
                2
            ),
            'status' => $attendance->status
        ]
    ];
}
}