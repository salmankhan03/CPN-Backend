<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CouponCodeValidateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'coupon_code' => 'required',
            'cart_amount' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'coupon_code.required' => 'Coupon Code is required!',
            'cart_amount.required' => 'Cart Amount is required!'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
