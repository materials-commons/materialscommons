<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class MaterialsCommonsMonthlyStatisticsMail extends Mailable
{
    use Queueable, SerializesModels;

    private \stdClass $userStats;
    private \stdClass $projectStats;
    private \stdClass $dsStats;
    private \stdClass $fileStats;
    private \stdClass $spreadsheetStats;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userStats, $projectStats, $dsStats, $fileStats, $spreadsheetStats)
    {
        $this->userStats = $userStats;
        $this->projectStats = $projectStats;
        $this->dsStats = $dsStats;
        $this->fileStats = $fileStats;
        $this->spreadsheetStats = $spreadsheetStats;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lastMonth = Carbon::now()->subMonth();
        $month = $lastMonth->monthName;
        $year = $lastMonth->year;
        $date = Carbon::now()->subMonth()->toDateString();
        return $this->subject("Materials Commons Statistics for {$month} {$year}")
                    ->view('email.statistics.monthly', [
                        'month'            => $month,
                        'year'             => $year,
                        'userStats'        => $this->userStats,
                        'projectStats'     => $this->projectStats,
                        'dsStats'          => $this->dsStats,
                        'fileStats'        => $this->fileStats,
                        'spreadsheetStats' => $this->spreadsheetStats,
                    ]);
    }
}
