<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;

use App\Contracts\RecordRepositoryInterface;

class ImportPaymentsData implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $recordRepository;
    protected $arrayOfFiles;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RecordRepositoryInterface $recordRepository)
    {
        $this->recordRepository = $recordRepository;
        $this->arrayOfFiles = scandir(__DIR__ . '/../../storage/app/public/splittedPayments');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$recordRepository = $this->recordRepository;
    	Log::info('Handling queue...');
    	Log::info($this->arrayOfFiles);

    	foreach ($this->arrayOfFiles as $fileName) {
			if (strpos($file, '.csv')) {
				Excel::load($file, function($rows) use ($recordRepository) {
					Log::info($rows);
				});
			}
    	}	
     //   	Excel::batch('storage/app/public/splittedPayments', function($rowCollection, $file) use($recordRepository) {
     //   		Log::info($file);
     //   		Log::info($rowCollection->toArray());
    	// });
    }
}
