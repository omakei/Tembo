<?php

namespace Omakei\Tembo\Commands;

use Illuminate\Console\Command;

class TemboCommand extends Command
{
    public $signature = 'tembo';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
