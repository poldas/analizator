<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wynik extends Model
{
    protected $table = "wyniki_egzaminu";

    public $timestamps = false;

    protected $fillable = ['nr_zadania', 'kod_ucznia', 'klasa', 'id_analiza', 'liczba_punktow', 'max_punktow'];
}
