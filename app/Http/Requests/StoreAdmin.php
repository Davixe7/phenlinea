<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmin extends FormRequest
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
      'contact_email'     => 'required',
      'nit'     => ['required', Rule::unique('admins')->ignore($this->admin)],
      'email'   => ['required', Rule::unique('admins')->ignore($this->admin)],
      'phone'   => 'required|numeric|digits_between:10,10',
      'phone_2' => 'nullable|numeric|digits_between:10,10',
      'password' => 'nullable|min:6'
    ];
  }

  public function attributes()
  {
    return [
      'phone'   => 'Teléfono 1',
      'phone_2' => 'Teléfono 2',
      'password' => 'Contraseña',
    ];
  }
}
