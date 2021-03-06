<?php

namespace Immortal\Cache\Console;

use Immortal\Console\Command;
use Immortal\Cache\CacheManager;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the application cache';

    /**
     * The cache manager instance.
     *
     * @var \Immortal\Cache\CacheManager
     */
    protected $cache;

    /**
     * Create a new cache clear command instance.
     *
     * @param  \Immortal\Cache\CacheManager  $cache
     * @return void
     */
    public function __construct(CacheManager $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $tags = array_filter(explode(',', $this->option('tags')));

        $cache = $this->cache->store($store = $this->argument('store'));

        $this->zgutu['events']->fire('cache:clearing', [$store, $tags]);

        if (! empty($tags)) {
            $cache->tags($tags)->flush();
        } else {
            $cache->flush();
        }

        $this->info('Cache cleared successfully.');

        $this->zgutu['events']->fire('cache:cleared', [$store, $tags]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['store', InputArgument::OPTIONAL, 'The name of the store you would like to clear.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['tags', null, InputOption::VALUE_OPTIONAL, 'The cache tags you would like to clear.', null],
        ];
    }
}
