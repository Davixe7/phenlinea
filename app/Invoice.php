<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['number','nit','total','date', 'status', 'paid_at'];

    public function admin(){
      return $this->belongsTo('App\Admin', 'nit', 'nit')->withDefault([
        'name' => 'Usuario eliminado',
        'nit'  => 'No disponible'
      ]);
    }

    public function scopeInMonth($query, $month, $year){
      $date = Carbon::now()->year( $year )->month( $month );
      $start = $date->startOfMonth()->format('Y-m-d');
      $end   = $date->endOfMonth()->format('Y-m-d');
      return $query->whereBetween('date', [$start, $end]);
    }
}
