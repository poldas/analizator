<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analiza extends Model
{

    protected $table = "analiza";

    public $timestamps = false;

    protected $fillable = ['nazwa', 'file_path'];

    public function obszary() {
        return $this->hasMany('App\Obszar', 'id_analiza');
    }

    public function wyniki() {
        return $this->hasMany('App\Wynik', 'id_analiza');
    }

    public function uczniowie() {
        return $this->hasMany('App\Uczen', 'id_analiza');
    }
}
