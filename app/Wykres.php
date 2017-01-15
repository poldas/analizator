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
    ];

    protected $fillable = ['id', 'id_analiza', 'name', 'series', 'labels', 'tags'];
}
