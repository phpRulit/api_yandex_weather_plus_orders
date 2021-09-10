<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|integer',
            'client_email' => 'nullable|email',
            'delivery_dt' => 'nullable|date|after:2021-01-01'
        ];
    }

}
