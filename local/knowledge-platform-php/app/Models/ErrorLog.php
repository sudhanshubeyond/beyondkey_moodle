<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/ErrorLog.cs (table: errorlog).
 */
class ErrorLog extends Model
{
    protected $table = 'errorlog';

    protected $primaryKey = 'errorlogid';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'createdat' => 'datetime',
    ];
}
