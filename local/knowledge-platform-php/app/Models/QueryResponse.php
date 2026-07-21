<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/QueryResponse.cs (table: queryresponse).
 */
class QueryResponse extends Model
{
    protected $table = 'queryresponse';

    protected $primaryKey = 'queryresponseid';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'createdat' => 'datetime',
    ];
}
