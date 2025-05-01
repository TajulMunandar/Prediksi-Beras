<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrediksiDataBaru extends Model
{
    /** @use HasFactory<\Database\Factories\PrediksiDataBaruFactory> */
    use HasFactory;

    protected $guarded = [
        'id',

    ];

    public function beras()
    {
        return $this->belongsTo(Beras::class);
    }
}
