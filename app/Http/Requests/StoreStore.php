<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStore extends FormRequest
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
          'category'    => 'required|string|max:192',
          'name'        => 'required|string|max:192',
          'description' => 'string|max:320',
          'address'     => 'string|max:320',
          'phone_1'     => 'required|string',
          // 'logo'        => 'image',
          'pictures.*'  => 'image',
        ];
    }
    
    public function attributes(){
      return [
        'phone_1' => 'Teléfono'
      ];
    }
}
