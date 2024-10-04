<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Este model representa um país, gerenciando dados no banco como nome, capital e população.
*/
class Country extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'capital',
        'population',
    ];
}