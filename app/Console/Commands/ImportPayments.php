<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Contracts\PaymentRecordRepositoryInterface;

class ImportPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:open_payments {limit} {offset} {method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from the Open Payments System Socrata API. Params: {limit} {offset} {method: API|CSV}';

    /**
     * [$recordRepository description]
     * @var [type]
     */
    protected $paymentRecordRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PaymentRecordRepositoryInterface $paymentRecordRepository)
    {
    	parent::__construct();
        $this->recordRepository = $paymentRecordRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $endpoint = 'openpaymentsdata.cms.gov';
    	$dataset = 'tf25-5jad';
    	$limit = intval($this->argument('limit'));
    	$offset = 0;
    	$results = 0;
    	$firstLoop = true;

    	$sodaApiRepository = new SodaApiRepository($endpoint, $dataset, $this->paymentRecordRepository);

        do {
        	if($firstLoop) {
        		$results = $sodaApiRepository->importDataset($limit, $offset);
        		$firstLoop = false;
        	} else {
        		$offset += $limit;
        		$results = $sodaApiRepository->importDataset($limit, $offset);
        	}
        } while ($results > 0);
    }
}
