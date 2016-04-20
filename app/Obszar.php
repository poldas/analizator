<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obszar extends Model
{
    protected $table = "obszary";

    public $timestamps = false;

    protected $fillable = ['id_analiza', 'obszar', 'umiejetnosc', 'nr_zadania'];
}
