<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Braintify - Learn Fast. Teach Smart. Grow Together.');
})->purpose('Display an inspiring quote');
