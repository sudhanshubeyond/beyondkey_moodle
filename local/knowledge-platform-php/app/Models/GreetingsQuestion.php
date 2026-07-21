<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/GreetingsQuestion.cs (table: greetingsquestion).
 *
 * `embeddeddata` (vector(1536)) is written/read via raw SQL.
 */
class GreetingsQuestion extends Model
{
    protected $table = 'greetingsquestion';

    protected $primaryKey = 'greetingsquestionid';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'createdat' => 'datetime',
        'modifiedat' => 'datetime',
    ];
}
