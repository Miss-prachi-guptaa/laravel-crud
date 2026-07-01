<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;


class FaceRecognitionService
{
    /**
     * Generate face descriptor from uploaded image.
     *
     * @param string $imagePath
     * @return array
     */
    public function generateDescriptor(string $imagePath): array
{
    // $response = Http::attach(
    //     'image',
    //     fopen($imagePath, 'r'),
    //     basename($imagePath)
    // )->post(config('services.face_ai.url') . '/generate-descriptor');

    // if (!$response->successful()) {
    //     throw new \Exception('Unable to generate face descriptor.');
    // }

    // return $response->json('descriptor');
}
}