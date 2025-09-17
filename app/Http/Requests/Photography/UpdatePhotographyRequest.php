<?php

namespace App\Http\Requests\Photography;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePhotographyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
       return [
            'category'=>'required',
            'title'=>'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'price'=>'required',
            // 'discount_price'=>'required',
        ];
    }
}
