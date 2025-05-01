<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiModel extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluasiModelFactory> */
    use HasFactory;

    protected $guarded = [
        'id',

    ];
}
