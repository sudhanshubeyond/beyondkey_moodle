<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/DocumentDetails.cs (table: documentdetails).
 */
class DocumentDetails extends Model
{
    protected $table = 'documentdetails';

    protected $primaryKey = 'document_id';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'documenttotalpage' => 'integer',
        'companychatbot_id' => 'integer',
        'createdat' => 'datetime',
        'modifiedat' => 'datetime',
    ];
}
