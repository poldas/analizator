<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wykres extends Model
{
    protected $table = 'wykresy';

    public $timestamps = false;

    protected $casts = [
        'options' => 'array',
        'category' => 'array',
        'series' => 'array',
    ];

    protected $fillable = ['id_analiza', 'xaxis_name', 'yaxis_name', 'chart_name', 'id_chart', 'category', 'options', 'series'];
}
