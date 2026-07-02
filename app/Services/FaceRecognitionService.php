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
    $response = Http::timeout(60)
            ->attach(
                'image',
                fopen($imagePath, 'r'),
                basename($imagePath)
            )
            ->post(config('services.face_ai.url') . '/generate-descriptor');

        if (!$response->successful()) {
            throw new Exception(
                $response->json('detail') ?? 'Unable to generate descriptor.'
            );
        }

        return $response->json('descriptor');
}

public function compareDescriptors(
    array $descriptor1,
    array $descriptor2
): array {

    $response = Http::timeout(60)
        ->post(
            config('services.face_ai.url').'/compare-faces',
            [
                'descriptor1' => $descriptor1,
                'descriptor2' => $descriptor2,
            ]
        );

    if (!$response->successful()) {
        throw new \Exception(
            $response->json('detail')
            ?? 'Unable to compare faces.'
        );
    }

    return $response->json();
}}
    