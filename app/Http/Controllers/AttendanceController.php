<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AttendanceService;
use App\Http\Requests\RegisterFaceRequest;
use App\Http\Requests\MarkAttendanceRequest;

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

    // TODO:
 // Replace with $request->user() after authentication integration.

       $user = User::where('empid', $request->empid)->first();

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
    // TODO:
    // Replace with authenticated user after login integration.
    // Current:
    // $user = User::where('empid', $request->empid)->first();
    //
    // Future:
    // $user = $request->user();

    $user = User::where('empid', $request->empid)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found.'
        ], 404);
    }

    return response()->json(
        $this->attendanceService->todayAttendance($user)
    );
}

public function markAttendance(
    MarkAttendanceRequest $request
)
{
      // TODO:
// Replace with authenticated user after login integration.
// Current:
// $user = User::where('empid', $request->empid)->first();
//
// Future:
// $user = $request->user();
      $user = User::where('empid', $request->empid)->first();

    return $this->attendanceService->markAttendance(
        $user,
        $request->file('image')
    );
}
}