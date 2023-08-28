<?php

namespace App\Api\Requests\Auth;

use App\Api\Requests\Request;

class ProductRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'price' => 'required|string',
            'produced_by' => 'required|string',
            'shipping_weight' => 'required|string',
            'product_code' => 'required|string',
            'upc_code' => 'required|string',
            'package_quantity' => 'required|string'
        ];
    }
}
