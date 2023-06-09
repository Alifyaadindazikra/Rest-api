<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable =[
        'nama',
        'umur',
        'no_hp',
        'riwayat_penyakit',
        'terakhir_periksa',
    ];
}
