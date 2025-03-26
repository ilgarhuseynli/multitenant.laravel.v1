<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



//Schedule::command(DeleteTmpFiles::class)
//    ->cron('0 0-4 * * *') // Runs at 00:00, 01:00, 02:00, 03:00, and 04:00
//    ->runInBackground(); //Ensures the task does not block other scheduled tasks.






//COMMANDS FOR START COMMANDS. ADD NEW CRON IN SERVER

//  * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
//  * * * * * php /var/www/html/projectName/artisan schedule:run >> /dev/null 2>&1
//  * * * * * cd /var/www/html/projectName && php artisan schedule:run >> /dev/null 2>&1
