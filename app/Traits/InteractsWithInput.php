<?php

namespace App\Traits;

use LaravelZero\Framework\Commands\Command;

/**
 * @mixin Command;
 */
trait InteractsWithInput
{
    public function forceAsk(string $question): string
    {
        $answer = $this->ask($question);

        while ($answer === null) {
            $this->warn('Question should be answered');

            $answer = $this->forceAsk($question);
        }

        return $answer;
    }
}