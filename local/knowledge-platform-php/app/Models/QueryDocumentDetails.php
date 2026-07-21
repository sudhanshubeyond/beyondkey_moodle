<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/QueryDocumentDetails.cs (table: querydocumentdetails).
 */
class QueryDocumentDetails extends Model
{
    protected $table = 'querydocumentdetails';

    protected $primaryKey = 'querydocumentdetailsid';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'queryresponseid' => 'integer',
        'pageno' => 'integer',
        'createdat' => 'datetime',
    ];
}
