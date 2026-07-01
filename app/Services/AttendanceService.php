<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    protected FaceRecognitionService $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

  public function registerFace(User $user, UploadedFile $image)
{
     // Already registered?
    if ($user->is_face_registered) {
        return response()->json([
            'success' => false,
            'message' => 'Face already registered.'
        ], 400);
    }

    // Store uploaded image temporarily.

      $tempImagePath = $image->store('temp');
      $fullPath = Storage::path($tempImagePath);

    // Generate face descriptor.
      // Dummy descriptor
    $descriptor = [
        0.12,
        -0.44,
        0.89,
        0.55
    ];

    // Save into user

    $user->face_descriptor = json_encode($descriptor);
    $user->is_face_registered = true;
    $user->face_registered_at = now();

    // Update registration status.
     $user->save();

  

    
 // Delete temporary image
    Storage::delete($tempImagePath);

     return [
        'success' => true,
        'message' => 'Face registered successfully.',
    ];
}
}