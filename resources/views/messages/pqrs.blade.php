Unidad: *{{$petition->admin->name}}*  
Su PQRS ha sido actualizado con éxito. Y su código de seguimiento es el *{{str_pad($petition->id, 4, '0', STR_PAD_LEFT)}}*  
Estado: *{{ ['pending'=>'Pendiente', 'read'=>'Leído', 'replied'=>'Respuesta enviada'][ $petition->status ] }}*  
Link de seguimiento del estado: {{ route('pqrs.show', compact('petition')) }}  
Servicio prestado por PHenlinea.com