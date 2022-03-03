<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlide extends FormRequest
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
          'title'   => 'required|between:4,70',
          'body'    => 'required|between:100,500',
          'picture' => 'nullable|mimes:jpeg,bmp,png,webp'
        ];
    }
}
