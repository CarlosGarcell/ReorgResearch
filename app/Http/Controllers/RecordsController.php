<?php

namespace App\Http\Controllers;

use App\PaymentRecord;

use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Exceptions\InvalidTypeException;

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
     * [index Redirects the user to the main screen with the total number of records in the DB]
     * 
     * @return [Response] [HTTP response with a view and a varialbe indicating the record count in the DB]
     */
    public function index() {
    	return view('welcome')->with(['recordCount' => $this->paymentRecordRepository->getRecordCount()]);
    }

    /**
     * [import Imports X amount of records from the API. For testing purposes, it's been setup to 3000]
     * 
     * @param  Request $request 
     * 
     * @return [string]           [Json encoded string]
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
    	} else {
    		print json_encode([]);
    	}
    }

    /**
     * [search Searches the defined Search index for records that match the expression sent by the user]
     * 
     * @param  Request $request
     * 
     * @return [string]           [Json encoded string]
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
     * [downloadFile Receives a json encoded string that contains all of the ids to be fetched from the database]
     * 
     * @param  Request $request 	[Request object]
     * 
     * @return [Response]           [A response that downloads a file if it was generated, otherwise, redirects to the main view]
     */
    public function downloadFile(Request $request) {
    	if(!is_array($request->matches)) throw new InvalidTypeException('Request parameter must be a json string, ' . gettype($request->matches) . ' given');

    	$ids = array_keys(json_decode($request->matches, true));
    	$records = $this->paymentRecordRepository->fetchRecords($ids);
    	if(count($records) > 0) {
    		$exportFileResult = $this->excelRepository->exportFile($records->toArray());
    		return response()->download($exportFileResult->storagePath . '/' . $exportFileResult->filename . '.' . $exportFileResult->ext);
    	} else {
    		return view('/')->with(['noRecordsFoundMessage' => 'No records were downloaded from the server']);
    	}
    }
}
