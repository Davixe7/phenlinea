<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['admin_id', 'year', 'm1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8', 'm9', 'm10', 'm11', 'm12'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function admin(){
      return $this->belongsTo('App\Admin');
    }
    
    public function scopeCurrent($query){
      $year = date("Y") . '-01-01';
      return $query->where('year', $year);
    }
    
    public function scopeYear($query, $year){
      return $query->where('year', $year);
    }
}
