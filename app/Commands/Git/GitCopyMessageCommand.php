<?php

namespace App\Commands\Git;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class GitCopyMessageCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'git:copy:message
                            {main=main : Git main branch (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Copy the git commit messages in current branch to the selected main branch';

    public function handle(): int
    {
        
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
