<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitipKunci extends Model
{
    protected $table = 'titip_kunci';
    protected $fillable = ['tanggal_masuk', 'no_pol', 'nama_petugas'];

    public function user()
    {
        return $this->belongsTo(User::class, 'nama_petugas', 'username');
    }

    public function rekapData()
    {
        return $this->hasOne(RekapData::class, 'id_rawatInap');
    }
}
