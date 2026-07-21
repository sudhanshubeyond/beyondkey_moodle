<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Knowledge Platform — PHP port.');
})->purpose('Sanity-check the console kernel.');
