<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class IndexDataset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:dataset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotate indexes for current dataset (recommended to run after every dataset update)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$sphinxConfPath = public_path() . '/../config/sphinx.conf';

        $process = new Process("sudo /usr/bin/indexer --rotate --config " . $sphinxConfPath . " --all");
        $process->run();

        if (!$process->isSuccessful()) {
        	throw new ProcessFailedException($process);
        }

        Log::info($process->getOutput());
    }
}