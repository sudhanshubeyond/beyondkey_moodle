<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/DocumentIngestionStatus.cs (table: documentingestionstatus).
 */
class DocumentIngestionStatus extends Model
{
    protected $table = 'documentingestionstatus';

    protected $primaryKey = 'documentingestionstatusid';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'companychatbot_id' => 'integer',
        'documentstatus' => 'integer',
        'createdat' => 'datetime',
    ];
}
