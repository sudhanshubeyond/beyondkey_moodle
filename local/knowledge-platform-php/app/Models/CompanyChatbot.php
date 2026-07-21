<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Port of DAL/Entities/CompanyChatbot.cs (table: companychatbot).
 */
class CompanyChatbot extends Model
{
    protected $table = 'companychatbot';

    protected $primaryKey = 'companychatbot_id';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'issuggestedquestionsenable' => 'boolean',
        'createdat' => 'datetime',
        'modifiedat' => 'datetime',
    ];
}
