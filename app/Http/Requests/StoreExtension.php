<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtension extends FormRequest
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
    $admin = auth()->guard('admin')->user();

    return [
      'name' => [
        function($att, $value, $fail) use($admin){
          if( !$this->extension || $this->extension->name != $this->name ){
            $extensionsCount = $admin->extensions()->name( $this->name )->count();
            if( $extensionsCount >= 1 ){
              $fail('Ya controlas un registro de la unidad que intentas guardar');
            }
          }
        }
      ],
      'phone_1' => 'required|numeric|digits_between:10,10',
      'phone_2' => 'nullable|numeric|digits_between:10,10',
      'phone_3' => 'nullable|numeric|digits_between:10,10',
      'phone_4' => 'nullable|numeric|digits_between:3,15',
    ];
  }

  public function attributes()
  {
    return [
      'phone_1'   => 'Teléfono 1',
      'phone_2'   => 'Teléfono 2',
      'phone_3'   => 'Teléfono 3',
      'phone_4'   => 'Teléfono 4',
    ];
  }
}
