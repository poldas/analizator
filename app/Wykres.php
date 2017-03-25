<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wykres extends Model
{
    protected $table = 'wykresy';

    public $timestamps = false;

    protected $casts = [
        'labels' => 'array',
        'tags' => 'array',
        'series' => 'array',
        'options' => 'array',
    ];

    protected $fillable = ['name', 'id_analiza', 'series', 'options', 'opis', 'labels', 'tags'];
}
