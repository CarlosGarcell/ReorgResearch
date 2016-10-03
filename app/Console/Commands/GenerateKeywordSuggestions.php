<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GenerateKeywordSuggestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:keywords_dictionary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a keyword dictinary to be used in search sugestions';

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
    	Log::info('Began indexing keywords...');

    	$suggestScriptPath = public_path() . '/../resources/assets/SphinxAutocomplete/suggest.php';
    	$keywordsTxtPath = public_path() . '/../resources/assets/SphinxAutocomplete/keywords.txt';
    	$dictionarySqlPath = public_path() . "/../resources/assets/SphinxAutocomplete/dictionary.sql";

        $mySqlCommand = '"/usr/bin/mysql"' . ' -h ' . env('DB_HOST') . 
        				' --user=' . env('DB_USERNAME') . 
        				' --password=' . env('DB_PASSWORD') . ' ' . 
        				env('DB_DATABASE') . ' < "' . $dictionarySqlPath . '"';

        $process = new Process(
        	"sudo /usr/bin/indexer reorgresearch --buildstops " . $keywordsTxtPath . " 100000 --buildfreqs ; " .
        	"/bin/cat " . $keywordsTxtPath . " | sudo /usr/bin/php " . $suggestScriptPath . " --builddict > " . $dictionarySqlPath . " ; "
        );

        $process->run();

        if (!$process->isSuccessful()) throw new ProcessFailedException($process);

        Log::info($process->getOutput());

        $sqlProcess = new Process($mySqlCommand);

        $sqlProcess->run();

        if (!$sqlProcess->isSuccessful()) throw new ProcessFailedException($sqlProcess);

        Log::info('Keywords dictionary has been successfully generated!');

        Log::info($sqlProcess->getOutput());
    }
}
