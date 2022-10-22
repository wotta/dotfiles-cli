<?php

namespace App\Commands\Ssh;

use App\Traits\InteractsWithInput;
use App\Traits\InteractsWithProcess;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class CreateNewSshCommand extends Command
{
    use InteractsWithInput;
    use InteractsWithProcess;

    protected string $algorithm = 'ed25519';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ssh:create';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create new ssh key';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->locateBinary('ssh-keygen');

        $sshName = $this->forceAsk('ssh name?');

        $filename = $this->forceAsk('ssh filename?');

        $fullFilename = sprintf('%s_%s', $filename, $this->algorithm);

        $output = $this->runProcess([
            'ssh-keygen',
            '-t',
            $this->algorithm,
            '-C',
            $sshName,
            '-N',
            '""',
            '-f',
            $fullFilename,
        ], $_SERVER['HOME'] . '/.ssh/', true);

        if (Str::contains($output, $fullFilename . ' already exists')) {
            $this->error('ssh already generated.');

            $this->comment('key pair can be found: ~/.ssh/' . $fullFilename);

            return 0;
        }

        $this->info('Generated ssh key pair');

        return $output;
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
