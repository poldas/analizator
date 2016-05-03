<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uczen extends Model
{
    protected $table = "uczniowie";

    public $timestamps = false;

    protected $casts = [
        'kod_ucznia' => 'string',
    ];

    protected $dates = ['updated_at'];

    protected $fillable = ['id_analiza', 'nr_ucznia', 'kod_ucznia', 'klasa', 'plec', 'dysleksja', 'lokalizacja', 'updated_at'];
}
