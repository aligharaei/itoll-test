<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCargoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'origin_lat' => 'required|numeric|between:-90,90',
            'origin_long' => 'required|numeric|between:-180,180',
            'origin_address' => 'required|string',
            'sender_name' => 'required|string',
            'sender_mobile' => 'required|numeric|digits:10',
            'destination_lat' => 'required|numeric|between:-90,90',
            'destination_long' => 'required|numeric|between:-180,180',
            'destination_address' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_mobile' => 'required|numeric|digits:10'
        ];
    }
}
