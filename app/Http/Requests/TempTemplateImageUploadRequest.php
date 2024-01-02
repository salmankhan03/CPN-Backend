<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class TempTemplateImageUploadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'images' => 'required',
            'template_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'images.required' => 'Images Are required!',
            'template_id.required' => 'Template Id is required!'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
