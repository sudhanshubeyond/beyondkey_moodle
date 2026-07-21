<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/QNASummary.cs (table: qnasummary).
 */
class QnaSummary extends Model
{
    protected $table = 'qnasummary';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'questionno' => 'integer',
        'createdat' => 'datetime',
    ];
}
