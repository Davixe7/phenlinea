<?php

namespace App\Http\Requests\v2;

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
      'name'          => 'required',
      'nit'           => 'required|unique:admins',
      'address'       => 'required',
      'contact_email' => 'required',
      'phone'         => 'required|numeric|digits_between:10,12',
      'phone_2'       => 'nullable|numeric|digits_between:10,12',

      'email'         => 'required|unique:admins',
      'password'      => 'required|min:6'
    ];
  }

  public function attributes()
  {
    return [
      'name'          => 'Nombre',
      'address'       => 'Direccion',
      'contact_email' => 'Email de contacto',
      'phone_2'       => 'Teléfono 2',
      'password'      => 'Contraseña',
    ];
  }
}
