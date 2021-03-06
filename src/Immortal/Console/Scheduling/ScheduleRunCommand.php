<?php

namespace Immortal\Console\Scheduling;

use Immortal\Console\Command;

class ScheduleRunCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'schedule:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the scheduled commands';

    /**
     * The schedule instance.
     *
     * @var \Immortal\Console\Scheduling\Schedule
     */
    protected $schedule;

    /**
     * Create a new command instance.
     *
     * @param  \Immortal\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $eventsRan = false;

        foreach ($this->schedule->dueEvents($this->zgutu) as $event) {
            if (! $event->filtersPass($this->zgutu)) {
                continue;
            }

            $this->line('<info>Running scheduled command:</info> '.$event->getSummaryForDisplay());

            $event->run($this->zgutu);

            $eventsRan = true;
        }

        if (! $eventsRan) {
            $this->info('No scheduled commands are ready to run.');
        }
    }
}
