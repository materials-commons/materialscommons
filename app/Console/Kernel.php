<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CreateDatasetZipfileCommand::class,
        Commands\ImportGlobusUploadCommand::class,
        Commands\ProcessFinishedGlobusTasksCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (config('app.beta') != 1) {
            $schedule->command("mc:process-finished-globus-tasks --background")
                     ->everyMinute()
                     ->runInBackground()
                     ->withoutOverlapping();

            // This command runs periodically to clean up any globus requests that didn't properly close.
            $schedule->command('mc-transfer:remove-closed-transfer-requests')
                     ->everyFiveMinutes()
                     ->runInBackground()
                     ->withoutOverlapping();

            // Delete old unverified accounts.
            $schedule->command('mc:delete-unverified-accounts')
                     ->daily()
                     ->at('02:00')
                     ->runInBackground();

            // Cleanup old deleted directories in the trashcan
            $schedule->command("mc:delete-old-trashcan-directories")
                     ->everyFifteenMinutes()
                     ->runInBackground()
                     ->withoutOverlapping();

            // Cleanup old deleted files in the trashcan
            $schedule->command("mc:delete-old-trashcan-files")
                     ->everyFifteenMinutes()
                     ->runInBackground()
                     ->withoutOverlapping();
        }

        if (config('backup.backup.run_backups') != 0) {
            if (config('app.beta') != 1) {
                $schedule->command('backup:clean')->daily()->at('01:00');
                $schedule->command('backup:run')->daily()->at('01:30');
            }
        }

        if (config('app.beta') != 1) {
            if (config('app.env') != 'dev') {
                $schedule->command('mc:generate-site-map')->daily()->at('3:00');
                $schedule->command('mc:generate-usage-statistics')->monthlyOn(1, '02:00');
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
