<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrediksiBulanan extends Model
{
    /** @use HasFactory<\Database\Factories\PrediksiBulananFactory> */
    use HasFactory;

    protected $guarded = [
        'id',

    ];

    public function beras()
    {
        return $this->belongsTo(Beras::class);
    }
}
