<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beras extends Model
{
    /** @use HasFactory<\Database\Factories\BerasFactory> */
    use HasFactory;
    protected $guarded = [
        'id',

    ];
    public function aktuals()
    {
        return $this->hasMany(DataBeras::class);
    }
    public function DataBaru()
    {
        return $this->hasMany(PrediksiDataBaru::class);
    }
    public function DataBulanan()
    {
        return $this->hasMany(PrediksiBulanan::class);
    }
}
