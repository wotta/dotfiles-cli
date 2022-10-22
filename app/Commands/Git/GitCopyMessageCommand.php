<?php

namespace App\Commands\Git;

use Illuminate\Support\Str;
use App\Traits\InteractsWithProcess;
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
            '--no-merges',
            '--oneline',
            '--decorate',
            sprintf('%s..HEAD', $mainBranch),
            '--pretty=format:%s',
        ], true);

        $this->info('Trying to copy commit messages');

        // Run php shell_exec to be able to copy.
        $output = shell_exec(sprintf('echo "%s" | pbcopy', $commitMessages));

        if ($output === 0) {
            $this->error('Could not copy the commit messages to clipboard.');

            $this->info('Copy the commit messages below');

            $this->line('');

            $this->comment($commitMessages);

            return 0;
        }

        $this->info('Done copying');

        return 0;
    }
}
