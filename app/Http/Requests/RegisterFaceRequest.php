<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterFaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:5120', // 5 MB
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Face image is required.',
            'image.image'    => 'Uploaded file must be an image.',
            'image.mimes'    => 'Only JPG, JPEG and PNG images are allowed.',
            'image.max'      => 'Image size must not exceed 5 MB.',
        ];
    }
}