<?php

namespace App\Commands\Git;

use App\Traits\InteractsWithProcess;
use LaravelZero\Framework\Commands\Command;

class GitInitialSetupCommand extends Command
{
    use InteractsWithProcess;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'git:init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup the initial git installation for project';

    public function handle()
    {
        $addAllFiles = $this->confirm('Add all files to initial setup?');

        $this->locateBinary('git', shouldThrow: true); // Throw error when bin not found

        $this->runProcess('git init', true);

        if ($addAllFiles) {
            $this->runProcess('git add --all', true);
        }

        $this->runProcess('git commit -m initial', true);


        $this->runProcess('git branch -M ' . $branch = $this->ask('What is the main branch name?', 'main'), true);

        $command = sprintf(
            'git remote add origin git@github.com:%s/%s.git',
            $this->ask('Github username'),
            $this->ask('Repository'),
        );
        $this->runProcess($command, true);

        $this->runProcess('git push -u origin ' . $branch, true);
    }
}
