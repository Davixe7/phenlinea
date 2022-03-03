<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminder extends FormRequest
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
      'title'       => 'required|min:5|max:128',
      'description' => 'required|min:5|max:1000',
      'pictures.*'  => 'image|max:1000',
    ];
  }
  
  public function attributes()
  {
    return [
      'title'       => 'título',
      'description' => 'descripción',
      'pictures.*'  => 'imagen'
    ];
  }
  
  public function messages()
  {
    return [
      'pictures.max' => 'El limite de imagenes adjuntas es de 7'
    ];
  }
}
