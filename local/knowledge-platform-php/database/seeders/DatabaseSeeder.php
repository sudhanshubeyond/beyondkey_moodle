<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Domain tables are managed externally (shared with the .NET app);
        // greeting embeddings are seeded via the
        // POST /api/DocumentIngestion/CreateGreetingQuestionsEmbeddings endpoint.
    }
}
