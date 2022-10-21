<?php

namespace App\Commands\Git;

use Illuminate\Support\Str;
use App\Traits\InteractsWithProcess;
use Illuminate\Console\Scheduling\Schedule;
use JetBrains\PhpStorm\NoReturn;
use LaravelZero\Framework\Commands\Command;

class GitCopyMessageCommand extends Command
{
    use InteractsWithProcess;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'git:copy:message';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Copy the git commit messages in current branch to the selected main branch';

    public function handle(): int
    {
        $this->locateBinary('git');

        $mainGitBranch = $this->runProcess([
            'git',
            'remote',
            'show',
            'origin'
        ], true);
        

        $mainBranch = $this->ask('Main branch', (string)Str::of($mainGitBranch)->after('HEAD branch:')->before("\n")->trim());

        $commitMessages = $this->runProcess([
            'git',
            'log',
            '--no-merged',
            '--oneline',
            '--decorate',
            sprintf('%s..HEAD', $mainBranch),
            '--pretty="format:%s"',
        ]);

        dd($commitMessages);
        // git log --no-merges --oneline --decorate master..HEAD --pretty='format:%s'
    }

    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
