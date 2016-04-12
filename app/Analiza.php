<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analiza extends Model
{

    protected $table = "analiza";

    public $timestamps = false;

    protected $fillable = ['nazwa', 'file_path'];
}
