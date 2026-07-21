<?php

namespace App\Support;

/**
 * Helpers for passing float embeddings to/from PostgreSQL pgvector columns.
 *
 * pgvector accepts the textual literal form "[0.1,0.2,...]". We bind that
 * string and cast it with ::vector in the SQL (replaces the Pgvector.Vector
 * type the .NET code used).
 */
class PgVector
{
    /**
     * @param  array<int,float|int>  $floats
     */
    public static function toLiteral(array $floats): string
    {
        // Use a stable, locale-independent representation for each component.
        $parts = array_map(static function ($f): string {
            return rtrim(rtrim(sprintf('%.8F', (float) $f), '0'), '.');
        }, $floats);

        return '['.implode(',', $parts).']';
    }
}
