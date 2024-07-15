<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawatInap extends Model
{
    protected $table = 'rawat_inap';
    protected $fillable = ['tanggal_masuk', 'tanggal_keluar', 'no_pol', 'nama_petugas'];

    public function user()
    {
        return $this->belongsTo(User::class, 'nama_petugas', 'username');
    }

    public function rekapData()
    {
        return $this->hasOne(RekapData::class, 'id_rawatInap');
    }

    public $timestamps = false;
}
