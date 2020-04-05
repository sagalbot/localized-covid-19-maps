<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectedRegionsRequest extends FormRequest
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
            'regions' => 'array',
            'provinces' => 'array',
            'countries' => 'array',
            'provinces.*.id' => 'exists:provinces',
            'countries.*.id' => 'exists:provinces',
        ];
    }
}
