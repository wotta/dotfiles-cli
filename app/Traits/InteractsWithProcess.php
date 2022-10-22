<?php

namespace App\Traits;

use Symfony\Component\Process\Process;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @mixin Command;
 */
trait InteractsWithProcess
{
    public function runProcess(array|string $commandText, string $cwd = null, bool $shouldThrow = false): ?string
    {
        $command = $commandText;

        if (! is_array($commandText)) {
            $command = explode(' ', $commandText);
        }

        $process = new Process($command, cwd: $cwd);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            if ($shouldThrow) {
                throw $exception;
            }


            $exceptionProcess = $exception->getProcess();

            $this->error(sprintf(
                'Process "%s" failed with error: %s',
                $exceptionProcess->getCommandLine(),
                $exceptionProcess->getExitCode(),
            ));


            return null;
        }

        return rtrim($process->getOutput());
    }

    protected function locateBinary(string $binary, bool $shouldThrow = true): ?string
    {
        return $this->runProcess('which ' . $binary, shouldThrow: $shouldThrow);
    }
}