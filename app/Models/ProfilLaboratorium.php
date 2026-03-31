<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilLaboratorium extends Model
{
    protected $table = 'profil_laboratorium';
    protected $fillable = [
        'namaLaboratorium',
        'tentangLaboratorium',
        'visi',
        'misiId',
        'jumlah_komputer',
    ];

    protected $casts = [
        'misiId' => 'string',
    ];

    public function misi(): BelongsTo
    {
        return $this->belongsTo(Misi::class, 'misiId');
    }
}
