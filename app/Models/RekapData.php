<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapData extends Model
{
    protected $table = 'rekap_data';
    protected $fillable = ['id_rawatInap', 'id_titipKunci'];

    public function rawatInap()
    {
        return $this->belongsTo(RawatInap::class, 'id_rawatInap');
    }

    public function titipKunci()
    {
        return $this->belongsTo(TitipKunci::class, 'id_titipKunci');
    }
}
