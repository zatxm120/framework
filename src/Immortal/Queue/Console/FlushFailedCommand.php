<?php

namespace Immortal\Queue\Console;

use Immortal\Console\Command;

class FlushFailedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush all of the failed queue jobs';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->zgutu['queue.failer']->flush();

        $this->info('All failed jobs deleted successfully!');
    }
}
