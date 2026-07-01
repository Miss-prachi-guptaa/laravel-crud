<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AttendanceService;
use App\Http\Requests\RegisterFaceRequest;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function registerFace(RegisterFaceRequest $request)
{
       $user = User::latest()->first();

       //$user = $request->user(); integration k time 

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'No user found.'
        ], 404);
    } // Temporary

    $result= $this->attendanceService->registerFace(
        $user,
        $request->file('image')
    );

     return response()->json($result);
}

public function today(Request $request)
{

}

public function markAttendance(MarkAttendanceRequest $request)
{

}
}
