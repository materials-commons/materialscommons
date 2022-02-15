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

        // Cleanup expired directories in the trashcan
        $schedule->command("mc:delete-expired-trashcan-directories")
                 ->everyFifteenMinutes()
                 ->runInBackground()
                 ->withoutOverlapping();

        // Cleanup expired files in the trashcan
        $schedule->command("mc:delete-expired-trashcan-files")
                 ->everyFifteenMinutes()
                 ->runInBackground()
                 ->withoutOverlapping();

        // Cleanup expired projects in the trashcan
        $schedule->command("mc:delete-expired-trashcan-projects")
                 ->everyFifteenMinutes()
                 ->runInBackground()
                 ->withoutOverlapping();

        // Queue file conversions
        $schedule->command("mc:run-conversion-on-files")
                 ->everyFifteenMinutes()
                 ->runInBackground()
                 ->withoutOverlapping();

        // Delete the actual files, where appropriate
        $schedule->command("mc:delete-tbd-files")
                 ->everyFiveMinutes()
                 ->runInBackground()
                 ->withoutOverlapping();

        $schedule->command("mc-logs:purge-old-log-files")
                 ->everyFifteenMinutes()
                 ->runInBackground()
                 ->withoutOverlapping();

        if (config('app.env') == 'production') {
            $schedule->command('backup:clean')->daily()->at('01:00');
            $schedule->command('backup:run')->daily()->at('01:30');
            $schedule->command('mc:generate-site-map')->daily()->at('3:00');
            $schedule->command('mc:generate-usage-statistics')->monthlyOn(1, '02:00');
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
