<?php

namespace App\Http\Controllers;

use App\PaymentRecord;

use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Contracts\ExcelRepositoryInterface;
use App\Contracts\RecordsApiRepositoryInterface;
use App\Contracts\SearchIndexRepositoryInterface;
use App\Contracts\PaymentRecordRepositoryInterface;

class RecordsController extends Controller
{
	/**
	 * [$recordsApiRepository description]
	 * @var [type]
	 */
	protected $recordsApiRepository;

	/**
	 * [$searchIndexRepository description]
	 * @var [type]
	 */
	protected $searchIndexRepository;

	/**
	 * [$paymentRecordRepository description]
	 * @var [type]
	 */
	protected $paymentRecordRepository;

	/**
	 * [$excelRepository description]
	 * @var [type]
	 */
	protected $excelRepository;

    public function __construct(
    	RecordsApiRepositoryInterface $recordsApiRepository, 
    	SearchIndexRepositoryInterface $searchIndexRepository,
    	PaymentRecordRepositoryInterface $paymentRecordRepository,
    	ExcelRepositoryInterface $excelRepository) 
    {
    	$this->recordsApiRepository = $recordsApiRepository;
    	$this->searchIndexRepository = $searchIndexRepository;
    	$this->paymentRecordRepository = $paymentRecordRepository;
    	$this->excelRepository = $excelRepository;
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index() {
    	return view('welcome')->with(['recordCount' => $this->paymentRecordRepository->getRecordCount()]);
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function import(Request $request) {
    	$limit = (isset($request->limit)) ? $request->limit : 3000;
    	$maxIdDB = DB::table('payments')->max('id');
    	$offset = ($maxIdDB !== null) ? $maxIdDB : 0;

    	$importResult = $this->recordsApiRepository->importRecords($limit, $offset);

    	if(count($importResult) > 0) {
    		$this->searchIndexRepository->indexDataset();
    		$this->searchIndexRepository->generateKeywordSuggestions();
    		print json_encode($importResult);
    	}
    }

    /**
     * [fetchRecords description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function search(Request $request) {
    	$searchResult = $this->searchIndexRepository->search(trim($request->searchData));
    	print json_encode($searchResult);
    }

    /**
     * [autocomplete description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function autocomplete(Request $request) {
    	print ($request->keyword !== '') ? json_encode($this->searchIndexRepository->autocomplete($request->keyword)) : json_encode([]);
    }

    /**
     * [buildExportFile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function buildExportFile(Request $request) {
    	$ids = array_keys(json_decode($request->matches, true));
    	$records = $this->paymentRecordRepository->fetchRecords($ids);
    	if(count($records) > 0) {
    		print json_encode($this->excelRepository->exportFile($records->toArray()));
    	} else {
    		print json_encode(['storagePath' => null, 'filePath' => null]);
    	}
    }
}
