<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/DocumentChunkDetails.cs (table: documentchunkdetails).
 *
 * The `embeddedchunkdata` (vector(1536)) and `tsv` (tsvector) columns are
 * written/read via raw SQL, not Eloquent attribute casts — pgvector has no
 * first-class Eloquent support. `tsv` is populated by a DB trigger, as in .NET.
 */
class DocumentChunkDetails extends Model
{
    protected $table = 'documentchunkdetails';

    protected $primaryKey = 'documentchunk_id';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'document_id' => 'integer',
        'pagenumber' => 'integer',
        'createdat' => 'datetime',
        'modifiedat' => 'datetime',
    ];
}
